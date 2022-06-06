<?php

namespace App\Repositories\Content;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Content\DisplayTargetContentRepositoryInterface;
use App\Models\Content\DisplayTargetContent;
use App\Repositories\MainEloquentRepository;

class DisplayTargetContentEloquentRepository extends MainEloquentRepository implements DisplayTargetContentRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetContent $Model
     */
    public $Model = DisplayTargetContent::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * acquire all ContentPlan records
     *
     * @return Collection
     */
    public function acquireAll(): Collection
    {
        return parent::acquireAll($id);
    }

    /**
     * acquire a ContentPlan record
     *
     * @param Int $id
     * @return DisplayTargetContent
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a DisplayTargetContent record
     *
     * @param Array $attributes
     * @return Bool/DisplayTargetContent
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a DisplayTargetContent record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/DisplayTargetContent
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a DisplayTargetContent record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * add bulk records to DisplayTargetContent
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
     * annul a list of DisplayTargetContent record base on attributes given
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
     * acquire all kumicd from DisplayTargetContent records via contentPlanId
     * call NTC (No Try Catch) method
     *
     * @param null|int $contentPlanId
     * @return array
     */
    public function acquireAllKumicdByContentPlan($contentPlanId, int $paginatePerPage = 10)
    {
        $rtn = [];

        try {
            if (!empty($contentPlanId)) {
                $rtn = DisplayTargetContent::where('contentPlanId', $contentPlanId)
                    ->sortDesc()
                    ->whereNotDeleted()
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
