<?php

namespace App\Http\Controllers;

use App\Http\Utils\responseMessage;
use App\Models\exhibitions;
use App\Models\registration_visitor;
use App\Models\sub_exhibitions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class dashboardController extends Controller
{
    //
    function getListVisitor(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'exhibition_id'     => 'required',
            'sub_exhibition_id' => 'required',
            'status'            => 'required|in:0,1,all',
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }
        // $visitor = Cache::remember('dashboard-visitor-list', Carbon::now()->addHours(4), function () use ($req) {
        //     return registration_visitor::query()
        //         ->join('sub_exhibitions', 'sub_exhibitions.id', '=', 'registration_visitors.sub_exhibitions_id')
        //         ->join('exhibitions', 'exhibitions.id', '=', 'sub_exhibitions.exhibitions_id')
        //         ->select(
        //             'exhibitions.name as exhibition_name',
        //             'sub_exhibitions.name as sub_exhibition_name',
        //             'registration_visitors.name as visitor_name',
        //         )
        //         ->when($req->status !== 'all', function ($query) use ($req) {
        //             return $query->where('exhibitions.status', $req->status);
        //         })
        //         ->when($req->exhibition_id !== 'all', function ($query) use ($req) {
        //             return $query->where('exhibitions.id', $req->exhibition_id);
        //         })
        //         ->when($req->sub_exhibition_id !== 'all', function ($query) use ($req) {
        //             return $query->where('sub_exhibitions.id', $req->sub_exhibition_id);
        //         })->get();
        // });
        $visitor = registration_visitor::query()
            ->join('sub_exhibitions', 'sub_exhibitions.id', '=', 'registration_visitors.sub_exhibitions_id')
            ->join('exhibitions', 'exhibitions.id', '=', 'sub_exhibitions.exhibitions_id')
            ->select(
                'exhibitions.name as exhibition_name',
                'sub_exhibitions.name as sub_exhibition_name',
                'registration_visitors.name as visitor_name',
            )
            ->when($req->status !== 'all', function ($query) use ($req) {
                return $query->where('exhibitions.status', $req->status);
            })
            ->when($req->exhibition_id !== 'all', function ($query) use ($req) {
                return $query->where('exhibitions.id', $req->exhibition_id);
            })
            ->when($req->sub_exhibition_id !== 'all', function ($query) use ($req) {
                return $query->where('sub_exhibitions.id', $req->sub_exhibition_id);
            });

        return DataTables::of($visitor)
            ->addIndexColumn()
            ->make(true);
    }

    function getExhibition(Request $req)
    {
        $exhibitions = exhibitions::select(
            'id',
            'name'
        )->get();
        return responseMessage::responseMessageWithData(1, "Success", 200, $exhibitions);
    }

    function getSubExhibition(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'exhibition_id' => 'required'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $sub_exhibitions = sub_exhibitions::select(
            'id',
            'name'
        )->where('exhibitions_id', $req->exhibition_id)->get();

        return responseMessage::responseMessageWithData(1, "Success", 200, $sub_exhibitions);
    }
}
