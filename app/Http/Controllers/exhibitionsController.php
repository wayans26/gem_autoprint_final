<?php

namespace App\Http\Controllers;

use App\Http\Utils\makeid;
use App\Http\Utils\responseMessage;
use App\Models\exhibitions;
use App\Models\file;
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
            'banner_file'       => 'required|mimes:png,jpg,jprg',
            'all_banner_file'   => 'required|mimes:png,jpg,jprg',
            'location'          => 'required',
            'date'              => 'required',
            'team'              => 'required',
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
            if ($exhibition->form != $req->form) {
                return responseMessage::responseMessage(0, "Active Exhibitions With Different Form Not Allowed", 200);
            }
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
                    'host'          => $req->host,
                    'opening_hours' => $req->opening_hours,
                    'path'          => $req->path,
                ]);
            });
            return responseMessage::responseMessage(1, "Success Add Exhibition", 200);
        } catch (\Throwable $th) {
            Storage::disk('local')->delete($banner_path);
            Storage::disk('local')->delete($all_banner_path);
            return responseMessage::responseMessage(0, $th->getMessage(), 200);
        }
    }
    function editExhibitions(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'code'              => 'required',
            'name'              => 'required',
            'full_name'         => 'required',
            'banner_file'       => 'required|mimes:png,jpg,jprg',
            'all_banner_file'   => 'required|mimes:png,jpg,jprg',
            'location'          => 'required',
            'date'              => 'required',
            'team'              => 'required',
            'form'              => 'required',
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
            if ($exhibition->form != $req->form) {
                return responseMessage::responseMessage(0, "Active Exhibitions With Different Form Not Allowed", 200);
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
                    'host'          => $req->host,
                    'opening_hours' => $req->opening_hours,
                    'path'          => $req->path,
                ]);
            });
            return responseMessage::responseMessage(1, "Success Edit Exhibition", 200);
        } catch (\Throwable $th) {
            Storage::disk('local')->delete($banner_path);
            Storage::disk('local')->delete($all_banner_path);
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
            'page',
            'path',
        )->where('id', $req->id)->first();



        if (empty($exhibition)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }

        return responseMessage::responseMessageWithData(1, "Success", 200, $exhibition);
    }











    function getExhibitionsUser(Request $req)
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
                return $btn;
            })
            ->addColumn('staff', function ($row) {
                $user_exhibitions = user_has_exhibitions::join('users', 'users.id', '=', 'user_has_exhibitions.user_id')
                    ->select('users.username', 'user_has_exhibitions.id')
                    ->where('user_has_exhibitions.exhibition_id', $row->id)
                    ->get();

                $btn = "";
                foreach ($user_exhibitions as $key => $value) {
                    $btn .= '<a href="javascript:void(0)" class="btnExhibitions" id="' . $value->id . '"><span class="badge badge-pill badge-primary m-1">' . $value->username . '</span></a>';
                }
                return $btn;
            })
            ->rawColumns(['action', 'exhibitions'])
            ->make(true);
    }

    function getListExhibitionsUser(Request $req)
    {
        $exhibitions = exhibitions::where('status', 1)->select('idexhibitions', 'name')->get();
        return responseMessage::responseMessageWithData(1, "Success", 200, $exhibitions);
    }

    function assignExhibitionsToUserUser(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'idexhibitions' => 'required',
            'iduser'        => 'required'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $exhibitions = exhibitions::find($req->idexhibitions);
        if (empty($exhibitions)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }

        $user = user::find($req->iduser);
        if (empty($user)) {
            return responseMessage::responseMessage(0, "User not found", 200);
        }

        user_has_exhibitions::create([
            'exhibition_id'     => $req->idexhibitions,
            'user_id'           => $req->iduser,
            'exhibitions_name'  => $exhibitions->name
        ]);

        return responseMessage::responseMessage(1, "Success", 200);
    }
    function deleteAssignExhibitionsToUserUser(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'id' => 'required',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $exhibition = user_has_exhibitions::find($req->id);
        if (empty($exhibition)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }
        $exhibition->delete();

        return responseMessage::responseMessage(1, "Success", 200);
    }

    function changeShowStatusUser(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'idexhibitions' => 'required',
            'cmd'           => 'required|in:1,0'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $exhibitions = exhibitions::find($req->idexhibitions);
        if (empty($exhibitions)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }
        $exhibitions->update([
            'is_show'   => $req->cmd
        ]);

        return responseMessage::responseMessage(1, "Success", 200);
    }
}
