<?php

namespace App\Http\Controllers;

use App\Http\Utils\responseMessage;
use App\Models\exhibitions;
use App\Models\user;
use App\Models\user_has_exhibitions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class userExhibitionController extends Controller
{
    //
    function getUserExhibitions(Request $req)
    {
        $exhibitions = user::query()->select([
            'users.id',
            'users.username'
        ])->with([
            'exhibitions'   => function ($query) {
                $query->select([
                    'exhibitions.id',
                    'exhibitions.name'
                ]);
            }
        ]);

        return DataTables::of($exhibitions)
            ->filterColumn('username', function ($query, $keyword) {
                $query->where('username', 'like', $keyword . '%');
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" id="' . $row->id . '" class="btn btn-primary btn-sm btnAdd"><i class="zmdi zmdi-plus"> Sub</i></button> ';
                return $btn;
            })
            ->addColumn('exhibitions', function (user $row) {
                return $row->exhibitions->map(function ($exhibition) {
                    return '<a href="javascript:void(0)" class="btnExhibitions" id="' . $exhibition->pivot->id . '"><span class="badge badge-pill badge-primary m-1">' . $exhibition->name . '</span></a>';
                })->implode('');
                // $user_exhibitions = user_has_exhibitions::with('exhibitions')->where('user_id', $row->id)->select('id', 'exhibitions_name')->get();

                // $btn = "";
                // foreach ($user_exhibitions as $key => $value) {
                //     $btn .= '<a href="javascript:void(0)" class="btnExhibitions" id="' . $value->id . '"><span class="badge badge-pill badge-primary m-1">' . $value->exhibitions->name . '</span></a>';
                // }
                // return $btn;
            })
            ->rawColumns(['action', 'exhibitions'])
            ->make(true);
    }

    function getExhibitions(Request $req)
    {
        $exhibitions = exhibitions::where('status', 1)->select('id', 'name')->get();
        return responseMessage::responseMessageWithData(1, "Success", 200, $exhibitions);
    }

    function assignExhibitionsToUser(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'exhibition_id' => 'required',
            'user_id'       => 'required'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $exhibitions = exhibitions::find($req->exhibition_id);
        if (empty($exhibitions)) {
            return responseMessage::responseMessage(0, "Exhibition not found", 200);
        }

        $user = user::find($req->user_id);
        if (empty($user)) {
            return responseMessage::responseMessage(0, "User not found", 200);
        }

        if (user_has_exhibitions::where([
            'exhibitions_id'    => $exhibitions->id,
            'user_id'           => $user->id
        ])->exists()) {
            return responseMessage::responseMessage(0, "Exhibition already assigned to user", 200);
        }

        user_has_exhibitions::create([
            'exhibitions_id'    => $exhibitions->id,
            'user_id'           => $user->id,
        ]);

        return responseMessage::responseMessage(1, "Success", 200);
    }

    function deleteAssignExhibitionsToUser(Request $req)
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
}
