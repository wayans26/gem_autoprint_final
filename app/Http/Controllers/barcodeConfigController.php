<?php

namespace App\Http\Controllers;

use App\Http\Utils\responseMessage;
use App\Models\barcode_config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class barcodeConfigController extends Controller
{
    //
    function getBarcodeConfig(Request $req)
    {
        $barcode_config = barcode_config::select(
            'config_name',
            'config_value'
        )->get()->keyBy('config_name');

        return responseMessage::responseMessageWithData(1, "Success", 200, $barcode_config);
    }

    function saveConfig(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'qr_size'               => 'required|integer',
            'qr_margin'             => 'required|integer',
            'error_correction'      => 'required|in:L,M,Q,H',
            'paper_width'           => 'required|numeric',
            'paper_height'          => 'required|numeric',
            'qr_position_bottom'    => 'required|numeric',
            'safe_area_bottom'      => 'required|numeric',
            'safe_area_top'         => 'required|numeric',
            'safe_area_right'       => 'required|numeric',
            'safe_area_left'        => 'required|numeric',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessageWithData(0, $validate->errors()->first(), 200);
        }

        barcode_config::updateOrCreate(
            [
                'config_name' => 'qr_size'
            ],
            [
                'config_value' => $req->qr_size
            ]
        );
        barcode_config::updateOrCreate(
            [
                'config_name' => 'qr_margin'
            ],
            [
                'config_value' => $req->qr_margin
            ]
        );
        barcode_config::updateOrCreate(
            [
                'config_name' => 'error_correction'
            ],
            [
                'config_value' => $req->error_correction
            ]
        );
        barcode_config::updateOrCreate(
            [
                'config_name' => 'paper_width'
            ],
            [
                'config_value' => $req->paper_width
            ]
        );
        barcode_config::updateOrCreate(
            [
                'config_name' => 'paper_height'
            ],
            [
                'config_value' => $req->paper_height
            ]
        );
        barcode_config::updateOrCreate(
            [
                'config_name' => 'qr_position_bottom'
            ],
            [
                'config_value' => $req->qr_position_bottom
            ]
        );
        barcode_config::updateOrCreate(
            [
                'config_name' => 'safe_area_bottom'
            ],
            [
                'config_value' => $req->safe_area_bottom
            ]
        );
        barcode_config::updateOrCreate(
            [
                'config_name' => 'safe_area_top'
            ],
            [
                'config_value' => $req->safe_area_top
            ]
        );
        barcode_config::updateOrCreate(
            [
                'config_name' => 'safe_area_right'
            ],
            [
                'config_value' => $req->safe_area_right
            ]
        );
        barcode_config::updateOrCreate(
            [
                'config_name' => 'safe_area_left'
            ],
            [
                'config_value' => $req->safe_area_left
            ]
        );
        return responseMessage::responseMessage(1, "Success", 200);
    }
}
