<?php

namespace App\Traits\Livewire;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Session;

trait PaginateTrait
{
    /*======================================================================
     *======================================================================
     * MUST DECLARE PROPERTIES
     *======================================================================

    public $pgListPaginate;
    public $pgTrash;
    public $pgSortingList = [
        0 => 'id'
    ];
    public $pgListSearchKey;
    public $pgListSearchingKey;

    *======================================================================
    *======================================================================*/

    /*======================================================================
     *======================================================================
     * OPTIONAL DECLARE PROPERTIES
     *======================================================================

     public $pgLoadData = false;

    *======================================================================
    *======================================================================*/

    private $strKey = '_paginate_livewire';

    /*======================================================================
     * HOOKS OVERRIDE
     *======================================================================*/

    /**
     * LIVEWIRE mount()
     *
     * @param Null|String|Array|Integer|Bool $params
     */
    public function mount($params = null)
    {
        $_s = [
            'success' => session()->get('success'),
            'failed' => session()->get('failed')
        ];
        $this->__session = $_s;
        $this->lwPageForget($this->lwPageGetKey());
        $this->lwTableForget($this->lwPageGetKey());

        if (method_exists($this, 'lwMount')) {
            if (is_array($params) ? count($params) != 0 : !is_null($params)) {
                return $this->lwMount($params);
            } else {
                return $this->lwMount();
            }
        }
    }

    /**
     * LIVEWIRE render()
     */
    public function render()
    {
        if (isset($this->__session)) {
            foreach ($this->__session as $key => $val) {
                if (!empty($val)) {
                    Session::put($key, $val);
                    Session::save();
                    break;
                }
            }
        }

        if (method_exists($this, 'lwRender')) {
            $lwRender = $this->lwRender();
            $this->lwPageListToCollection();

            return $lwRender;
        }
    }

    /**
     * LIVEWIRE hydrate()
     */
    public function hydrate()
    {
        $this->_pg_isInit = false;

        if (method_exists($this, '_hydrate')) {
            return $this->_hydrate();
        }
    }

    /**
     * LIVEWIRE dehydrate()
     */
    public function dehydrate()
    {
        if (method_exists($this, '_dehydrate')) {
            return $this->_dehydrate();
        }
    }

    /*======================================================================
     * CUSTOM PRIVATE METHODS
     *======================================================================*/

    /**
     * get a key to be use in the session for livewire pagination
     *
     * @param Null|String $key
     * @return String $rtn
     */
    private function lwPageGetKey($key = null)
    {
        $rtn = 'default';
        $tempKey = $key;

        if (is_null($key)) {
            $tempKey = $rtn;
        } else {
            $rtn = $tempKey;
        }

        $rtn = '_' . $this->id;

        return $rtn;
    }

    /**
     * get session for livewire pagination
     *
     * @param Null|String $key
     * @return Array $rtn
     */
    private function lwPageGetSession($name = null)
    {
        $strKey = $this->strKey;

        if (Session::has($strKey)) {
            $temp = Session::get($strKey);

            if (!is_array($temp)) {
                Session::forget($strKey);
            }
        }

        if (!Session::has($strKey)) {
            $data = [];
            Session::put($strKey, $data);
            Session::save();
        }

        $rtn = Session::get($strKey);

        if (!is_null($name)) {
            if (!empty($rtn[$name])) {
                $data = $rtn[$name];

                if (!is_array($data)) {
                    $this->lwPageSetSession($name, []);
                }
            } else {
                $this->lwPageSetSession($name, []);
            }

            $rtn = Session::get($strKey);
            $rtn = $rtn[$name];
        }

        return $rtn;
    }

    /**
     * set session for livewire pagination
     *
     * @param String $name
     * @param Null|String|Integer|Bool $value
     */
    private function lwPageSetSession($name, $value)
    {
        $strKey = $this->strKey;
        $data = $this->lwPageGetSession();
        $data[$name] = $value;

        Session::put($strKey, $data);
        Session::save();
    }

