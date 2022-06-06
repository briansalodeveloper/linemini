<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\DisplayTargetStampRepositoryInterface;
use App\Models\DisplayTargetStamp;

class DisplayTargetStampEloquentRepository extends MainEloquentRepository implements DisplayTargetStampRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetStamp $Model
     */
    public $Model = DisplayTargetStamp::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * add a DisplayTargetStamp record
     *
     * @param Array $attributes
     * @return Bool/DisplayTargetStamp
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a DisplayTargetStamp record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/DisplayTargetStamp
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a DisplayTargetStamp record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to DisplayTargetStamp
     * call NTC (No Try Catch) method
     *
     * @param Array $attributesArray
     * @return Bool/Model
     */
    public function addBulk(array $attributesArray)
    {
        return parent::addBulk($attributesArray);
    }

    /**
     * annul a list of DisplayTargetStamp record base on attributes given
     * call NTC (No Try Catch) method
     *
     * @param Array $attributes
     * @return Bool
     */
    public function annulByAttributes(array $attributes)
    {
        return parent::annulByAttributes($attributes);
    }

    /**
     * acquire all kumicd from DisplayTargetStamp records via stampPlanId
     * call NTC (No Try Catch) method
     *
     * @param null|int $stampPlanId
     * @return array
     */
    public function acquireAllKumicdByStampPlan($stampPlanId, int $paginatePerPage = 10)
    {
        $rtn = [];

        try {
            if (!empty($stampPlanId)) {
                $rtn = DisplayTargetStamp::where('StampPlanId', $stampPlanId)
                    ->sortDesc()
                    ->whereNotDeleted()
                    ->pluck('kumicd')
                    ->toArray();
            }
        } catch (\Exception $e) {
            \Log::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            \Log::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        return $rtn;
    }
}
