<?php

namespace App\Http\Controllers;

use App\Http\Utils\responseMessage;
use App\Models\report_file;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class reportController extends Controller
{
    function getReportList(Request $Req)
    {
        if (empty($Req->users)) {
            return responseMessage::responseMessage(401, "Unauthorized", 401);
        }

        $report_file = report_file::query()
            ->where([
                'user_id'       => $Req->users->id,
                'report_type'   => 'visitor',
            ])
            ->select(
                'id',
                'report_type',
                'file_name',
                'path',
                'selected_fields',
                'filters',
                'status',
                'execute_time',
                'completed_at',
                'created_at'
            )
            ->orderBy('created_at', 'desc');

        return DataTables::of($report_file)
            ->addIndexColumn()
            ->addColumn('status_name', function ($row) {
                if ($row->status === report_file::STATUS_COMPLETED) {
                    return 'Completed';
                }
                if ($row->status === report_file::STATUS_FAILED) {
                    return 'Failed';
                }

                return 'Processing';
            })
            ->addColumn('selected_field_count', function ($row) {
                return sizeof($row->selected_fields ?? []);
            })
            ->addColumn('can_download', function ($row) {
                return $row->status === report_file::STATUS_COMPLETED &&
                    !empty($row->path) &&
                    Storage::exists($row->path);
            })
            ->removeColumn('path')
            ->make(true);
    }

    function downloadFile(Request $Req, $id)
    {
        $file = report_file::find($id);

        if (empty($file)) {
            return abort(404);
        }
        if (empty($file->path) || !Storage::exists($file->path)) {
            return abort(404);
        }

        $reportFile = Storage::path($file->path);
        return response()->download($reportFile, $file->file_name);
    }
}
