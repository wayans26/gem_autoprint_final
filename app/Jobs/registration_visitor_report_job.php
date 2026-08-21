<?php

namespace App\Jobs;

use App\Exports\registration_visitor_export;
use App\Models\report_file;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Excel as MaatwebsiteExcel;
use Maatwebsite\Excel\Facades\Excel;
use RuntimeException;
use Throwable;

class registration_visitor_report_job implements ShouldQueue
{
    use Queueable;

    public $tries = 1;
    public $timeout = 80;
    public $failOnTimeout = true;

    protected $idfile;
    protected $start_time;

    public function __construct($idfile)
    {
        $this->idfile = $idfile;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->start_time = microtime(true);
        $report_file = report_file::find($this->idfile);

        if (empty($report_file)) {
            throw new RuntimeException('Report file record not found.');
        }
        if ($report_file->report_type !== 'visitor') {
            throw new RuntimeException('Invalid report type.');
        }
        if (empty($report_file->user_id)) {
            throw new RuntimeException('Report file owner not found.');
        }
        if ($report_file->status !== report_file::STATUS_PROCESSING) {
            return;
        }

        $file_name = basename($report_file->file_name);
        if (strtolower(pathinfo($file_name, PATHINFO_EXTENSION)) !== 'xlsx') {
            throw new RuntimeException('Invalid report file extension.');
        }

        $path = 'Reports/Visitor/' . $file_name;
        $file = Excel::store(
            new registration_visitor_export(
                $report_file->selected_fields ?? [],
                $report_file->filters ?? []
            ),
            $path,
            'local',
            MaatwebsiteExcel::XLSX
        );

        if (!$file) {
            throw new RuntimeException('Failed to store visitor report.');
        }

        $execution_time = microtime(true) - $this->start_time;
        $report_file->update([
            'path'          => $path,
            'execute_time'  => round($execution_time, 2),
            'status'        => report_file::STATUS_COMPLETED,
            'exception'     => null,
            'completed_at'  => now(),
        ]);
    }

    public function failed(Throwable $exception): void
    {
        $report_file = report_file::find($this->idfile);

        if (empty($report_file)) {
            return;
        }

        $file_name = basename($report_file->file_name);
        if ($file_name !== '') {
            Storage::disk('local')->delete('Reports/Visitor/' . $file_name);
        }

        $execution_time = $this->start_time === null ? null : round(microtime(true) - $this->start_time, 2);
        $report_file->update([
            'path'          => null,
            'execute_time'  => $execution_time,
            'status'        => report_file::STATUS_FAILED,
            'exception'     => Str::limit($exception->getMessage(), 2000, ''),
            'completed_at'  => now(),
        ]);
    }
}
