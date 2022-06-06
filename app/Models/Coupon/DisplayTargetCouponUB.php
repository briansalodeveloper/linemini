<?php

namespace App\Models\Coupon;

use App\Models\MainModel;

class DisplayTargetCouponUB extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_DisplayTargetCuponUB';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'displayTargetCuponUBId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'cuponPlanId',
        'utilizationBusinessId',
        'updateDate',
        'updateUser',
        'delFlg'
    ];

    /**
     * @var $appends
     */
    protected $appends = [
        'id',
        'isEmpty',
        'isNotEmpty'
    ];

    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    /** ORDER IS OBSERVED */
    const UTILIZATION_BUSINESS_LIST = [
        '1' => '宅配',
        '2' => '店舗',
        '3' => '夕食宅配',
        '4' => '共済',
        '5' => '保険',
        '6' => '福祉',
        '7' => 'その他１',
        '8' => 'その他２',
        '9' => 'その他３'
    ];
}
