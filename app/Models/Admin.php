<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;

class Admin extends MainModelAuthenticatable
{
    protected $table = 'M_Admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'role',
        'password',
        'thumbnail',
        'updateDate',
        'updateUser',
        'delFlg',
    ];

    /**
     * @var $appends
     */
    protected $appends = [
        'roleStr',
        'isRoleAdmin',
        'isRoleChecker',
        'isRoleSystem',
        'isRoleManagement',
        'isEmpty',
        'isNotEmpty',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /*======================================================================
     * CONSTANTS
     *======================================================================*/

    const ROLE_ADMIN = 0;
    const ROLE_CHECKER = 1;
    const ROLE_SYSTEM = 2;
    const ROLE_MANAGEMENT = 3;

    const CAN_MANAGE_DASHBOARD = 'manageDashboard';
    const CAN_MANAGE_CONTENTS = 'manageContents';
    const CAN_MANAGE_FLYER = 'manageFlyer';
    const CAN_MANAGE_COUPON = 'manageCoupon';
    const CAN_MANAGE_STAMP = 'manageStamp';
    const CAN_MANAGE_MESSAGE = 'manageMessage';
    const CAN_MANAGE_USER = 'manageUser';
    const CAN_MANAGE_BATCHLOG = 'manageBatchLog';
    const CAN_MANAGE_ADMIN = 'manageAdmin';
    const CAN_MANAGE_QUESTIONNAIRE = 'manageQuestionnaire';
    const CAN = [
        'manageDashboard',
        'manageContents',
        'manageFlyer',
        'manageCoupon',
        'manageStamp',
        'manageMessage',
        'manageUser',
        'manageBatchLog',
        'manageAdmin',
        'manageQuestionnaire',
    ];

    /**
     * contentTypeNewsStr
     *
     * @return String
     */
    
    /*======================================================================
     * ACCESSORS
     *======================================================================*/

    /**
     * roleStr
     *
     * @return String
     */
    public function getRoleStrAttribute(): string
    {
        $rtn = '';

        if ($this->role == self::ROLE_ADMIN) {
            $rtn = __('words.Administrator');
        } elseif ($this->role == self::ROLE_CHECKER) {
            $rtn = __('words.Checker');
        } elseif ($this->role == self::ROLE_SYSTEM) {
            $rtn = __('words.InformationSystemCharge');
        } elseif ($this->role == self::ROLE_MANAGEMENT) {
            $rtn = __('words.Headquarters');
        }

        return $rtn;
    }

    /**
     * isRoleAdmin
     *
     * @return String
     */
    public function getIsRoleAdminAttribute(): string
    {
        return $this->role == self::ROLE_ADMIN;
    }

    /**
     * isRoleChecker
     *
     * @return String
     */
    public function getIsRoleCheckerAttribute(): string
    {
        return $this->role == self::ROLE_CHECKER;
    }

    /**
     * isRoleSystem
     *
     * @return String
     */
    public function getIsRoleSystemAttribute(): string
    {
        return $this->role == self::ROLE_SYSTEM;
    }

    /**
     * isRoleManagement
     *
     * @return String
     */
    public function getIsRoleManagementAttribute(): string
    {
        return $this->role == self::ROLE_MANAGEMENT;
    }

    /*======================================================================
     * CUSTOM METHODS
     *======================================================================*/

    /**
     * check if user has permission to specific task base on its role
     *
     * @param String|Admin::CAN_ $permission
     * @param Bool $rtn
     */
    public function checkPermission(string $permission)
    {
        $rtn = false;

        switch ($permission) {
            case self::CAN_MANAGE_DASHBOARD:
                $rtn = true;
                break;
            case self::CAN_MANAGE_CONTENTS:
                $rtn = $this->isRoleAdmin || $this->isRoleManagement;
                break;
            case self::CAN_MANAGE_FLYER:
                $rtn = $this->isRoleAdmin || $this->isRoleManagement;
                break;
            case self::CAN_MANAGE_COUPON:
                $rtn = $this->isRoleAdmin || $this->isRoleManagement;
                break;
            case self::CAN_MANAGE_STAMP:
                $rtn = true;
                break;
            case self::CAN_MANAGE_MESSAGE:
                $rtn = $this->isRoleAdmin || $this->isRoleManagement;
                break;
            case self::CAN_MANAGE_USER:
                $rtn = $this->isRoleAdmin || $this->isRoleChecker;
                break;
            case self::CAN_MANAGE_BATCHLOG:
                $rtn = $this->isRoleAdmin || $this->isRoleSystem;
                break;
            case self::CAN_MANAGE_ADMIN:
                $rtn = $this->isRoleAdmin;
                break;
            case self::CAN_MANAGE_QUESTIONNAIRE:
                $rtn = true;
                break;
        }

        return $rtn;
    }
}
