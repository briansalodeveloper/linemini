<?php

namespace App\Repositories;

use App\Interfaces\UnionMemberRepositoryInterface;
use App\Models\UnionMember;
use App\Models\Flyer\FlyerStoreSelect;

class UnionMemberEloquentRepository extends MainEloquentRepository implements UnionMemberRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var UnionMember $Model
     */
    public $Model = UnionMember::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * acquire total number of union member base on AO ID
     *
     * @param Array $ids - Affiliationoffice ID's
     * @return Array $rtn
     */
    public function acquireCountByAoId(array $ids)
    {
        $rtn = [];

        try {
            if (!empty($this->Model) && count($ids) != 0) {
                $rtn = $this->Model::selectRaw('count(unionMemberId) as cntId, affiliationOffice')
                    ->whereHas('unionLine', function ($query) {
                        $query->whereNotStop();

                        return $query;
                    })
                    ->whereNotDeleted()
                    ->whereIn('affiliationOffice', $ids)
                    ->groupBy('affiliationOffice')
                    ->pluck('cntId', 'affiliationOffice')
                    ->toArray();
            }
        } catch (\Exception $e) {
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        foreach ($ids as $id) {
            if (!isset($rtn[$id])) {
                $rtn[$id] = 0;
            }
        }

        return $rtn;
    }

    /**
     * acquire total number of union member base on UB ID
     *
     * @param Array $ids - Utilization Business ID's
     * @return Array $rtn
     */
    public function acquireCountByUbId(array $ids)
    {
        $rtn = [];
        try {
            if (!empty($this->Model) && count($ids) != 0) {
                $tableJoinUl = 'LEFT JOIN M_UnionLineId ul ON ul.unionMemberCode = M_UnionMemberId.unionMemberCode';
                $tableJoinUlWhere = 'AND ul.unionLineId IS NOT NULL AND ul.delFlg = 0 AND ul.stopFlg = 0';
                $data = $this->Model::selectRaw(
                    '(SELECT COUNT(*) FROM M_UnionMemberId ' . $tableJoinUl . ' WHERE utilizationBusiness1 != 0 AND M_UnionMemberId.delFlg = 0 ' . $tableJoinUlWhere . ') as "1",'
                    . ' (SELECT COUNT(*) FROM M_UnionMemberId ' . $tableJoinUl . ' WHERE utilizationBusiness2 != 0 AND M_UnionMemberId.delFlg = 0 ' . $tableJoinUlWhere . ') as "2",'
                    . ' (SELECT COUNT(*) FROM M_UnionMemberId ' . $tableJoinUl . ' WHERE utilizationBusiness3 != 0 AND M_UnionMemberId.delFlg = 0 ' . $tableJoinUlWhere . ') as "3",'
                    . ' (SELECT COUNT(*) FROM M_UnionMemberId ' . $tableJoinUl . ' WHERE utilizationBusiness4 != 0 AND M_UnionMemberId.delFlg = 0 ' . $tableJoinUlWhere . ') as "4",'
                    . ' (SELECT COUNT(*) FROM M_UnionMemberId ' . $tableJoinUl . ' WHERE utilizationBusiness5 != 0 AND M_UnionMemberId.delFlg = 0 ' . $tableJoinUlWhere . ') as "5",'
                    . ' (SELECT COUNT(*) FROM M_UnionMemberId ' . $tableJoinUl . ' WHERE utilizationBusiness6 != 0 AND M_UnionMemberId.delFlg = 0 ' . $tableJoinUlWhere . ') as "6",'
                    . ' (SELECT COUNT(*) FROM M_UnionMemberId ' . $tableJoinUl . ' WHERE utilizationBusiness7 != 0 AND M_UnionMemberId.delFlg = 0 ' . $tableJoinUlWhere . ') as "7",'
                    . ' (SELECT COUNT(*) FROM M_UnionMemberId ' . $tableJoinUl . ' WHERE utilizationBusiness8 != 0 AND M_UnionMemberId.delFlg = 0 ' . $tableJoinUlWhere . ') as "8",'
                    . ' (SELECT COUNT(*) FROM M_UnionMemberId ' . $tableJoinUl . ' WHERE utilizationBusiness9 != 0 AND M_UnionMemberId.delFlg = 0 ' . $tableJoinUlWhere . ') as "9"'
                )->first();

                if (is_null($data)) {
                    $data = [
                        '0' => 0,
                        '1' => 1,
                        '2' => 0,
                        '3' => 1,
                        '4' => 0,
                        '5' => 0,
                        '6' => 0,
                        '7' => 0,
                        '8' => 0
                    ];
                } else {
                    $data = $data->toArray();
                }

                unset($data['id']);
                unset($data['isEmpty']);
                unset($data['isNotEmpty']);

                foreach ($data as $ind => $value) {
                    $rtn[(string)($ind + 1)] = $value;
                }
            }
        } catch (\Exception $e) {
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        if (count($rtn) == 0) {
            $rtn = [
                '1' => 0,
                '2' => 0,
                '3' => 0,
                '4' => 0,
                '5' => 0,
                '6' => 0,
                '7' => 0,
                '8' => 0,
                '9' => 0,
            ];
        }

        return $rtn;
    }

    /**
     * acquire total number of union member base on Store ID
     *
     * @param Array $ids - Store ID's
     * @return Array $rtn
     */
    public function acquireCountByStoreId(array $ids)
    {
        $rtn = [];

        try {
            if (!empty($this->Model) && count($ids) != 0) {
                $rtn = $this->Model::selectRaw('count(T_FlyerStoreSelect.storeId) as cntId, T_FlyerStoreSelect.storeId as storeId')
                    ->leftJoin('T_FlyerStoreSelect', 'M_UnionMemberId.unionMemberCode', '=', 'T_FlyerStoreSelect.unionMemberCode')
                    ->where('T_FlyerStoreSelect.viewFlg', '=', FlyerStoreSelect::VIEWFLG_ISVIEW)
                    ->where('M_UnionMemberId.delFlg', $this->Model::STATUS_NOTDELETED)
                    ->whereIn('T_FlyerStoreSelect.storeId', $ids)
                    ->groupBy('T_FlyerStoreSelect.storeId')
                    ->pluck('cntId', 'storeId')
                    ->toArray();
            }
        } catch (\Exception $e) {
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        foreach ($ids as $id) {
            if (!isset($rtn[$id])) {
                $rtn[$id] = 0;
            }
        }

        return $rtn;
    }

    /**
     * acquire a list of records base on attributes given
     * call NTC (No Try Catch) method
     *
     * @param Array $attributes
     * @param Bool $returnCollection - either return by BuildQuery or Collection
     * @return BuildQuery|Collection
     */
    public function acquireByAttributes(array $attributes, bool $returnCollection = true)
    {
        return parent::acquireByAttributes($attributes, $returnCollection);
    }
}
