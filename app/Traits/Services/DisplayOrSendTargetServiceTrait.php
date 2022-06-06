<?php

namespace App\Traits\Services;

trait DisplayOrSendTargetServiceTrait
{
    /**
     * store or update target common (any table that has same column format with T_DisplayTargetContent)
     *
     * @param Array $newIds
     * @param String $modelMainIdName
     * @param Model $modelMain
     * @param Model $modelTarget
     * @param String $targetColumnToSave
     * @param Int|Null $targetFlagCurrentValue
     * @param Int|Null $targetFlagNewValue
     * @param String $targetCurrentListPropertyName
     * @param Repository $targetRepository
     */
    protected function storeUpdateTargetCommon(array $newIds, string $modelMainIdName, $modelMain, $modelTarget, string $targetColumnToSave, $targetFlagCurrentValue, $targetFlagNewValue, string $targetCurrentListPropertyName, $targetRepository)
    {
        if ($targetFlagCurrentValue == $targetFlagNewValue || (is_null($targetFlagCurrentValue) && is_null($targetFlagNewValue))) {
            $oldIds = $modelMain->getAttr($targetCurrentListPropertyName);
            $idsDelete = array_diff($oldIds, $newIds);
            $idsAdd = array_diff($newIds, $oldIds);

            if (count($idsDelete) != 0) {
                $rtn = $targetRepository->NTCannulByAttributes([
                    $modelMainIdName => $modelMain->id,
                    $targetColumnToSave => $idsDelete,
                ]);

                if (!$rtn) {
                    \L0g::error($targetColumnToSave . ' Target not deleted.', [
                        $modelMainIdName => $modelMain->id,
                        'ID\'s to be deleted' => $idsDelete,
                        'Target flag' => $targetFlagCurrentValue,
                    ]);
                    \SlackLog::error($targetColumnToSave . ' Target not deleted. ' . $modelMainIdName . ': ' . $modelMain->id . ' > ' . $targetColumnToSave . ' ID\'s to be deleted:' . json_encode($idsDelete) . ' > Target flag:' . $targetFlagCurrentValue);
                }
            }

            if (count($idsAdd) != 0) {
                $ids = [];

                foreach ($idsAdd as $id) {
                    $ids[] = [
                        $modelMainIdName => $modelMain->id,
                        $targetColumnToSave => $id,
                    ];
                }

                $rtn = $targetRepository->NTCaddBulk($ids);

                if (!$rtn) {
                    \L0g::error($targetColumnToSave . ' Target Id\'s not added.', [
                        $modelMainIdName => $modelMain->id,
                        'ID\'s to be added' => $idsAdd,
                        'Target flag' => $targetFlagCurrentValue,
                    ]);
                    \SlackLog::error($targetColumnToSave . ' Target Id\'s not added. ' . $modelMainIdName . ': ' . $modelMain->id . ' > ' . $targetColumnToSave . ' ID\'s to be added:' . json_encode($idsAdd) . ' > Target flag:' . $targetFlagCurrentValue);
                }
            }

            $modelMain = $modelMain->fresh();
            $newlyRecordedIds = $modelMain->getAttr($targetCurrentListPropertyName);

            if (count($newlyRecordedIds) != count($newIds) || count(array_diff($newlyRecordedIds, $newIds)) != 0) {
                \L0g::error($targetColumnToSave . ' Target Id\'s mismatch.', [
                    $modelMainIdName => $modelMain->id,
                    'Previous ID\'s' => $oldIds,
                    'New record ID\'s' => $newIds,
                    'Recorded ID\'s' => $newlyRecordedIds,
                    'Target flag' => $targetFlagCurrentValue,
                ]);
                \SlackLog::error($targetColumnToSave . ' Target Id\'s not added. ' . $modelMainIdName . ': ' . $modelMain->id . ' > ' . $targetColumnToSave . ' Previous ID\'s:' . json_encode($oldIds) . ' > New record ID\'s:' . json_encode($newIds) . ' > Recorded ID\'s:' . json_encode($newlyRecordedIds) . ' > Target flag:' . $targetFlagCurrentValue);
            }
        } else {
            if ($modelTarget->count() != 0) {
                $rtn = $targetRepository->NTCannulByAttributes([
                    $modelMainIdName => $modelMain->id,
                ]);

                if (!$rtn) {
                    $ids = $modelMain->getAttr($targetCurrentListPropertyName);

                    \L0g::error($targetColumnToSave . ' Target not deleted.', [
                        $modelMainIdName => $modelMain->id,
                        'ID\'s' => $ids,
                        'Target flag' => $targetFlagCurrentValue,
                    ]);
                    \SlackLog::error($targetColumnToSave . ' Target not deleted. ' . $modelMainIdName . ': ' . $modelMain->id . ' > ' . $targetColumnToSave . ' ID\'s:' . json_encode($ids) . ' > Target flag:' . $targetFlagCurrentValue);
                }
            }
        }
    }

