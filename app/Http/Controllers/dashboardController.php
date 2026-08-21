<?php

namespace App\Http\Controllers;

use App\Exports\registration_visitor_export;
use App\Http\Utils\responseMessage;
use App\Jobs\registration_visitor_report_job;
use App\Models\exhibitions;
use App\Models\report_file;
use App\Models\registration_visitor;
use App\Models\sub_exhibitions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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

        $visitor = registration_visitor::query()
            ->join('sub_exhibitions', 'sub_exhibitions.id', '=', 'registration_visitors.sub_exhibitions_id')
            ->join('exhibitions', 'exhibitions.id', '=', 'sub_exhibitions.exhibitions_id')
            ->select(
                'exhibitions.name as exhibition_name',
                'sub_exhibitions.name as sub_exhibition_name',
                'registration_visitors.name as visitor_name',
                'registration_visitors.is_printed'
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

    function getVisitorChart(Request $req)
    {
        $validate = Validator::make($req->all(), $this->visitorFilterRules());

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $visitor = registration_visitor::query()
            ->join('sub_exhibitions', 'sub_exhibitions.id', '=', 'registration_visitors.sub_exhibitions_id')
            ->join('exhibitions', 'exhibitions.id', '=', 'sub_exhibitions.exhibitions_id')
            ->select(
                'exhibitions.id as exhibition_id',
                'exhibitions.name as exhibition_name',
                DB::raw('COUNT(DISTINCT registration_visitors.id) as total_registration'),
                DB::raw('COUNT(DISTINCT CASE WHEN registration_visitors.is_printed = 1 THEN registration_visitors.id END) as total_printed')
            );

        $visitor = $this->applyVisitorFilters($visitor, $req)
            ->groupBy('exhibitions.id', 'exhibitions.name')
            ->orderBy('exhibitions.name', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'exhibition_id'       => $item->exhibition_id,
                    'exhibition_name'     => $item->exhibition_name,
                    'total_registration' => (int) $item->total_registration,
                    'total_printed'       => (int) $item->total_printed,
                ];
            });

        return responseMessage::responseMessageWithData(1, "Success", 200, $visitor);
    }

    function getVisitorChartList(Request $req)
    {
        $validate = Validator::make($req->all(), $this->visitorFilterRules());

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $visitor = registration_visitor::query()
            ->join('sub_exhibitions', 'sub_exhibitions.id', '=', 'registration_visitors.sub_exhibitions_id')
            ->join('exhibitions', 'exhibitions.id', '=', 'sub_exhibitions.exhibitions_id')
            ->select(
                'registration_visitors.name as visitor_name',
                'registration_visitors.company',
                'registration_visitors.country',
                'registration_visitors.email',
                'exhibitions.name as exhibition_name',
                'sub_exhibitions.name as sub_exhibition_name',
                DB::raw('COALESCE(registration_visitors.register_date, registration_visitors.created_at) as registration_date'),
                'registration_visitors.is_printed'
            );

        $visitor = $this->applyVisitorFilters($visitor, $req);

        return DataTables::of($visitor)
            ->addIndexColumn()
            ->filterColumn('visitor_name', function ($query, $keyword) {
                $query->where('registration_visitors.name', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('company', function ($query, $keyword) {
                $query->where('registration_visitors.company', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('country', function ($query, $keyword) {
                $query->where('registration_visitors.country', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('email', function ($query, $keyword) {
                $query->where('registration_visitors.email', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('exhibition_name', function ($query, $keyword) {
                $query->where('exhibitions.name', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('sub_exhibition_name', function ($query, $keyword) {
                $query->where('sub_exhibitions.name', 'like', '%' . $keyword . '%');
            })
            ->filterColumn('registration_date', function ($query, $keyword) {
                $query->whereRaw(
                    'COALESCE(registration_visitors.register_date, registration_visitors.created_at) like ?',
                    ['%' . $keyword . '%']
                );
            })
            ->orderColumn('visitor_name', 'registration_visitors.name $1')
            ->orderColumn('company', 'registration_visitors.company $1')
            ->orderColumn('country', 'registration_visitors.country $1')
            ->orderColumn('email', 'registration_visitors.email $1')
            ->orderColumn('exhibition_name', 'exhibitions.name $1')
            ->orderColumn('sub_exhibition_name', 'sub_exhibitions.name $1')
            ->orderColumn(
                'registration_date',
                'COALESCE(registration_visitors.register_date, registration_visitors.created_at) $1'
            )
            ->orderColumn('is_printed', 'registration_visitors.is_printed $1')
            ->make(true);
    }

    function getVisitorExportFields(Request $req)
    {
        $fields = registration_visitor_export::getExportFieldOptions();

        return responseMessage::responseMessageWithData(1, "Success", 200, $fields);
    }

    function requestVisitorExport(Request $req)
    {
        $allowed_fields = registration_visitor_export::getAllowedFields();
        $rules = array_merge($this->visitorFilterRules(), [
            'fields'    => 'required|array|min:1',
            'fields.*'  => [
                'required',
                'string',
                'distinct',
                Rule::in($allowed_fields),
            ],
        ]);

        $validate = Validator::make($req->all(), $rules);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }

        $filters = [
            'status'            => $req->status,
            'exhibition_ids'    => array_values($req->input('exhibition_ids', [])),
            'start_date'        => $req->start_date,
            'end_date'          => $req->end_date,
        ];
        $selected_fields = registration_visitor_export::normalizeSelectedFields(array_values($req->fields));

        $report_file = DB::transaction(function () use ($req, $filters, $selected_fields) {
            $report_file = report_file::create([
                'user_id'           => $req->users->id,
                'report_type'       => 'visitor',
                'file_name'         => 'visitor_registration_' . Carbon::now()->format('Ymd_His') . '_' . Str::lower(Str::random(6)) . '.xlsx',
                'selected_fields'   => $selected_fields,
                'filters'           => $filters,
                'status'            => report_file::STATUS_PROCESSING,
            ]);

            registration_visitor_report_job::dispatch($report_file->id)->afterCommit();

            return $report_file;
        });

        return responseMessage::responseMessageWithData(1, "Report is being processed", 200, [
            'id'        => $report_file->id,
            'status'    => $report_file->status,
            'file_name' => $report_file->file_name,
        ]);
    }

    function getExhibition(Request $req)
    {
        $validate = Validator::make($req->all(), [
            'status' => 'required|in:0,1,all'
        ]);

        if ($validate->fails()) {
            return responseMessage::responseMessage(0, $validate->errors()->first(), 200);
        }
        $exhibitions = exhibitions::select(
            'id',
            'name'
        )
            ->when($req->status !== 'all', function ($query) use ($req) {
                return $query->where('status', $req->status);
            })
            ->get();
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

    private function visitorFilterRules()
    {
        return [
            'status'                => 'required|in:0,1,all',
            'exhibition_ids'        => 'nullable|array',
            'exhibition_ids.*'      => [
                'integer',
                'distinct',
                Rule::exists('exhibitions', 'id'),
            ],
            'start_date'            => 'nullable|required_with:end_date|date_format:Y-m-d',
            'end_date'              => 'nullable|required_with:start_date|date_format:Y-m-d|after_or_equal:start_date',
        ];
    }

    private function applyVisitorFilters($query, Request $req)
    {
        return $query
            ->when($req->status !== 'all', function ($query) use ($req) {
                return $query->where('exhibitions.status', $req->status);
            })
            ->when(!empty($req->exhibition_ids), function ($query) use ($req) {
                return $query->whereIn('exhibitions.id', $req->exhibition_ids);
            })
            ->when($req->filled('start_date') && $req->filled('end_date'), function ($query) use ($req) {
                return $query->whereBetween(
                    DB::raw('COALESCE(registration_visitors.register_date, registration_visitors.created_at)'),
                    [
                        Carbon::createFromFormat('Y-m-d', $req->start_date)->startOfDay(),
                        Carbon::createFromFormat('Y-m-d', $req->end_date)->endOfDay(),
                    ]
                );
            });
    }
}
