<?php

namespace App\Helpers;

use Carbon\Carbon;

class BatchLog
{
    /**
     * get all files on the path specified in the .env BATCH_LOG_PATH
     *
     * @return Array $rtn - array of files
     */
    public static function getAll()
    {
        $rtn = [];
        $path = config('const.batchLog.path');

        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file) {
            $fullPath = $path . '/' . $file;
            $fileCreatedTimeStamp = filemtime($fullPath);
            $fileCreatedTime = date("H:i", $fileCreatedTimeStamp);
            $fileNameCreatedDateArray = explode('command-', $file);
            $fileNameCreatedDate = date("Y/m/d", $fileCreatedTimeStamp);
            $fileCreatedDateTime = '';

            if (count($fileNameCreatedDateArray) > 1) {
                $fileNameCreatedDate = $fileNameCreatedDateArray[1];
                $fileNameCreatedDate = explode('.log', $fileNameCreatedDate)[0];
                $fileNameCreatedDate = Carbon::parse($fileNameCreatedDate)->format('Y/m/d');
            }

            $fileCreatedDateTime = $fileNameCreatedDate . ' ' . $fileCreatedTime;
            $fileTimeStamp = Carbon::parse($fileCreatedDateTime)->timestamp;

            $rtn[] = [
                'timestamp' => $fileTimeStamp,
                'createdAt' => $fileCreatedDateTime,
                'path' => $fullPath,
                'fileName' => $file,
                'customFileName' => $file,
            ];
        }

        return $rtn;
    }
}