    /**
     * store or update display target member code
     *
     * @param Array $newIds
     * @param String $modelMainIdName
     * @param Model $modelMain
     * @param Model $modelTarget
     * @param Int $targetFlagCurrentValue
     * @param Int $targetFlagNewValue
     * @param String $targetCurrentListPropertyName
     * @param Repository $targetRepository
     */
    protected function storeUpdateMemberCode(array $newIds, string $modelMainIdName, $modelMain, $modelTarget, int $targetFlagCurrentValue, int $targetFlagNewValue, string $targetCurrentListPropertyName, $targetRepository)
    {
        $this->storeUpdateTargetCommon(
            $newIds,
            $modelMainIdName,
            $modelMain,
            $modelTarget,
            'kumicd',
            $targetFlagCurrentValue,
            $targetFlagNewValue,
            $targetCurrentListPropertyName,
            $targetRepository
        );
    }

    /**
     * store or update display traget Affiliation Office (AO)
     *
     * @param Array $newIds
     * @param String $modelMainIdName
     * @param Model $modelMain
     * @param Model $modelTarget
     * @param Int $targetFlagCurrentValue
     * @param Int $targetFlagNewValue
     * @param String $targetCurrentListPropertyName
     * @param Repository $targetRepository
     */
    protected function storeUpdateAo(array $newIds, string $modelMainIdName, $modelMain, $modelTarget, int $targetFlagCurrentValue, int $targetFlagNewValue, string $targetCurrentListPropertyName, $targetRepository)
    {
        $this->storeUpdateTargetCommon(
            $newIds,
            $modelMainIdName,
            $modelMain,
            $modelTarget,
            'affiliationOfficeId',
            $targetFlagCurrentValue,
            $targetFlagNewValue,
            $targetCurrentListPropertyName,
            $targetRepository
        );
    }

    /**
     * store or update display target Utilization Business (UB)
     *
     * @param Array $newUbIds
     * @param String $modelMainIdName
     * @param Model $modelMain
     * @param Model $modelTarget
     * @param Int $targetFlagCurrentValue
     * @param Int $targetFlagNewValue
     * @param String $targetCurrentListPropertyName
     * @param Repository $targetRepository
     */
    protected function storeUpdateUb(array $newIds, string $modelMainIdName, $modelMain, $modelTarget, int $targetFlagCurrentValue, int $targetFlagNewValue, string $targetCurrentListPropertyName, $targetRepository)
    {
        $this->storeUpdateTargetCommon(
            $newIds,
            $modelMainIdName,
            $modelMain,
            $modelTarget,
            'utilizationBusinessId',
            $targetFlagCurrentValue,
            $targetFlagNewValue,
            $targetCurrentListPropertyName,
            $targetRepository
        );
    }

