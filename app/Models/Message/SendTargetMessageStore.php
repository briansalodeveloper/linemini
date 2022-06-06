<?php

namespace App\Models\Message;

use App\Models\MainModel;

class SendTargetMessageStore extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_SendTargetMessageStore';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'sendTargetMessageStoreId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'messageId',
        'storeId',
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
