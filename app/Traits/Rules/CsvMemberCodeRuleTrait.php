<?php

namespace App\Traits\Rules;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Helpers\Excel;
use App\Helpers\Upload;
use App\Interfaces\UnionMemberRepositoryInterface;
use App\Interfaces\UnionLineRepositoryInterface;
use App\Models\UnionMember;
use App\Models\UnionLine;

trait CsvMemberCodeRuleTrait
{
    /*======================================================================
     * STATIC METHODS
     *======================================================================*/

    /**
     * get data from excel resource
     *
     * @param String|Null $resource
     * @param Bool $isReturnBool - set to true if only wants to return boolean value, and only get the first valid value of the array data
     * @return Array|Bool $rtn
     */
    public static function getValidUnionMemberCodeFromExcelUrl($resource, bool $isReturnBool = false)
    {
        $rtn = [];
        $extension = '';
        $hasInvalidArray = false;

        if (!empty($resource)) {
            if (Upload::isValidFileType($resource, \Globals::CSV_ACCEPTEDMIMES)) {
                $data = [];

                if ($resource instanceof UploadedFile) {
                    $data = Excel::getFirstColumnValuesByUploadedFile($resource);
                } elseif (is_string($resource)) {
                    $data = Excel::getFirstColumnValuesByUrl($resource);
                }

                if (count($data) != 0) {
                    $invalidArray = [];
                    $data = json_encode($data);
                    $data = str_replace('\u0000', '', $data);
                    $data = str_replace('\"', '', $data);
                    $data = str_replace('\n', '', $data);
                    $data = json_decode($data);
                    $unionMemberRepository = app()->make(UnionMemberRepositoryInterface::class);
                    $unionLineRepository = app()->make(UnionLineRepositoryInterface::class);

                    foreach ($data as $datum) {
                        $error = '';

                        if (is_numeric(trim((string)$datum))) {
                            $datum = (int) trim((string)$datum);

                            if (strlen((string) $datum) <= 8) {
                                $datum = (string) $datum;
                                $datum = str_pad($datum, 8, '0', STR_PAD_LEFT);
                                $unionMember = $unionMemberRepository->acquireByAttributes([
                                    'unionMemberCode' => $datum,
                                    'delFlg' => $unionMemberRepository->Model::STATUS_NOTDELETED,
                                ]);

                                if ($unionMember->count() >= 1) {
                                    $unionMember = $unionMember->first();

                                    if ($unionMember->withdrawalApplicationDate == '0000-00-00 00:00:00' && $unionMember->withdrawalDate == '0000-00-00 00:00:00') {
                                        $unionLine = $unionLineRepository->acquireByAttributes([
                                            'unionMemberCode' => $datum,
                                            'stopFlg' => $unionLineRepository->Model::STOPFLG_NO,
                                            'delFlg' => $unionLineRepository->Model::STATUS_NOTDELETED,
                                        ]);

                                        if ($unionLine->count() >= 1) {
                                            if (!in_array($datum, $rtn)) {
                                                $rtn[] = $datum;
                                            }
                                        } else {
                                            $error = __('messages.custom.invalidMemberCodeNoLineLink');
                                        }
                                    } else {
                                        $error = __('messages.custom.invalidMemberCodeWithdrawalDate');
                                    }
                                } else {
                                    $error = __('messages.custom.invalidMemberCodeNoUnionMemberFound');
                                }
                            } else {
                                $error = __('messages.custom.invalidMemberCode');
                            }
                        } else {
                            $error = __('messages.custom.invalidMemberCode');
                        }

                        if (!empty($error)) {
                            $hasInvalidArray = true;
                        }

                        $invalidArray[] = [$datum, $error];
                    }
                }
            }
        }

        if ($isReturnBool) {
            $rtn = !$hasInvalidArray && count($rtn) != 0;
        }

        if (!empty($resource)) {
            if ($hasInvalidArray && $resource instanceof UploadedFile) {
                $url = Excel::arrayToCsv($invalidArray, __('words.Error') . $resource->getClientOriginalName());
                session()->flash('invalidCsvUrl', $url);
            }
        }

        return $rtn;
    }
}
