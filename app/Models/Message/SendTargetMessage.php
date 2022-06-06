<?php

namespace App\Models\Message;

use App\Models\MainModel;
use App\Models\UnionLine;
use App\Models\UnionMember;

class SendTargetMessage extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_SendTargetMessage';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'sendTargetMessageId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'messageId',
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
