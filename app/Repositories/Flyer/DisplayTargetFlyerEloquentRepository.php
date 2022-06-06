<?php

namespace App\Repositories\Flyer;

use App\Interfaces\Flyer\DisplayTargetFlyerRepositoryInterface;
use App\Models\Flyer\DisplayTargetFlyer;
use App\Repositories\MainEloquentRepository;

class DisplayTargetFlyerEloquentRepository extends MainEloquentRepository implements DisplayTargetFlyerRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetFlyer $Model
     */
    public $Model = DisplayTargetFlyer::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * add a DisplayTargetFlyer record
     *
     * @param Array $attributes
     * @return Bool/DisplayTargetFlyer
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a DisplayTargetFlyer  record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/DisplayTargetFlyer
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a DisplayTargetFlyer record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to DisplayTargetFlyer
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
     * annul a list of DisplayTargetFlyer record base on attributes given
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
     * acquire all kumicd from DisplayTargetFlyer records via flyerPlanId
     * call NTC (No Try Catch) method
     *
     * @param null|int $flyerPlanId
     * @return array
     */
    public function acquireAllKumicdByFlyer($flyerPlanId, int $paginatePerPage = 10)
    {
        $rtn = [];

        try {
            if (!empty($flyerPlanId)) {
                $rtn = DisplayTargetFlyer::where('flyerPlanId', $flyerPlanId)
                    ->whereNotDeleted()
                    ->sortDesc()
                    ->pluck('kumicd')
                    ->toArray();
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
}
