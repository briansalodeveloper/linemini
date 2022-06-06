<?php

namespace App\Models\Content;

use App\Models\MainModel;
use App\Models\UnionMember;
use App\Models\UnionLine;

class DisplayTargetContent extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_DisplayTargetContent';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'displayTargetContentId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'contentPlanId',
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
