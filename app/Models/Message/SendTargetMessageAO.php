<?php

namespace App\Models\Message;

use App\Models\MainModel;

class SendTargetMessageAO extends MainModel
{
    /**
     * @var $table
     */
    protected $table = 'T_SendTargetMessageAO';

    /**
     * @var $primaryKey
     */
    protected $primaryKey = 'sendTargetMessageAOId';

    /**
     * @var $fillable
     */
    protected $fillable = [
        'messageId',
        'affiliationOfficeId',
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
