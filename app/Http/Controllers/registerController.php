<?php

namespace App\Http\Controllers;

use App\Http\Utils\generatePrint;
use App\Http\Utils\makeid;
use App\Http\Utils\responseMessage;
use App\Http\Utils\sendEmail;
use App\Models\activity_history;
use App\Models\countries;
use App\Models\exhibitions;
use App\Models\registration_visitor;
use App\Models\sub_exhibitions;
use App\Models\user_has_exhibitions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class registerController extends Controller
{
    //
    function getRegisterData(Request $req)
    {
        $country = countries::select('id', 'country_name')->get();

        $exhibitions = exhibitions::join('user_has_exhibitions', 'user_has_exhibitions.exhibitions_id', '=', 'exhibitions.id')
            ->where([
                'user_has_exhibitions.user_id'  => $req->users->id,
                'exhibitions.status'            => 1
            ])
            ->select('exhibitions.id', 'exhibitions.name')->get();

        return responseMessage::responseMessageWithData(1, "Success", 200, array(
            'country'       => $country,
            'exhibitions'   => $exhibitions
        ));
    }

    function getSubExhibitions(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'exhibition_id' => 'required'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $subExhibitions = sub_exhibitions::where('exhibitions_id', $req->exhibition_id)->get();
        return responseMessage::responseMessageWithData(1, "Success", 200, $subExhibitions);
    }

    function registrasi(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'exhibition_id'     => 'required',
            'sub_exhibition_id' => 'required',
            'name'              => 'required',
            'title'             => 'required',
            'company'           => 'required',
            'email'             => 'required|email',
            'phone'             => 'required',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $exhibition = exhibitions::find($req->exhibition_id);
        if (empty($exhibition)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }
        $sub_exhibitions = sub_exhibitions::find($req->sub_exhibition_id);
        if (empty($sub_exhibitions)) {
            return responseMessage::responseMessage(0, "Sub Exhibition not found", 200);
        }

        $country = countries::find($req->country);
        if (empty($country)) {
            return responseMessage::responseMessage(0, "Country not found", 200);
        }

        if (registration_visitor::where([
            'email'                 => $req->email,
            'sub_exhibitions_id'    => $sub_exhibitions->id,
        ])->exists()) {
            return responseMessage::responseMessage(0, "Your Email Has Been Registered", 200);
        }
        $barcode = $exhibition->id . "-" . $sub_exhibitions->id  . "-" .  Carbon::now()->format('dm') . "-" . makeid::createId(6);


        $checkinLocation = "AP";
        $checkinTime = Carbon::now();
        $checkinBy = $req->users->id;


        $visitor_registrasi = registration_visitor::create([
            'sub_exhibitions_id'                        => $sub_exhibitions->id,
            'name_title'                                => "none",
            'name'                                      => $req->name,
            'company'                                   => $req->company,
            'job_title'                                 => $req->title,
            'address'                                   => "none",
            'state'                                     => "none",
            'country'                                   => $country->country_name,
            'mobile_phone'                              => $req->phone,
            'email'                                     => $req->email,
            'job_function'                              => "none",
            'visit_purpose'                             => "none",
            'purchasing_role'                           => "none",
            'event_find'                                => "none",
            'is_received_invitation_next'               => "0",
            'is_received_invitation_next_address_same'  => "1",
            'barcode'                                   => $barcode,
            'is_printed'                                => '1',
            'register_date'                             => $checkinTime,
            'last_checkin_time'                         => $checkinTime,
            'last_checkin_location'                     => $checkinLocation,
        ]);


        $registerId = $registrasi->id;

        activity_history::create([
            'checkin_time' => $checkinTime,
            'checkin_location' => $checkinLocation,
            'user_id' => $checkinBy,
            'registration_visitors_id' => $visitor_registrasi->id,
        ]);

        sendEmail::sendEmailRegistration($registrasi->id, $req);

        $data_print = generatePrint::PDFPPLB($req->name, $req->title, $req->company, $barcode);

        // dd($data_print);

        return responseMessage::responseMessageWithData(1, "Success", 200, $data_print);
    }
    function testPrint(Request $req)
    {


        // $data_print = generatePrint::PPLB("TEST", "TEST", "TEST", "oiuytghjkiuytghjkiuy");
        $data_print = generatePrint::PDFPPLB("TEST", "TEST", "TEST PRINTER WAYAN AJAH DEH WHAHAHAH WAYAN PRINTER COMPANY HAHAHA TEXT", "oiuytghjkiuytghjkiuy");

        // dd($data_print);

        return responseMessage::responseMessageWithData(1, "Success", 200, $data_print);
    }

    function registrationVisitor(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'exhibition_id'                             => 'required',
            'sub_exhibition_id'                         => 'required',
            'name_title'                                => 'nullable|string|max:255',
            'name'                                      => 'required|string|max:255',
            'company'                                   => 'required',
            'job_title'                                 => 'required|string|max:255',
            'address'                                   => 'required',
            'city'                                      => 'nullable|string|max:255',
            'country'                                   => 'required',
            'dial_code'                                 => 'required',
            'telephone'                                 => 'nullable|string|max:255',
            'mobile_phone'                              => 'required',
            'fax'                                       => 'nullable|string|max:255',
            'email'                                     => 'required|string|max:255',
            'business_type'                             => 'nullable|string|max:255',
            'job_function'                              => 'nullable|string|max:255',
            'job_function_other'                        => 'nullable|string',
            'visit_purpose'                             => 'nullable|string|max:255',
            'visit_purpose_other'                       => 'nullable|string',
            'purchasing_role'                           => 'nullable|string|max:255',
            'purchasing_role_other'                     => 'nullable|string',
            'event_find'                                => 'nullable|string|max:255',
            'event_find_other'                          => 'nullable|string',
            'is_received_invitation_next'               => 'nullable',
            'is_received_invitation_next_address_same'  => 'nullable',
            'received_invitation_next_address'          => 'nullable|string|max:255',
            'first_name'                                => 'nullable|string|max:255',
            'last_name'                                 => 'nullable|string|max:255',
            'company_type'                              => 'nullable|string|max:255',
            'company_type_other'                        => 'nullable|string|max:255',
            'line_of_business'                          => 'nullable',
            'is_receive_news_letter'                    => 'nullable|in:0,1',
            'is_agree_policy'                           => 'nullable|in:0,1',
            'job_level'                                 => 'nullable|string|max:255',
            'job_level_other'                           => 'nullable|string|max:255',
            'departement'                               => 'nullable|string|max:255',
            'departement_other'                         => 'nullable|string|max:255',
            'website'                                   => 'nullable|string|max:255',
            'HowKnow'                                   => 'nullable|string|max:255',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }


        $exhibition = exhibitions::find($req->exhibition_id);
        if (empty($exhibition)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }

        $sub_exhibitions = sub_exhibitions::find($req->sub_exhibition_id);
        if (empty($sub_exhibitions)) {
            return responseMessage::responseMessage(0, "Sub Exhibition not found", 200);
        }

        //check 1 user can regis 1 sub
        if (registration_visitor::where([
            'email'                 => $req->email,
            'sub_exhibitions_id'    => $sub_exhibitions->id,
        ])->exists()) {
            return responseMessage::responseMessage(0, "Your Email Has Been Registered", 200);
        }

        // check 1 user can regis to 1 host
        // if(registration_visitor::join('sub_exhibitions', 'sub_exhibitions.idsubexhibitions', '=', 'registration_visitors.sub_exhibitions_id')
        //     ->join('exhibitions', 'exhibitions.id', '=', 'sub_exhibitions.exhibition_id')
        // ->where([
        //     'host'  => $req->host(),
        //     'email' => $req->email,
        // ])->exists()){
        //     return responseMessage::responseMessage(0, "Your Email Has Been Registered", 200);
        // }

        $barcode = $exhibition->id . "-" . $sub_exhibitions->id  . "-" .  Carbon::now()->format('dm') . "-" . makeid::createId(6);
        $dial_code = countries::find($req->dial_code);
        $country = countries::find($req->country);

        if (empty($dial_code)) {
            return responseMessage::responseMessage(0, "Dial code not found", 200);
        }
        if (empty($country)) {
            return responseMessage::responseMessage(0, "Country not found", 200);
        }

        $visitor_registrasi = registration_visitor::create([
            'sub_exhibitions_id'                        => $sub_exhibitions->id,
            'barcode'                                   => $barcode,
            'name_title'                                => $req->name_title,
            'name'                                      => $req->name,
            'company'                                   => $req->company,
            'job_title'                                 => $req->job_title,
            'address'                                   => $req->address,
            'state'                                     => $req->state,
            'country'                                   => $country->country_name,
            'telephone'                                 => $req->telephone,
            'mobile_phone'                              => $dial_code->dial_code . "-" . $req->mobile_phone,
            'fax'                                       => $req->fax,
            'email'                                     => $req->email,
            'business_type'                             => $req->business_type,
            'job_function'                              => $req->job_function,
            'job_function_other'                        => $req->job_function_other,
            'visit_purpose'                             => $req->visit_purpose,
            'visit_purpose_other'                       => $req->visit_purpose_other,
            'purchasing_role'                           => $req->purchasing_role,
            'purchasing_role_other'                     => $req->purchasing_role_other,
            'event_find'                                => $req->event_find,
            'event_find_other'                          => $req->event_find_other,
            'is_received_invitation_next'               => $req->is_received_invitation_next,
            'is_received_invitation_next_address_same'  => $req->is_received_invitation_next_address_same,
            'received_invitation_next_address'          => $req->received_invitation_next_address,
            'first_name'                                => $req->first_name,
            'last_name'                                 => $req->last_name,
            'company_type'                              => $req->company_type,
            'company_type_other'                        => $req->company_type_other,
            'line_of_business'                          => $req->line_of_business,
            'city'                                      => $req->city,
            'is_receive_news_letter'                    => $req->has('is_receive_news_letter') ? $req->is_receive_news_letter : "0",
            'is_agree_policy'                           => $req->has('is_agree_policy') ? $req->is_agree_policy : "0",
            'job_level'                                 => $req->job_level,
            'job_level_other'                           => $req->job_level_other,
            'departement'                               => $req->departement,
            'departement_other'                         => $req->departement_other,
            'website'                                   => $req->website,
            'how_know'                                  => $req->how_know,
        ]);

        sendEmail::sendEmailRegistration($visitor_registrasi->id, $req);

        return responseMessage::responseMessage(1, "Registration Success", 200);
    }
}
