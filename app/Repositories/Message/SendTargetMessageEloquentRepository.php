<?php

namespace App\Repositories\Message;

use Illuminate\Database\Eloquent\Collection;
use App\Interfaces\Message\SendTargetMessageRepositoryInterface;
use App\Models\Message\SendTargetMessage;
use App\Repositories\MainEloquentRepository;

class SendTargetMessageEloquentRepository extends MainEloquentRepository implements SendTargetMessageRepositoryInterface
{
    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var SendTargetMessage $Model
     */
    public $Model = SendTargetMessage::class;

    /*======================================================================
     * METHODS
     *======================================================================*/

    /**
     * acquire all Message records
     *
     * @return Collection
     */
    public function acquireAll(): Collection
    {
        return parent::acquireAll($id);
    }

    /**
     * acquire a Message record
     *
     * @param Int $id
     * @return SendTargetMessage
     */
    public function acquire($id)
    {
        return parent::acquire($id);
    }

    /**
     * add a SendTargetMessage record
     *
     * @param Array $attributes
     * @return Bool/SendTargetMessage
     */
    public function add(array $attributes)
    {
        return parent::add($attributes);
    }

    /**
     * adjust a SendTargetMessage record
     *
     * @param Int $id
     * @param Array $attributes
     * @return Bool/SendTargetMessage
     */
    public function adjust(int $id, array $attributes)
    {
        return parent::adjust($id, $attributes);
    }

    /**
     * annul a SendTargetMessage record
     *
     * @param Int $id
     * @return Bool
     */
    public function annul(int $id)
    {
        return parent::annul($id);
    }

    /**
     * acquire all kumicd from Message records via messageId
     * call NTC (No Try Catch) method
     *
     * @param null|int $messageId
     * @return array
     */
    public function acquireAllKumicdByMessage($messageId, int $paginatePerPage = 10)
    {
        $rtn = [];

        try {
            if (!empty($messageId)) {
                $rtn = SendTargetMessage::where('messageId', $messageId)
                    ->whereNotDeleted()
                    ->sortDesc()
                    ->pluck('kumicd')
                    ->toArray();
            }
        } catch (\Exception $e) {
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        return $rtn;
    }
}
