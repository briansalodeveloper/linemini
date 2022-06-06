<?php

namespace App\Services;

use App\Interfaces\UnionLineRepositoryInterface;
use App\Models\UnionLine;

class UnionLineService
{
    /*======================================================================
     * CONSTRUCTOR
     *======================================================================*/

    /**
     * @param UnionLineRepositoryInterface $repository
     * @return void
     */
    public function __construct(UnionLineRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /*======================================================================
     * PUBLIC METHODS
     *======================================================================*/

    /**
     * fetch UnionLine records
     *
     * @return Array $rtn
     */
    public function allByFilter()
    {
        $rtn = [
            'data' => []
        ];

        $filters = [
            'cardNumber' => request()->get('cardNumber'),
            'unionMemberCode' => request()->get('unionMemberCode')
        ];

        if (request()->has('cardNumber') || request()->has('unionMemberCode')) {
            $rtn['data'] = $this->repository->acquireAllByFilter($filters);
        } else {
            $rtn['data'] = $this->repository->acquireAll();
        }

        return $rtn;
    }

    /**
     * update unionline incident record
     *
     * @return Bool $rtn
     */
    public function updateIncident()
    {
        $ids = request()->get('checkbox');
        $incident = request()->get('incident');
        $delFlg = null;
        $stopFlg = null;
        $attributes = [
            'incidental' => $incident
        ];

        $incidentForDelete = [
            UnionLine::INCIDENTAL_PHONEREPLACE,
            UnionLine::INCIDENTAL_CARDREISSUE,
            UnionLine::INCIDENTAL_PHONEREPLACEDBUTHASISSUE,
        ];

        $incidentForStopFlag = [
            UnionLine::INCIDENTAL_PHONELOST,
        ];

        $incidentForCancelStopFlag = [
            UnionLine::INCIDENTAL_PHONERECOVERED,
            UnionLine::INCIDENTAL_PHONEREPLACED,
        ];

        if (in_array($incident, $incidentForDelete)) {
            $delFlg = UnionLine::STATUS_DELETED;
        } elseif (in_array($incident, $incidentForStopFlag)) {
            $stopFlg = UnionLine::STOPFLG_YES;
        } elseif (in_array($incident, $incidentForCancelStopFlag)) {
            $stopFlg = UnionLine::STOPFLG_NO;
        }

        if (!is_null($delFlg)) {
            $attributes['delFlg'] = $delFlg;
        }

        if (!is_null($stopFlg)) {
            $attributes['stopFlg'] = $stopFlg;
        }

        $rtn = $this->repository->adjustByAttributes([
            'unionLineId' => $ids
        ], $attributes);

        return $rtn;
    }
}
