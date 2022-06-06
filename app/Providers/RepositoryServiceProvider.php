<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $models = array(
            'Admin',
            'ContentPlan',
            'DisplayTargetContent' => 'Content',
            'DisplayTargetContentAO'=> 'Content',
            'DisplayTargetContentUB'=> 'Content',
            'CouponPlan',
            'CouponPlanStore' => 'Coupon',
            'DisplayTargetCoupon' => 'Coupon',
            'DisplayTargetCouponAO' => 'Coupon',
            'DisplayTargetCouponUB' => 'Coupon',
            'Store',
            'Message',
            'SendTargetMessage' => 'Message',
            'SendTargetMessageAO' => 'Message',
            'SendTargetMessageUB' => 'Message',
            'SendTargetMessageStore' => 'Message',
            'FlyerPlan',
            'FlyerStoreSelect' => 'Flyer',
            'DisplayTargetFlyer' => 'Flyer',
            'DisplayTargetFlyerUB' => 'Flyer',
            'DisplayTargetFlyerAO' => 'Flyer',
            'FlyerDisplayStore' => 'Flyer',
            'UnionLine',
            'UnionMember',
            'StampPlan',
            'StampPlanStore',
            'StampPlanTargetClass',
            'DisplayTargetStamp',
            'DisplayTargetStampAO',
            'DisplayTargetStampUB',
            'StampPlanStore',
            'StampPlanTargetClass',
            'Product',
            'CouponPlanProduct' => 'Coupon',
            'CouponPlanTargetClass' => 'Coupon',
            'StampPlanProduct'
        );

        foreach ($models as $index => $model) {
            if (!is_numeric($index)) {
                $this->app->bind("App\Interfaces\\{$model}\\{$index}RepositoryInterface", "App\Repositories\\{$model}\\{$index}EloquentRepository");
            } else {
                $this->app->bind("App\Interfaces\\{$model}RepositoryInterface", "App\Repositories\\{$model}EloquentRepository");
            }
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
