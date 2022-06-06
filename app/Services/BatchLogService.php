<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Helpers\BatchLog;
use App\Helpers\Upload;
use App\Traits\ModelCollectionTrait;

class BatchLogService
{
    use ModelCollectionTrait;

    /*======================================================================
     * PUBLIC METHODS
     *======================================================================*/

    /**
     * fetch card record
     *
     * @return Array $rtn
     */
    public function allByDate(int $paginatePerPage = 10)
    {
        $page = request()->get('page', 1);
        $date = request()->get('date');

        $logs = BatchLog::getAll();
        $logs = $this->arrayToCollection($logs);
        $rtn = [
            'data' => [],
        ];

        if ($date) {
            $date = Carbon::parse($date)->format('Y/m/d');
            $filteredLogs = $logs->filter(function ($item) use ($date) {
                return Carbon::parse($date)->format('Y/m/d') == Carbon::parse($item['createdAt'])->format('Y/m/d');
            })->values();
            $logs = $filteredLogs;
        }

        $logs = $logs->sortByDesc('timestamp');
        $logs = $this->arrayToPagination($logs->toArray(), $paginatePerPage, $page, [
            'path' => route('batchLog.index')
        ]);
        $rtn['data'] = $logs;

        return $rtn;
    }

    /**
     * get contents of the log file url
     *
     * @return String $rtn
     */
    public function getContent()
    {
        $rtn = '';
        $path = request()->get('path');

        if (!empty($path)) {
            if (Upload::existInServer($path)) {
                $rtn = file_get_contents($path);

                if (!empty($rtn)) {
                    $rtn = nl2br($rtn);
                }
            }
        }

        return $rtn;
    }
}
