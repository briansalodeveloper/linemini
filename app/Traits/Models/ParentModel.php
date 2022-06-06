<?php

namespace App\Traits\Models;

use Carbon\Carbon;

trait ParentModel
{
    /*======================================================================
     * CUSTOM METHODS
     *======================================================================*/

    /**
     * get valid attribute if exist, if not then return default value
     *
     * @return [ModelProperty] $rtn
     */
    public function getAttr(string $attribute, $default = '')
    {
        $rtn = $default;

        if ($this->isNotEmpty) {
            if (isset($this[$attribute])) {
                $rtn = $this->{$attribute};
            }
        }

        return $rtn;
    }

    /**
     * get valid relationship attribute if exist, if not then return default value
     *
     * @return [ModelProperty] $rtn
     */
    public function getRelAttr(string $relationshipMethodString, string $attribute, $default = '')
    {
        $rtn = $default;

        if (!empty($this->{$relationshipMethodString})) {
            if (isset($this->{$relationshipMethodString}[$attribute])) {
                $rtn = $this->{$relationshipMethodString}->{$attribute};
            }
        }

        return $rtn;
    }

    /**
     * carbon format a property date
     *
     * @param String $property
     * @param String $format
     * @return String $rtn
     */
    public function formatDate(string $property, string $format = 'Y年m月d日'): string
    {
        $rtn = '';

        if (!empty($this->{$property})) {
            $dt = Carbon::parse($this->{$property});

            if (!empty($dt)) {
                $rtn = $dt->format($format);
            }
        }

        return $rtn;
    }

    /*======================================================================
     * CUSTOM STATIC METHODS
     *======================================================================*/

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($data) {
            if (empty($data->updateDate)) {
                $data->updateDate = Carbon::now();
            }

            if (empty($data->updateUser)) {
                $name = '';

                if (auth()->check()) {
                    $name = auth()->user()->name;
                } else {
                    $name = 'SYSTEM';
                }

                $data->updateUser = _trim($name, 8, '...');
            }

            if (empty($data->delFlg)) {
                $data->delFlg = static::STATUS_NOTDELETED;
            }

            return $data;
        });

        static::updating(function ($data) {
            $name = '';

            if (auth()->check()) {
                $name = auth()->user()->name;
            } else {
                $name = 'SYSTEM';
            }

            $data->updateDate = Carbon::now();
            $data->updateUser = _trim($name, 8, '...');

            return $data;
        });
    }

    /**
     * inserting bulk records
     *
     * @param Array $attributesArray
     * @return Bool $rtn
     */
    public static function inserting(array $attributesArray)
    {
        $rtn = false;
        $insertAttributesArray = [];

        $name = '';

        if (auth()->check()) {
            $name = auth()->user()->name;
        } else {
            $name = 'SYSTEM';
        }

        $updateUser = _trim($name, 8, '...');
        $updateDate = Carbon::now();

        foreach ($attributesArray as $arr) {
            if (empty($arr['updateDate'])) {
                $arr['updateDate'] = $updateDate;
            }

            if (empty($arr['updateUser'])) {
                $arr['updateUser'] = $updateUser;
            }

            if (empty($arr['delFlg'])) {
                $arr['delFlg'] = static::STATUS_NOTDELETED;
            }

            $insertAttributesArray[] = $arr;
        }

        if (!empty($insertAttributesArray)) {
            $rtn = self::insert($insertAttributesArray);
        }

        return $rtn;
    }

    /**
     * empty table column values
     */
    public static function empty()
    {
        return new static();
    }

    /*======================================================================
     * ACCESSORS
     *======================================================================*/

    /**
     * id
     *
     * @return Int
     */
    public function getIdAttribute($value): int
    {
        $rtn = 0;

        if ($this->primaryKey == 'id') {
            if (!is_null($value)) {
                $rtn = $value;
            }
        } else {
            if (isset($this[$this->primaryKey])) {
                $rtn = $this[$this->primaryKey];
            }
        }

        return $rtn;
    }

    /**
     * isEmpty
     *
     * @return Bool
     */
    public function getIsEmptyAttribute()
    {
        return empty($this->id);
    }

    /**
     * isNotEmpty
     *
     * @return Bool
     */
    public function getIsNotEmptyAttribute()
    {
        return !$this->isEmpty;
    }

    /*======================================================================
     * SCOPES
     *======================================================================*/

    /**
     * whereDeleted
     */
    public function scopeWhereDeleted($query)
    {
        $query->where('delFlg', self::STATUS_DELETED);
        return $query;
    }

    /**
     * whereNotDeleted
     */
    public function scopeWhereNotDeleted($query)
    {
        $query->where('delFlg', self::STATUS_NOTDELETED);
        return $query;
    }

    /**
     * sortAsc
     */
    public function scopeSortAsc($query)
    {
        $query->orderBy('updateDate', 'asc');
        return $query;
    }

    /**
     * sortDesc
     */
    public function scopeSortDesc($query)
    {
        $query->orderBy('updateDate', 'desc');
        return $query;
    }
}
