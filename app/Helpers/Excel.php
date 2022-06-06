<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Excel\Exports\ArrayToExcelExport;
use App\Helpers\Excel\Imports\ExcelToArrayImport;
use App\Helpers\Upload;

class Excel
{
    /*======================================================================
     * PUBLIC STATIC METHODS
     *======================================================================*/

    /**
     * get the column values in a cell
     *
     * @param String $fileUrl
     * @return Array $rtn
     */
    public static function getFirstColumnValuesByUrl(string $fileUrl)
    {
        $rtn = [];

        try {
            if (Upload::existInPublic($fileUrl)) {
                $basePath = Upload::getBasePath($fileUrl);
                $path = Storage::disk(Upload::DISK_PUBLIC)->path($basePath);
                $rtn = self::getFirstColumn($path);
            } else {
                $msg = 'Invalid excel file url: "' . $fileUrl . '". This does not exist in the public storage.';
                \L0g::error($msg);
                \SlackLog::error($msg);
            }
        } catch (\Exception $e) {
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        return $rtn;
    }

    /**
     * get the first column values in a cell
     * get the column values in a cell
     *
     * @param String $fileUrl
     * @return Array $rtn
     */
    public static function getFirstColumnValuesByUploadedFile(UploadedFile $file)
    {
        $rtn = [];

        try {
            $rtn = self::getFirstColumn($file);
        } catch (\Exception $e) {
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        return $rtn;
    }

    /**
     * get the column values in a cell
     *
     * @param String $fileUrl
     * @return Array $rtn
     */
    public static function getColumnValuesByUrl(string $fileUrl, $cols = [])
    {
        $rtn = [];

        try {
            if (Upload::existInPublic($fileUrl)) {
                $basePath = Upload::getBasePath($fileUrl);
                $path = Storage::disk(Upload::DISK_PUBLIC)->path($basePath);
                $rtn = self::getColumns($path, $cols);
            } else {
                $msg = 'Invalid excel file url: "' . $fileUrl . '". This does not exist in the public storage.';
                \L0g::error($msg);
                \SlackLog::error($msg);
            }
        } catch (\Exception $e) {
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        return $rtn;
    }

    /**
     * get the first column values in a cell
     *
     * @param String $fileUrl
     * @return Array $rtn
     */
    public static function getColumnValuesByUploadedFile(UploadedFile $file, $cols = [])
    {
        $rtn = [];

        try {
            $rtn = self::getColumns($file, $cols);
        } catch (\Exception $e) {
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        return $rtn;
    }

    /**
     * export data array to csv file
     *
     * @param Array $data - data to be insert in the CSV
     * @param String $fileName
     * @param String $path
     * @return Bool|String $rtn - false/url
     */
    public static function arrayToCsv(array $data, string $fileName = '', string $path = '')
    {
        $rtn = false;

        if (empty($fileName)) {
            $fileName = Upload::randomHashName() . '.csv';
        }

        if (empty($path)) {
            $path = Upload::generateTempPath();
        }

        $fullPathName = $path . '/' . $fileName;
        $saved = \MExcel::store(new ArrayToExcelExport($data), $fullPathName, Upload::DISK_PUBLIC);

        if ($saved) {
            $rtn = Storage::disk('public')->url($fullPathName);
        }

        return $rtn;
    }

    /*======================================================================
     * STATIC PRIVATE METHODS
     *======================================================================*/

    /**
     * get the first column values in a cell
     *
     * @param String $filePath
     * @return Array $rtn
     */
    private static function getFirstColumn($file)
    {
        $rtn = [];
        $sheets = \MExcel::toArray(new ExcelToArrayImport(), $file);

        foreach ($sheets as $rows) {
            foreach ($rows as $cells) {
                foreach ($cells as $cell) {
                    if (!empty($cell)) {
                            $rtn[] = $cell;
                    }

                    break;
                }
            }
        }
        return $rtn;
    }

    /**
     * Get values of columns in a file provided .
     *
     * @param String|UploadedFile $filePath
     *
     * @return Array $rtn
     */
    private static function getColumns($file, $cols = [])
    {
        $rtn = [];
        $sheets = \MExcel::toArray(new ExcelToArrayImport(), $file);

        foreach ($sheets as $rows) {
            foreach ($rows as $cells) {
                $temp = [];

                foreach ($cols as $col) {
                    if (isset($cells[$col])) {
                        $temp[] = $cells[$col];
                    }
                }

                $rtn[] = $temp;
            }
        }

        return $rtn;
    }
}
