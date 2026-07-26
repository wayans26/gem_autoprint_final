<?php

namespace App\Http\Controllers;

use App\Http\Utils\generatePrint;
use App\Http\Utils\makeid;
use App\Http\Utils\responseMessage;
use App\Models\activity_history;
use App\Models\registration;
use App\Models\registration_visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class visitorPrint extends Controller
{
    //
    function printVisitor(Request $req)
    {

        $validate = Validator::make($req->all(), [
            'barcode'    => 'required'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $visitor = registration_visitor::where('barcode', $req->barcode)->first();

        if (empty($visitor)) {
            return responseMessage::responseMessage(0, "Visitor Not Found", 200);
        }

        $checkinLocation = "AP";
        $checkinTime = Carbon::now();
        $checkinBy = $req->users->id;
        $registerId = $visitor->id;
        $firstRegister = false;

        if (!activity_history::whereDate('checkin_time', $checkinTime->format('Y-m-d'))->where('registration_visitors_id', $registerId)->exists()) {
            activity_history::create([
                'checkin_time'              => $checkinTime,
                'checkin_location'          => $checkinLocation,
                'user_id'                   => $checkinBy,
                'registration_visitors_id'  => $registerId,
            ]);
            $firstRegister = true;
        }

        $name = $visitor->name === null ? $visitor->first_name : $visitor->name;
        $job = $visitor->job_title === null ? $visitor->jub_level : $visitor->job_title;

        $isPrinted = $visitor->is_printed === 0 || $firstRegister ? 0 : 1;
        $visitor->update([
            'is_printed'                => 1,
            'last_checkin_location'     => "AP",
            'last_checkin_time'         => $checkinTime
        ]);
        $data_print = generatePrint::PDFPPLB($registerId);
        return responseMessage::responseMessageWithData(1, "Success", 200, array(
            'data_print' => $data_print,
            'isPrinted'  => $isPrinted
        ));
    }
}
