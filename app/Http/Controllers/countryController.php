<?php

namespace App\Http\Controllers;

use App\Http\Utils\responseMessage;
use App\Models\countries;
use Illuminate\Http\Request;

class countryController extends Controller
{
    //
    function getCountry(Request $req)
    {
        $country = countries::select(
            'id',
            'code',
            'country_name',
            'dial_code'
        )->get();

        return responseMessage::responseMessageWithData(1, "Success", 200, $country);
    }
}
