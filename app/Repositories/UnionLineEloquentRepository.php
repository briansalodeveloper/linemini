<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Interfaces\UnionLineRepositoryInterface;
use App\Models\UnionLine;

class UnionLineEloquentRepository extends MainEloquentRepository implements UnionLineRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var UnionLine
     */
    public $Model = UnionLine::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * acquire all UnionLine records
     *
     * @param Int $paginatePerPage
     * @return LengthAwarePaginator
     */
    public function acquireAll(int $paginatePerPage = 10): LengthAwarePaginator
    {
        $rtn = $this->arrayToPagination([]);

        try {
            $rtn = $this->Model::sortDesc()->paginate($paginatePerPage);
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
     * acquire a UnionLine record
     *
     * @param Int $id
     * @return UnionLine
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a UnionLine record
     *
     * @param Array $attributes
     * @return Bool/UnionLine
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a UnionLine record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/UnionLine
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a UnionLine record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /*======================================================================
     * CUSTOM METHODS
     *======================================================================*/

    /**
     * acquire all UnionLine records via unionMemberCode or Utilization Business id's or Affiliation office id's or store id
     * call NTC (No Try Catch) method
     *
     * @param Array $umIds
     * @param Array $ubIds
     * @param Array $aoIds
     * @param Array $storeIds
     * @return Collection $rtn
     */
    public function acquireAllByUmAoUbOrStoreId(array $umIds, array $ubIds, array $aoIds, array $storeIds)
    {
        $rtn = $this->arrayToCollection([]);

        try {
            $rtn = $this->NTCacquireAllByUmAoUbOrStoreId($umIds, $ubIds, $aoIds, $storeIds);
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
     * acquire all UnionLine records via unionMemberCode or Utilization Business id's or Affiliation office id's or store id
     * NTC (No Try Catch) method
     *
     * @param Array $umIds
     * @param Array $ubIds
     * @param Array $aoIds
     * @param Array $storeIds
     * @return Collection $rtn
     */
    public function NTCacquireAllByUmAoUbOrStoreId(array $umIds, array $ubIds, array $aoIds, $storeIds)
    {
        $rtn = $this->arrayToCollection([]);

        if (!empty($this->Model)) {
            $list = $this->Model::select('LineTokenId')->whereNotDeleted()->whereNotStop()->where(function ($query) use ($umIds, $ubIds, $aoIds, $storeIds) {
                if (count($umIds) != 0 || count($ubIds) != 0 || count($aoIds) != 0) {
                    $query->whereHas('unionMember', function ($query) use ($umIds, $ubIds, $aoIds) {
                        if (count($umIds) != 0) {
                            $query->whereIn('unionMemberCode', $umIds);
                        } elseif (count($ubIds) != 0) {
                            $query->where(function ($query) use ($ubIds) {
                                foreach ($ubIds as $ind => $ubId) {
                                    if ($ind == 0) {
                                        $query->where('utilizationBusiness' . $ubId, 1);
                                    } else {
                                        $query->orWhere('utilizationBusiness' . $ubId, 1);
                                    }
                                }

                                return $query;
                            });
                        } elseif (count($aoIds) != 0) {
                            $query->whereIn('affiliationOffice', $aoIds);
                        }
        
                        return $query;
                    });
                } elseif (count($storeIds) != 0) {
                    $query->whereHas('flyerStoreSelect', function ($query) use ($storeIds) {
                        $query->isView();

                        if (count($storeIds) != 0) {
                            $query->whereIn('storeId', $storeIds);
                        }
        
                        return $query;
                    });
                }

                return $query;
            });

            if (count($storeIds) != 0) {
                $list = $list->groupBy('LineTokenId');
            }

            $rtn = $list->get();
        }

        return $rtn;
    }

    /**
     * acquire all UnionLine records via filters
     *
     * @param array $filters
     * @return Bool/UnionLine
     */
    public function acquireAllByFilter(array $filters, int $paginatePerPage = 10)
    {
        $rtn = $this->arrayToPagination([]);

        try {
            $rtn = new $this->Model();

            if (isset($filters['cardNumber'])) {
                $rtn = $rtn->where('cardNumber', $filters['cardNumber']);
            }

            if (isset($filters['unionMemberCode'])) {
                $rtn = $rtn->where('unionMemberCode', $filters['unionMemberCode']);
            }

            if (empty($filters['cardNumber']) && empty($filters['unionMemberCode'])) {
                $rtn = $rtn->where([
                    [ 'cardNumber', $filters['cardNumber'] ],
                    [ 'unionMemberCode', $filters['unionMemberCode'] ]
                ]);
            }

            $rtn = $rtn->sortDesc()->paginate($paginatePerPage);
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

    /**
     * adjust a list of records base on attributes given
     * NTC (No Try Catch) method
     *
     * @param Array $whereAttributes
     * @param Array $adjustAttributes
     * @return Bool
     */
    public function adjustByAttributes(array $whereAttributes, array $adjustAttributes)
    {
        return parent::adjustByAttributes($whereAttributes, $adjustAttributes);
    }
}
