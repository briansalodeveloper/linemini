<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisplayTargetStamp extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_DisplayTargetStamp';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'displayTargetStampId';
    
    /**
     * @var $fillable
     */
    protected $fillable = [
        'stampPlanId',
        'kumicd',
        'updateDate',
        'updateUser',
        'delFlg',
    ];

    /**
     * @var $appends
     */
    protected $appends = [
        'id',
        'isEmpty',
        'isNotEmpty',
    ];

    /*======================================================================
     * RELATIONSHIPS
     *======================================================================*/

    /**
     * unionMember()
     */
    public function unionMember()
    {
        return $this->belongsTo(UnionMember::class, 'kumicd', 'unionMemberCode')->where('delFlg', UnionMember::STATUS_NOTDELETED);
    }

    /**
     * UnionLine()
     */
    public function UnionLine()
    {
        return $this->belongsTo(UnionLine::class, 'kumicd', 'unionMemberCode')->where('delFlg', UnionLine::STATUS_NOTDELETED);
    }
}
