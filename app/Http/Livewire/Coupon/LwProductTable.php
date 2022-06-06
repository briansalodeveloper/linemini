<?php

namespace App\Http\Livewire\Coupon;

use Livewire\Component;
use App\Interfaces\CouponPlanRepositoryInterface;
use App\Traits\Livewire\PaginateTrait;

class LwProductTable extends Component
{
    use PaginateTrait;

    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    public $pgListPaginate;
    public $pgLoadData = false;
    protected $listeners = [
        'lwPagePrev' => 'lwPagePrev',
        'lwPageGo' => 'lwPageGo',
        'lwPageNext' => 'lwPageNext',
    ];

    public $couponId;

    /*======================================================================
     * HOOKS
     *======================================================================*/

    /**
     * from LIVEWIRE mount()
     *
     * @param Null|String|Array|Integer|Bool $params
     */
    public function lwMount($params)
    {
        $this->pgLoadData = false;
        $this->couponId = $params;
    }

    /**
     * from LIVEWIRE render()
     */
    public function lwRender()
    {
        return view('livewire.coupon.lw-product-table');
    }

    /**
     * from LIVEWIRE lwPageListToCollection()
     */
    public function lwPgListToCollection($perPage = 10)
    {
        $page = $this->lwPageGet();
        $list = [];

        if ($this->pgLoadData) {
            $couponPlanRepository = app()->make(CouponPlanRepositoryInterface::class);
            $couponPlan = $couponPlanRepository->acquire($this->couponId);

            if ($couponPlan->isNotEmpty) {
                $list = $couponPlan->couponProducts()
                    ->whereHas('product')
                    ->paginate($perPage, ['*'], 'page', $page);
            } else {
                $list = $this->paginate($list, $perPage, $page);
            }
        } else {
            $list = $this->paginate($list, $perPage, $page);
        }

        $data = [
            'list' => $list
        ];

        return $data;
    }
}
