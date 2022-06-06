<?php

namespace App\Models\Message;

use App\Models\MainModel;

class SendTargetMessageUB extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_SendTargetMessageUB';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'sendTargetMessageUBId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'messageId',
        'utilizationBusinessId',
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
}