    /**
     * store or update target common for multiple columns
     *
     * @param Array $columns
     * @param array $baseId
     * @param String $modelMainIdName
     * @param Model $modelMain
     * @param Model $modelTarget
     * @param Int|Null $targetFlagCurrentValue
     * @param Int|Null $targetFlagNewValue
     * @param String $targetCurrentListPropertyName
     * @param Repository $targetRepository
     */
    protected function storeUpdateTargetMultipleColsCommon(array $columns, array $baseIds, string $modelMainIdName, $modelMain, $modelTarget, $targetFlagCurrentValue, $targetFlagNewValue, string $targetCurrentListPropertyName, $targetRepository)
    {
        if ($targetFlagCurrentValue == $targetFlagNewValue || (is_null($targetFlagCurrentValue) && is_null($targetFlagNewValue))) {
            $oldData = collect($modelMain->getAttr($targetCurrentListPropertyName));
            if (count($baseIds) > 1) {
                $oldIds = $oldData->map(function ($item) use ($baseIds) {
                    $item = collect($item)->filter(function ($item, $key) use ($baseIds) {
                        return in_array($key, $baseIds);
                    });

                    return $item->implode('-');
                })->toArray();

                $newIds = collect($columns)->map(function ($item) use ($baseIds) {
                    $item = collect($item)->filter(function ($item, $key) use ($baseIds) {
                        return in_array($key, $baseIds);
                    });

                    return $item->implode('-');
                })->toArray();
            } else {
                $oldIds = $oldData->pluck($baseIds[0])->toArray();
                $newIds = collect($columns)->pluck($baseIds[0])->toArray();
            }

            $idsDelete = array_diff($oldIds, $newIds);
            $idsAdd = array_diff($newIds, $oldIds);

            $colsToAdd = collect($columns)->filter(function ($column) use ($idsAdd, $baseIds) {
                if (count($baseIds) > 1) {
                    $idsInCol = [];
                    foreach ($baseIds as $id) {
                        $idsInCol[] = $column[$id];
                    }
                    $idsInCol = collect($idsInCol)->implode('-');
                } else {
                    $idsInCol = $column[$baseIds[0]];
                }

                if (in_array($idsInCol, $idsAdd)) {
                    return $column;
                }
            });

            $colsToAdd = $colsToAdd->map(function ($column) use ($modelMainIdName, $modelMain) {
                $column[$modelMainIdName] = $modelMain->id;
                return $column;
            })->toArray();

            if (count($idsDelete) != 0) {
                $idsDeleteList = [];
                $keyItemsToDeleteList = [];

                foreach ($idsDelete as $idToDel) {
                    $idsDeleteList[] = explode('-', $idToDel);
                }

                foreach ($baseIds as $key => $id) {
                    $keyItemsToDeleteList[$id] = [];

                    foreach ($idsDeleteList as $idToDel) {
                        $keyItemsToDeleteList[$id][] = $idToDel[$key];
                    }
                }

                $idsDelete = $idsDeleteList;
                $arr = [
                    $modelMainIdName => $modelMain->id
                ];
                $arr = array_merge($arr, $keyItemsToDeleteList);
                $rtn = $targetRepository->NTCannulByAttributes($arr);

                if (!$rtn) {
                    \L0g::error(implode(',', $baseIds) . ' Target not deleted.', [
                        $modelMainIdName => $modelMain->id,
                        'ID\'s to be deleted' => $idsDelete,
                        'Target flag' => $targetFlagCurrentValue,
                    ]);
                    \SlackLog::error(implode(',', $baseIds) . ' Target not deleted. ' . $modelMainIdName . ': ' . $modelMain->id . ' > ' . implode(',', $baseIds)  . ' ID\'s to be deleted:' . json_encode($idsDelete) . ' > Target flag:' . $targetFlagCurrentValue);
                }
            }
            if (count($colsToAdd) != 0) {
                $rtn = $targetRepository->NTCaddBulk($colsToAdd);
                if (!$rtn) {
                    \L0g::error(implode(',', $baseIds) . ' Target Id\'s not added.', [
                        $modelMainIdName => $modelMain->id,
                        'ID\'s to be added' => $idsAdd,
                        'Target flag' => $targetFlagCurrentValue,
                    ]);
                    \SlackLog::error(implode(',', $baseIds)  . ' Target Id\'s not added. ' . $modelMainIdName . ': ' . $modelMain->id . ' > ' . implode(',', $baseIds)  . ' ID\'s to be added:' . json_encode($idsAdd) . ' > Target flag:' . $targetFlagCurrentValue);
                }
            }

            $modelMain = $modelMain->fresh();
            $newlyRecordedData = $modelMain->getAttr($targetCurrentListPropertyName);
            $newlyRecordedDataIds = collect($newlyRecordedData)->map(function ($item) use ($baseIds) {
                $item = collect($item)->filter(function ($item, $key) use ($baseIds) {
                    return in_array($key, $baseIds);
                });
                return $item->implode('-');
            })->toArray();

            if (count($newlyRecordedData) != count($columns) || count(array_diff($newlyRecordedDataIds, $newIds)) != 0) {
                \L0g::error(implode(',', $baseIds)  . ' Target Id\'s mismatch.', [
                    $modelMainIdName => $modelMain->id,
                    'Previous ID\'s' => $oldIds,
                    'New record ID\'s' => $newIds,
                    'Recorded ID\'s' => json_encode($newlyRecordedData),
                    'Target flag' => $targetFlagCurrentValue,
                ]);
                \SlackLog::error(implode(',', $baseIds)  . ' Target Id\'s not added. ' . $modelMainIdName . ': ' . $modelMain->id . ' > ' . implode(',', $baseIds) . ' Previous ID\'s:' . json_encode($oldIds) . ' > New record ID\'s:' . json_encode($newIds) . ' > Recorded ID\'s:' . json_encode($newlyRecordedData) . ' > Target flag:' . $targetFlagCurrentValue);
            }
        } else {
            if ($modelTarget->count() != 0) {
                $rtn = $targetRepository->NTCannulByAttributes([
                    $modelMainIdName => $modelMain->id,
                ]);

                if (!$rtn) {
                    $ids = $modelMain->getAttr($targetCurrentListPropertyName);

                    \L0g::error(implode(',', $baseIds) . ' Target not deleted.', [
                        $modelMainIdName => $modelMain->id,
                        'ID\'s' => $ids,
                        'Target flag' => $targetFlagCurrentValue,
                    ]);
                    \SlackLog::error(implode(',', $baseIds) . ' Target not deleted. ' . $modelMainIdName . ': ' . $modelMain->id . ' > ' . implode(',', $baseIds) . ' ID\'s:' . json_encode($ids) . ' > Target flag:' . $targetFlagCurrentValue);
                }
            }
        }
    }
}
