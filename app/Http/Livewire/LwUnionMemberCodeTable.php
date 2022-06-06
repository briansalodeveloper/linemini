<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Traits\Livewire\PaginateTrait;

class LwUnionMemberCodeTable extends Component
{
    use PaginateTrait;

    /*======================================================================
     * PROPERTIES
     *======================================================================*/
    const MAX_DATAPERROW = 4;

    public $pgListPaginate;
    public $pgLoadData = false;
    protected $listeners = [
        'lwPagePrev' => 'lwPagePrev',
        'lwPageGo' => 'lwPageGo',
        'lwPageNext' => 'lwPageNext',
    ];

    public $dataId;
    public $interface;
    public $dTMethodName;

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
        $this->dataId = $params['dataId'];
        $this->interface = $params['interface'];
        $this->dTMethodName = $params['dTMethodName'];
    }

    /**
     * from LIVEWIRE render()
     */
    public function lwRender()
    {
        return view('livewire.lw-union-member-code-table');
    }

     /**
     * from LIVEWIRE lwPageListToCollection()
     */
    public function lwPgListToCollection($perPage = 10)
    {
        $page = $this->lwPageGet();
        $list = [];

        if ($this->pgLoadData) {
            $modelRepository = app()->make($this->interface);
            $model = $modelRepository->acquire($this->dataId);

            if ($model->isNotEmpty) {
                $list = $model->{$this->dTMethodName}()
                    ->whereHas('unionLine')
                    ->whereHas('unionMember')
                    ->pluck('kumicd')->toArray();

                $list = array_chunk($list, self::MAX_DATAPERROW);
            }
        }

        $list = $this->paginate($list, $perPage, $page);
        $data = [
            'list' => $list
        ];

        return $data;
    }
}