    /**
     * reload the livewire component
     */
    private function lwPageOpenTrash()
    {
        if (property_exists($this, 'pgTrash')) {
            if ($this->pgTrash !== 1) {
                $this->pgTrash = 1;
            } else {
                $this->pgTrash = 0;
            }
        }
    }

    /*======================================================================
     * PAGINATION
     *======================================================================*/

    /**
     * turn Collection or Array into pagination
     *
     * @param Array|Collection $items
     * @param Int $perPage
     * @param Null|Int $page
     * @param Array $options
     * @return LengthAwarePaginator $list
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        if (is_null($page)) {
            $page = $this->lwPageGet($this->lwPageGetKey());
        }

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $newitems = $items instanceof Collection ? $items : Collection::make($items);
        $list = new LengthAwarePaginator($newitems->forPage($page, $perPage), $newitems->count(), $perPage, $page, $options);

        return $list;
    }

    /**
     * update pagination session to the next page
     *
     * @param Null|String $key
     */
    public function lwPageNext($key = null)
    {
        $key = $this->lwPageGetKey($key);
        $page = $this->lwPageGet($key);
        $page++;
        $this->lwPageSet($key, $page);
        $this->lwPageListToCollection();
    }

    /**
     * update pagination session to the corresponding value given
     *
     * @param Int $page
     * @param Null|String $key
     */
    public function lwPageGo($page, $key = null)
    {
        $key = $this->lwPageGetKey($key);
        $this->lwPageGet($key);
        $this->lwPageSet($key, $page);
        $this->lwPageListToCollection();
    }

    /**
     * update pagination session to the previous page
     *
     * @param Null|String $key
     */
    public function lwPagePrev($key = null)
    {
        $key = $this->lwPageGetKey($key);
        $page = $this->lwPageGet($key);
        $page--;
        $this->lwPageSet($key, $page);
        $this->lwPageListToCollection();
    }

    /**
     * get the current pagination # session
     *
     * @param Null|String $key
     */
    public function lwPageGet($key = null)
    {
        $key = $this->lwPageGetKey($key);
        $strKey = $this->strKey;
        $data = $this->lwPageGetPageList();

        if (empty($data[$key])) {
            $this->lwPagePaginationValueSave($key, 1);
        }

        return $this->lwPagePaginationValue($key);
    }

    /**
     * set the current pagination # session
     *
     * @param Null|String $key
     * @param Int $page
     */
    public function lwPageSet($key, $page)
    {
        if (is_null($key)) {
            $key = $this->lwPageGetKey($key);
        }

        $this->lwPagePaginationValueSave($key, $page);

        return $this->lwPagePaginationValue($key);
    }

    /**
     * load the pagination data by updating the pgLoadData property to true
     */
    public function lwPgLoadData()
    {
        if ($this->lwHasLoadDataFlag()) {
            $this->pgLoadData = true;
        }
    }

    /**
     * check if pgLoadData property exists
     *
     * @return Bool
     */
    public function lwHasLoadDataFlag()
    {
        return property_exists($this, 'pgLoadData');
    }

    /*======================================================================
     * PAGINATION - private methods
     *======================================================================*/

    /**
     * get the pagination session list
     *
     * @return Array $data
     */
    private function lwPageGetPageList()
    {
        $data = $this->lwPageGetSession('pagination');
        return $data;
    }

    /**
     * update/add pagination session value
     *
     * @param String $key
     * @param Null|String|Integer|Bool $value
     * @param Array $data
     */
    private function lwPagePaginationValueSave($key, $value)
    {
        $data = $this->lwPageGetSession('pagination');
        $data[$key] = $value;
        $this->lwPageSetSession('pagination', $data);
    }

    /**
     * return the pagination session list while checking if needed to put default value
     *
     * @param String $key
     * @param Array $data
     */
    private function lwPagePaginationValue($key)
    {
        $data = $this->lwPageGetSession('pagination');

        if (!isset($data[$key])) {
            $this->lwPagePaginationValueSave($key, 1);
            $data = $this->lwPageGetSession('pagination');
        }

        return $data[$key];
    }

