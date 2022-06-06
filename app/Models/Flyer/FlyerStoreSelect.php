<?php

namespace App\Models\Flyer;

use App\Models\UnionLine;
use App\Models\Store;
use App\Models\MainModel;

class FlyerStoreSelect extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_FlyerStoreSelect';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'unionMemberCode';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'cardNumber',
        'storeId',
        'viewFlg',
    ];

    /**
     * @var $appends
     */
    protected $appends = [
        'id',
        'label',
        'isEmpty',
        'isNotEmpty',
    ];

    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    const VIEWFLG_ISNOTVIEW = 0;
    const VIEWFLG_ISVIEW = 1;

    /*======================================================================
     * ACCESSORS
     *======================================================================*/

    public function getLabelAttribute()
    {
        $rtn = '';
        $store = $this->store;

        if (!empty($store)) {
            $rtn = $store->storeName;
        }

        return $rtn;
    }

    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

    /**
     * This method return multiple FlyerStoreSelect relation to unionLine.
     *
     * @return collection
     */
    public function unionLine()
    {
        return $this->hasOne(UnionLine::class, 'unionMemberCode', 'unionMemberCode')
            ->whereRaw('M_UnionLineId.cardNumber = T_FlyerStoreSelect.cardNumber')
            ->where('delFlg', UnionLine::STATUS_NOTDELETED);
    }

    /**
     * This method return multiple FlyerStoreSelect relation to store.
     *
     * @return collection
     */
    public function store()
    {
        return $this->hasOne(Store::class, 'storeId', 'storeId')->where('delFlg', Store::STATUS_NOTDELETED);
    }

    /*======================================================================
     * SCOPES
     *======================================================================*/

    /**
     * isView()
     */
    public function scopeIsView($query)
    {
        $query->where('viewFlg', self::VIEWFLG_ISVIEW);

        return $query;
    }
}
