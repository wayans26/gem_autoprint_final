<?php

namespace App\Http\Controllers;

use App\Http\Utils\makeid;
use App\Http\Utils\responseMessage;
use App\Models\file;
use App\Models\sub_exhibitions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use DB;
use Illuminate\Support\Facades\Storage;

class subExhibitionsController extends Controller
{
    //
    function getSubExhibitions(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'id_exhibitions' => 'required'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }
        $subExhibitions = sub_exhibitions::query()->select(
            'id',
            'name',
            'status'
        )->where('exhibitions_id', $req->id_exhibitions);

        return DataTables::of($subExhibitions)
            ->addIndexColumn()
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', $keyword . '%');
            })
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" id="' . $row->id . '" class="btn btn-info btn-sm btnEdit"><i class="zmdi zmdi-edit"></i></button> ';
                if ($row->status == 1) {
                    $btn .= '<button type="button" id="' . $row->id . '" class="btn btn-danger btn-sm btnDisable"><i class="zmdi zmdi-close"></i></button> ';
                } else {
                    $btn .= '<button type="button" id="' . $row->id . '" class="btn btn-success btn-sm btnEnable"><i class="zmdi zmdi-check"></i></button> ';
                }
                return $btn;
            })
            ->make(true);
    }

    function addSubExhibition(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'id_exhibitions'    => 'required',
            'name'              => 'required',
            'file_banner'       => 'required|mimes:png,jpg,jpeg',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $exhibition = exhibitions::find($req->id_exhibitions);
        if (empty($exhibition)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }

        try {
            DB::transaction(function () use ($req, $exhibition) {
                $banner_extension = $req->file_banner->getClientOriginalExtension();
                $banner_name = "sub_exhibition_" . $req->name . '.' . makeid::createId(10) . "." . $banner_extension;
                $banner_path = Storage::disk('local')->putFileAs("Exhibitions", $req->file_banner, $banner_name);

                $id_banner = makeid::createUuid();

                file::create([
                    'id'        => $id_banner,
                    'path'      => $banner_path,
                    'name'      => $banner_name,
                    'type'      => "image",
                    'extension' => $banner_extension,
                ]);

                sub_exhibitions::create([
                    'idexhibitions' => $exhibition->id,
                    'name'          => $req->name,
                    'banner'        => $id_banner,
                ]);
            });
            return responseMessage::responseMessage(1, "Success", 200);
        } catch (\Throwable $th) {
            return responseMessage::responseMessage(0, $th->getMessage(), 200);
        }
    }

    function editSubExhibition(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'name'              => 'required',
            'file_banner'       => 'required|mimes:png,jpg,jpeg',
            'id'                => 'required',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $sub_exhibition = sub_exhibitions::find($req->id);
        if (empty($sub_exhibition)) {
            return responseMessage::responseMessage(0, "Sub Exhibition not found", 200);
        }

        try {
            DB::transaction(function () use ($req, $sub_exhibition) {

                $banner_extension = $req->file_banner->getClientOriginalExtension();
                $banner_name = "sub_exhibition_" . $req->name . '.' . makeid::createId(10) . "." . $banner_extension;
                $banner_path = Storage::disk('local')->putFileAs("Exhibitions", $req->file_banner, $banner_name);

                $id_banner = makeid::createUuid();

                file::create([
                    'id'        => $id_banner,
                    'path'      => $banner_path,
                    'name'      => $banner_name,
                    'type'      => "image",
                    'extension' => $banner_extension,
                ]);

                $sub_exhibition->update([
                    'name'          => $req->name,
                    'banner'        => $id_banner,
                ]);
            });
            return responseMessage::responseMessage(1, "Success", 200);
        } catch (\Throwable $th) {
            return responseMessage::responseMessage(0, $th->getMessage(), 200);
        }
    }

    function changeStatus(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'id'        => 'required',
            'status'    => 'required|in:0,1',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $sub_exhibition = sub_exhibitions::find($req->id);
        if (empty($sub_exhibition)) {
            return responseMessage::responseMessage(0, "Sub Exhibition not found", 200);
        }

        $sub_exhibition->update([
            'status' => $req->status,
        ]);

        return responseMessage::responseMessage(1, "Success", 200);
    }

    function getSubExhibitionById(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'id' => 'required',
        ]);

        $sub_exhibition = sub_exhibitions::select(
            'id',
            'name',
            'status'
        )->where('id', $req->id)->first();

        if (empty($sub_exhibition)) {
            return responseMessage::responseMessage(0, "Sub Exhibition not found", 200);
        }

        return responseMessage::responseMessageWithData(1, "Success", 200, $sub_exhibition);
    }
}