    /**
     * forget pagination session
     *
     * @param String $key
     * @param Array $data
     */
    private function lwPageForget($key = null)
    {
        $data = $this->lwPageGetPageList();
        unset($data[$key]);
        $this->lwPageSetSession('pagination', $data);
        $data = $this->lwPageGetPageList();

        return $data;
    }

    /**
     * initialize the list of records for the table in the pgListPaginate property
     */
    public function lwPageListToCollection()
    {
        if (method_exists($this, 'lwPgListToCollection')) {
            if (isset($this->{'_pg_isInit'}) ? !$this->{'_pg_isInit'} : true) {
                $this->{'_pg_isInit'} = true;
                $data = $this->lwPgListToCollection();
                $this->pgListPaginate = $data;
            }
        }
    }

    /*======================================================================
     * TABLE SORTING
     *======================================================================*/

    /**
     * check the specified column for preparation of sorting
     *
     * @param Int $columnIndex
     */
    public function lwTableSort($columnIndex)
    {
        $sort_value = null;
        $data = $this->lwTableGetSortingList();

        if (!isset($data[$columnIndex])) {
            $sort_value = 0;
        }

        $sort_value = isset($data[$columnIndex]) ? $data[$columnIndex] : $sort_value;

        if ($sort_value === 0 && isset($data[$columnIndex])) {
            $sort_value = 1;
        } else {
            $sort_value = 0;
        }

        $temp_data = $data;

        foreach ($temp_data as $c_index => $d) {
            unset($data[$c_index]);
        }

        $data[$columnIndex] = $sort_value;
        $this->lwPageSetSession('table_sorting', $data);
        $this->lwPageOpenTrash();
    }

    /**
     * get the column sorting order
     *
     * @param Int $columnIndex
     * @return String
     */
    public function lwTableSorting($columnIndex)
    {
        if ($this->lwTableIsAsc($columnIndex)) {
            return 'asc';
        } elseif ($this->lwTableIsDesc($columnIndex)) {
            return 'desc';
        } else {
            return '';
        }
    }

    /**
     * check if column is sorted by ascending order
     *
     * @param Int $columnIndex
     * @return Bool|String false/"asc"
     */
    public function lwTableIsAsc($columnIndex)
    {
        $sorting = $this->lwTableSortingValue($columnIndex);

        if ($sorting === 0) {
            return 'asc';
        } else {
            return false;
        }
    }

    /**
     * check if column is sorted by descending order
     *
     * @param Int $columnIndex
     * @return Bool|String false/"desc"
     */
    public function lwTableIsDesc($columnIndex)
    {
        $sorting = $this->lwTableSortingValue($columnIndex);

        if ($sorting === 1) {
            return 'desc';
        } else {
            return false;
        }
    }

    /*======================================================================
     * TABLE SORTING - private methods
     *======================================================================*/

    /**
     * get sorting list session
     *
     * @return Array $data
     */
    public function lwTableGetSortingList()
    {
        $data = $this->lwPageGetSession('table_sorting');
        return $data;
    }

    /**
     * save table sorting value
     *
     * @param String $key
     * @param String $value
     */
    private function lwTableSortingValueSave($key, $value)
    {
        $data = $this->lwPageGetSession('table_sorting');
        $data[$key] = $value;
        $this->lwPageSetSession('table_sorting', $data);
    }

    /**
     * get table sorting value and add default value
     *
     * @param String $key
     * @param Array $data
     */
    private function lwTableSortingValue($key)
    {
        $data = $this->lwPageGetSession('table_sorting');

        if (!isset($data[$key])) {
            return false;
        }

        return $data[$key];
    }

    /**
     * reset table sorting session
     *
     * @param String $key
     * @param Array $data
     */
    private function lwTableForget($key = null)
    {
        $this->lwPageSetSession('table_sorting', []);
        $data = $this->lwTableGetSortingList();

        return $data;
    }

    /*======================================================================
     * SEARCH
     *======================================================================*/

    /**
     * update searching string value
     */
    public function lwPgListSearch()
    {
        $this->pgListSearchingKey = $this->pgListSearchKey;
    }
}
