<?php

namespace App\Http\Controllers;

use App\Http\Utils\makeid;
use App\Http\Utils\responseMessage;
use App\Models\exhibitions;
use App\Models\file;
use App\Models\sub_exhibitions;
use App\Models\user;
use App\Models\user_has_exhibitions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use DB;

class exhibitionsController extends Controller
{
    //
    function getExhibitions(Request $req)
    {
        $exhibitions = exhibitions::query();

        return DataTables::of($exhibitions)
            ->filterColumn('code', function ($query, $keyword) {
                $query->where('code', 'like', $keyword . '%');
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', $keyword . '%');
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" id="' . $row->id . '" class="btn btn-primary btn-sm btnAdd"><i class="zmdi zmdi-plus"> Sub</i></button> ';
                $btn .= '<button type="button" id="' . $row->id . '" class="btn btn-info btn-sm btnEdit"><i class="zmdi zmdi-edit"></i></button> ';
                if ($row->status == 1) {
                    $btn .= '<button type="button" id="' . $row->id . '" class="btn btn-danger btn-sm btnDisable"><i class="zmdi zmdi-close"></i></button> ';
                } else {
                    $btn .= '<button type="button" id="' . $row->id . '" class="btn btn-success btn-sm btnEnable"><i class="zmdi zmdi-check"></i></button> ';
                }
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    function addExhibitions(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'code'              => 'required',
            'name'              => 'required',
            'full_name'         => 'required',
            'banner_file'       => 'required|mimes:png,jpg,jpeg',
            'all_banner_file'   => 'required|mimes:png,jpg,jpeg',
            'location'          => 'required',
            'date'              => 'required',
            'team'              => 'required',
            'type'              => 'required',
            'form'              => 'required',
            'host'              => 'required',
            'opening_hours'     => 'required',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $exhibition = exhibitions::where([
            'host'      => $req->host,
            'status'    => 1
        ])->first();

        if (!empty($exhibition)) {
            if ($exhibition->page != $req->form) {
                return responseMessage::responseMessage(0, "Active Exhibitions With Different Form Not Allowed", 200);
            }
        }

        if (exhibitions::where('code', $req->code)->exists()) {
            return responseMessage::responseMessage(0, "Exhibition Code Already Exists", 200);
        }

        try {
            DB::transaction(function () use ($req) {
                $banner_extension = $req->banner_file->getClientOriginalExtension();
                $banner_name = "Banner_" . $req->code . $req->name . '.' . makeid::createId(10) . "." . $banner_extension;
                $banner_path = Storage::disk('local')->putFileAs("Exhibitions", $req->banner_file, $banner_name);

                $all_banner_extension = $req->all_banner_file->getClientOriginalExtension();
                $all_banner_name = "all_" . $req->code . $req->name . '.' . makeid::createId(10) . "." . $all_banner_extension;
                $all_banner_path = Storage::disk('local')->putFileAs("Exhibitions", $req->all_banner_file, $all_banner_name);

                $idBanner = makeid::createUuid();
                $idAllBanner = makeid::createUuid();

                file::create([
                    'id'    => $idBanner,
                    'path'  => $banner_path,
                    'name'  => $banner_name,
                    'type'  => "image",
                    'extension' => $banner_extension,
                ]);

                file::create([
                    'id'    => $idAllBanner,
                    'path'  => $all_banner_path,
                    'name'  => $all_banner_name,
                    'type'  => "image",
                    'extension' => $all_banner_extension,
                ]);

                exhibitions::create([
                    'code'          => $req->code,
                    'name'          => $req->name,
                    'full_name'     => $req->full_name,
                    'banner_file'   => $idBanner,
                    'all_banner'    => $idAllBanner,
                    'location'      => $req->location,
                    'date'          => $req->date,
                    'team'          => $req->team,
                    'page'          => $req->form,
                    'page'          => $req->type,
                    'host'          => $req->host,
                    'opening_hours' => $req->opening_hours,
                    'path'          => $req->path,
                ]);
            });
            return responseMessage::responseMessage(1, "Success Add Exhibition", 200);
        } catch (\Throwable $th) {
            return responseMessage::responseMessage(0, $th->getMessage(), 200);
        }
    }
    function editExhibitions(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'code'              => 'required',
            'name'              => 'required',
            'full_name'         => 'required',
            'banner_file'       => 'required|mimes:png,jpg,jpeg',
            'all_banner_file'   => 'required|mimes:png,jpg,jpeg',
            'location'          => 'required',
            'date'              => 'required',
            'team'              => 'required',
            'form'              => 'required|in:reguler,vip,busworld',
            'type'              => 'required',
            'host'              => 'required',
            'opening_hours'     => 'required',
            'id'                => 'required',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $exhibition = exhibitions::find($req->id);
        if (empty($exhibition)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        } else {
            if ($exhibition->page != $req->form) {
                return responseMessage::responseMessage(0, "Active Exhibitions With Different Form Not Allowed", 200);
            }

            if ($exhibition->code !== $req->code) {
                if (exhibitions::where('code', $req->code)->exists()) {
                    return responseMessage::responseMessage(0, "Exhibition Code Already Exists", 200);
                }
            }
        }

        try {
            DB::transaction(function () use ($req, $exhibition) {
                $banner_extension = $req->banner_file->getClientOriginalExtension();
                $banner_name = "Banner_" . $req->code . $req->name . '.' . makeid::createId(10) . "." . $banner_extension;
                $banner_path = Storage::disk('local')->putFileAs("Exhibitions", $req->banner_file, $banner_name);

                $all_banner_extension = $req->all_banner_file->getClientOriginalExtension();
                $all_banner_name = "all_" . $req->code . $req->name . '.' . makeid::createId(10) . "." . $all_banner_extension;
                $all_banner_path = Storage::disk('local')->putFileAs("Exhibitions", $req->all_banner_file, $all_banner_name);

                $idBanner = makeid::createUuid();
                $idAllBanner = makeid::createUuid();

                file::create([
                    'id'    => $idBanner,
                    'path'  => $banner_path,
                    'name'  => $banner_name,
                    'type'  => "image",
                    'extension' => $banner_extension,
                ]);

                file::create([
                    'id'    => $idAllBanner,
                    'path'  => $all_banner_path,
                    'name'  => $all_banner_name,
                    'type'  => "image",
                    'extension' => $all_banner_extension,
                ]);

                $exhibition->update([
                    'code'          => $req->code,
                    'name'          => $req->name,
                    'full_name'     => $req->full_name,
                    'banner_file'   => $idBanner,
                    'all_banner'    => $idAllBanner,
                    'location'      => $req->location,
                    'date'          => $req->date,
                    'team'          => $req->team,
                    'page'          => $req->form,
                    'type'          => $req->type,
                    'host'          => $req->host,
                    'opening_hours' => $req->opening_hours,
                    'path'          => $req->path,
                ]);
            });
            return responseMessage::responseMessage(1, "Success Edit Exhibition", 200);
        } catch (\Throwable $th) {
            return responseMessage::responseMessage(0, $th->getMessage(), 200);
        }
    }

    function getExhibitionById(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'id' => 'required',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $exhibition = exhibitions::select(
            'id',
            'code',
            'name',
            'full_name',
            'location',
            'date',
            'team',
            'opening_hours',
            'host',
            'type',
            'page',
            'path',
        )->where('id', $req->id)->first();



        if (empty($exhibition)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }

        return responseMessage::responseMessageWithData(1, "Success", 200, $exhibition);
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

        $exhibition = exhibitions::find($req->id);
        if (empty($exhibition)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }
        // dd($req->all());

        $exhibition->update([
            'status' => $req->status,
        ]);

        return responseMessage::responseMessage(1, "Success", 200);
    }

    function getRegistrationExhibition(Request $req)
    {
        $exhibition_list = exhibitions::select(
            'id',
            'name',
            'banner_file',
        )->where([
            'status' => 1,
            'host'   => $req->host()
        ])
            ->when($req->has('path'), function ($query) use ($req) {
                $query->where('path', $req->path);
            })->get();

        $exhibition_detail = exhibitions::select(
            'id',
            'all_banner',
            'banner_file',
            'date',
            'full_name',
            'name',
            'location',
            'team',
        )->where([
            'status' => 1,
            'host'   => $req->host()
        ])
            ->when($req->has('path'), function ($query) use ($req) {
                $query->where('path', $req->path);
            })->first();

        return responseMessage::responseMessageWithData(1, "Success", 200, array(
            'exhibition_list'   => $exhibition_list,
            'exhibition_detail' => $exhibition_detail
        ));
    }

    function getRegistrationSubExhibition(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'id' => 'required',
        ]);
        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $sub_exhibition = sub_exhibitions::where([
            'status'            => 1,
            'exhibitions_id'    => $req->id
        ])->get();

        return responseMessage::responseMessageWithData(1, "Success", 200, $sub_exhibition);
    }
}
