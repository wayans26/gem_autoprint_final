<?php

namespace App\Http\Controllers;

use App\Models\registration_visitor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class dashboardController extends Controller
{
    //
    function get_list_visitor(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'exhibition_id' => 'required',
            'sub_exhibition_id' => 'required',
            'status' => 'required|in:0,1',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }
        $visitor = Cache::remember('dashboard_visitor_list', Carbon::now()->addHours(4), function () use ($req) {
            return registration_visitor::query();
        });

        return DataTables::of($visitor)->make(true);
    }
}
