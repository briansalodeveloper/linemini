<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Interfaces\MessageRepositoryInterface;
use App\Services\MessageService;

class ScheduledMessageSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduled:message_send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send message from database table M_Message';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(MessageService $service, MessageRepositoryInterface $repository)
    {
        $message = "Cron Job: Scheduled sending message\n";
        $data = $repository->acquireScheduledToBeSendNow();
        $messageCnt = $data->count();
        $messageCntSuccess = 0;
        $messageCntFailed = 0;
        $messageCntNoLineUsers = 0;
        $messageCntLineUsers = 0;
        $unionLineIdList = [];

        foreach ($data as $datum) {
            $rtn = $service->send($datum);

            $messageCntSuccess += $rtn['messageCntSuccess'];
            $messageCntFailed += $rtn['messageCntFailed'];
            $messageCntNoLineUsers += $rtn['messageCntNoLineUsers'];
            $messageCntLineUsers += $rtn['unionLineIdCount'];
            $unionLineIdList = array_merge($unionLineIdList, $rtn['unionLineIdList']);
        }

        $message .= "\tTotal messages: " . $messageCnt;
        $message .= "\n\tSuccess: " . $messageCntSuccess;
        $message .= "\n\tFailed: " . $messageCntFailed;
        $message .= "\n\tNo union line users: " . $messageCntNoLineUsers;
        $message .= "\n\tTotal union line users: " . $messageCntLineUsers;

        if ($messageCnt != 0) {
            $messageIds = $data->pluck('messageId')->toArray();

            if (count($messageIds) == 0) {
                $messageIds = '[empty]';
            } else {
                $messageIds = implode(',', $messageIds);
            }

            \L0g::info($message, [
                'Message ID list' => $messageIds,
                'Union Line ID list' => $unionLineIdList,
            ]);
        }

        $this->info($message);
    }
}
