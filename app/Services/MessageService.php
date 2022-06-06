<?php

namespace App\Services;

use DB;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use App\API\Line\PushMessage;
use App\Helpers\Trumbowyg;
use App\Helpers\Upload;
use App\Interfaces\MessageRepositoryInterface;
use App\Interfaces\Message\SendTargetMessageRepositoryInterface;
use App\Interfaces\Message\SendTargetMessageAORepositoryInterface;
use App\Interfaces\Message\SendTargetMessageStoreRepositoryInterface;
use App\Interfaces\Message\SendTargetMessageUBRepositoryInterface;
use App\Interfaces\UnionMemberRepositoryInterface;
use App\Interfaces\UnionLineRepositoryInterface;
use App\Interfaces\Flyer\FlyerStoreSelectRepositoryInterface;
use App\Models\Message;
use App\Traits\Rules\CsvMemberCodeRuleTrait;
use App\Traits\Services\DisplayOrSendTargetServiceTrait;

class MessageService extends MainService
{
    use CsvMemberCodeRuleTrait;
    use DisplayOrSendTargetServiceTrait;

    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var SendTargetMessageRepositoryInterface
     */
    private $sendTargetMessageRepository;

    /**
     * @var SendTargetMessageAORepositoryInterface
     */
    private $sendTargetMessageAORepository;

    /**
     * @var SendTargetMessageUBRepositoryInterface
     */
    private $sendTargetMessageUBRepository;

    /**
     * @var SendTargetMessageStoreRepositoryInterface
     */
    private $sendTargetMessageStoreRepository;

    /**
     * @var UnionLineRepositoryInterface
     */
    private $unionLineRepository;

    /**
     * @var UnionMemberRepositoryInterface
     */
    private $unionMemberRepository;

    /**
     * @var FlyerStoreSelectRepositoryInterface
     */
    private $flyerStoreSelectRepository;

    /*======================================================================
     * CONSTRUCTOR
     *======================================================================*/

    /**
     * @param MessageRepositoryInterface $repository
     * @param SendTargetMessageRepositoryInterface $sendTargetMessageUBRepository
     * @param SendTargetMessageAORepositoryInterface $sendTargetMessageAORepository
     * @param SendTargetMessageUBRepositoryInterface $sendTargetMessageRepository
     * @param SendTargetMessageStoreRepositoryInterface $sendTargetMessageStoreRepository
     * @param UnionLineRepositoryInterface $unionLineRepository
     * @param UnionMemberRepositoryInterface $unionMemberRepository
     * @param FlyerStoreSelectRepositoryInterface $flyerStoreSelectRepository
     */
    public function __construct(
        MessageRepositoryInterface $repository,
        SendTargetMessageRepositoryInterface $sendTargetMessageRepository,
        SendTargetMessageAORepositoryInterface $sendTargetMessageAORepository,
        SendTargetMessageUBRepositoryInterface $sendTargetMessageUBRepository,
        SendTargetMessageStoreRepositoryInterface $sendTargetMessageStoreRepository,
        UnionLineRepositoryInterface $unionLineRepository,
        UnionMemberRepositoryInterface $unionMemberRepository,
        FlyerStoreSelectRepositoryInterface $flyerStoreSelectRepository
    ) {
        $this->repository = $repository;
        $this->sendTargetMessageRepository = $sendTargetMessageRepository;
        $this->sendTargetMessageAORepository = $sendTargetMessageAORepository;
        $this->sendTargetMessageUBRepository = $sendTargetMessageUBRepository;
        $this->sendTargetMessageStoreRepository = $sendTargetMessageStoreRepository;
        $this->unionLineRepository = $unionLineRepository;
        $this->unionMemberRepository = $unionMemberRepository;
        $this->flyerStoreSelectRepository = $flyerStoreSelectRepository;
    }

    /*======================================================================
     * PUBLIC METHODS
     *======================================================================*/

    /**
     * fetch all records
     *
     * @return Array $rtn
     */
    public function all(): array
    {
        $rtn = [
            'data' => $this->repository->acquireAll()
        ];

        return $rtn;
    }

    /**
     * fetch a record
     *
     * @param Int|Null $id
     * @return Array $rtn
     */
    public function get(int $id = null): array
    {
        $message = $this->repository->acquire($id);

        if ($message->isNotEmpty) {
            if ($message->isStatusNotSend) {
                $message = $this->repository->adjust($id, [
                    'draftFlg' => Message::DRAFTFLG_YES
                ]);
            }
        }

        $listAo = config('const.listAo');
        $listUb = config('const.listUb');
        $listStore = $this->flyerStoreSelectRepository->acquireAllStoreDistinctWithUnionLineUser();

        $rtn = [
            'data' => $message,
            'listAo' => $listAo,
            'listAoCount' =>  $this->unionMemberRepository->acquireCountByAoId(array_keys($listAo)),
            'listUb' =>  $listUb,
            'listUbCount' =>  $this->unionMemberRepository->acquireCountByUbId(array_keys($listUb)),
            'storeList' =>  $listStore,
            'storeListCount' =>  $this->unionMemberRepository->acquireCountByStoreId(array_keys($listStore)),
            'umList' => $this->sendTargetMessageRepository->acquireAllKumicdByMessage($id)
        ];

        return $rtn;
    }

    /**
     * store a record
     *
     * @return Bool|Message $rtn
     */
    public function store()
    {
        $rtn = false;

        DB::beginTransaction();
        try {
            $copyFrom = null;

            if (request()->get(config('searchQuery.param.copy'), config('searchQuery.value.copyNo'))) {
                $copyFrom = $this->repository->NTCacquire(request()->get('messageId'));

                $data = [
                    'sendTargetFlg' => $copyFrom->sendTargetFlg,
                    'sendDateTime' => $copyFrom->sendDateTime,
                    'messageName' => $copyFrom->messageName . ' - ' . __('words.Copy'),
                    'thumbnail' => $copyFrom->thumbnail,
                    'thumbnailPreview' => $copyFrom->thumbnailPreview,
                    'contents' => $copyFrom->contents,
                    'draftFlg' => $copyFrom->draftFlg,
                ];
            } else {
                $data = [
                    'sendTargetFlg' => request()->get('sendTargetFlg'),
                    'sendDateTime' => request()->get('sendDateTime'),
                    'messageName' => request()->get('messageName'),
                    'thumbnail' => request()->get('thumbnail') ? request()->get('thumbnail', '') : '',
                    'thumbnailPreview' => request()->get('thumbnail') ? request()->get('thumbnail', '') : '',
                    'contents' => request()->get('contents') ? request()->get('contents', '') : '',
                    'draftFlg' => request()->get('draft', 0),
                ];
            }

            $message = $this->repository->NTCadd($data);

            if ($message) {
                if (request()->get(config('searchQuery.param.copy'), config('searchQuery.value.copyNo'))) {
                    $unionMemberIds = $copyFrom->sendTargetMessageIdList;
                    $aoIds = $copyFrom->sendTargetMessageAoAffiliationOfficeIdList;
                    $ubIds = $copyFrom->sendTargetMessageUbUtilizationBusinessIdList;
                    $storeIds = $copyFrom->sendTargetMessageStoreIdList;
                } else {
                    $unionMemberIds = static::getValidUnionMemberCodeFromExcelUrl(request()->get('unionMemberCsv'));
                    $aoIds = request()->get('affiliationOffice') ? request()->get('affiliationOffice', []) : [];
                    $ubIds = request()->get('utilizationBusiness') ? request()->get('utilizationBusiness', []) : [];
                    $storeIds = request()->get('storeId') ? request()->get('storeId', []) : [];
                }

                $this->storeUpdateMemberCode(
                    $unionMemberIds,
                    'messageId',
                    $message,
                    $message->sendTargetMessage,
                    $message->sendTargetFlg,
                    Message::SENDTARGET_UNIONMEMBER,
                    'sendTargetMessageIdList',
                    $this->sendTargetMessageRepository
                );
                $this->storeUpdateAo(
                    $aoIds,
                    'messageId',
                    $message,
                    $message->sendTargetMessageAO,
                    $message->sendTargetFlg,
                    Message::SENDTARGET_AO,
                    'sendTargetMessageAoAffiliationOfficeIdList',
                    $this->sendTargetMessageAORepository
                );
                $this->storeUpdateUb(
                    $ubIds,
                    'messageId',
                    $message,
                    $message->sendTargetMessageUB,
                    $message->sendTargetFlg,
                    Message::SENDTARGET_UB,
                    'sendTargetMessageUbUtilizationBusinessIdList',
                    $this->sendTargetMessageUBRepository
                );
                $this->storeUpdateTargetCommon(
                    $storeIds,
                    'messageId',
                    $message,
                    $message->sendTargetMessageStore,
                    'storeId',
                    null,
                    null,
                    'sendTargetMessageStoreIdList',
                    $this->sendTargetMessageStoreRepository
                );

                if (request()->get(config('searchQuery.param.copy'), config('searchQuery.value.copyNo'))) {
                    $rtn = $message;
                } else {
                    $thumbnails = $this->storeThumbnailUrlToS3(request()->get('thumbnail'));
                    $contents = $this->storeContentsUrlToS3($message->contents);

                    if ($thumbnails) {
                        $message->thumbnail = $thumbnails['main'];
                        $message->thumbnailPreview = $thumbnails['preview'];
                    }

                    if ($contents) {
                        $message->contents = $contents;
                    }

                    if ($thumbnails || $contents) {
                        $rtn = $this->repository->NTCadjust($message->id, [
                            'sendFlg' => $message->sendFlg,
                            'thumbnail' => $message->thumbnail,
                            'thumbnailPreview' => $message->thumbnailPreview,
                            'contents' => $message->contents,
                        ]);
                    } else {
                        $rtn = $message;
                    }
                }
            }

            if ($rtn) {
                DB::commit();
                $this->tmpResourcesDump();
            } else {
                DB::rollback();
            }
        } catch (\Exception $e) {
            DB::rollback();
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            DB::rollback();
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        return $rtn;
    }

    /**
     * update a record
     *
     * @param Int $id
     * @return Bool|Message $rtn
     */
    public function update(int $id)
    {
        $rtn = false;

        DB::beginTransaction();
        try {
            $oldData = $this->repository->NTCacquire($id);

            $data = [
                'sendTargetFlg' => request()->get('sendTargetFlg'),
                'sendDateTime' => request()->get('sendDateTime'),
                'messageName' => request()->get('messageName'),
                'thumbnail' => request()->get('thumbnail') ? request()->get('thumbnail', '') : '',
                'contents' => request()->get('contents') ? request()->get('contents', '') : '',
                'draftFlg' => request()->get('draft', 0),
            ];

            if ($oldData->sendDateTime != $data['sendDateTime'] && Carbon::parse($oldData->sendDateTime) < Carbon::parse($data['sendDateTime'])) {
                $data['sendFlg'] = Message::SENDSATTUS_NO;
            }

            $message = $this->repository->NTCadjust($id, $data);

            if ($message) {
                $unionMemberIds = static::getValidUnionMemberCodeFromExcelUrl(request()->get('unionMemberCsv'));
                $aoIds = request()->get('affiliationOffice') ? request()->get('affiliationOffice', []) : [];
                $ubIds = request()->get('utilizationBusiness') ? request()->get('utilizationBusiness', []) : [];
                $storeIds = request()->get('storeId') ? request()->get('storeId', []) : [];

                if (is_null(request()->get('unionMemberCsv')) && request()->get('sendTargetFlg') == Message::SENDTARGET_UNIONMEMBER) {
                    $unionMemberIds = $message->sendTargetMessageIdList;
                }

                $this->storeUpdateMemberCode(
                    $unionMemberIds,
                    'messageId',
                    $message,
                    $message->sendTargetMessage,
                    $message->sendTargetFlg,
                    Message::SENDTARGET_UNIONMEMBER,
                    'sendTargetMessageIdList',
                    $this->sendTargetMessageRepository
                );
                $this->storeUpdateAo(
                    $aoIds,
                    'messageId',
                    $message,
                    $message->sendTargetMessageAO,
                    $message->sendTargetFlg,
                    Message::SENDTARGET_AO,
                    'sendTargetMessageAoAffiliationOfficeIdList',
                    $this->sendTargetMessageAORepository
                );
                $this->storeUpdateUb(
                    $ubIds,
                    'messageId',
                    $message,
                    $message->sendTargetMessageUB,
                    $message->sendTargetFlg,
                    Message::SENDTARGET_UB,
                    'sendTargetMessageUbUtilizationBusinessIdList',
                    $this->sendTargetMessageUBRepository
                );
                $this->storeUpdateTargetCommon(
                    $storeIds,
                    'messageId',
                    $message,
                    $message->sendTargetMessageStore,
                    'storeId',
                    null,
                    null,
                    'sendTargetMessageStoreIdList',
                    $this->sendTargetMessageStoreRepository
                );

                $thumbnails = $this->storeThumbnailUrlToS3(request()->get('thumbnail'));
                $contents = $this->storeContentsUrlToS3(request()->get('contents'));

                if ($thumbnails) {
                    $message->thumbnail = $thumbnails['main'];
                    $message->thumbnailPreview = $thumbnails['preview'];
                }

                if ($contents) {
                    $message->contents = $contents;
                }

                if ($thumbnails || $contents) {
                    $rtn = $this->repository->NTCadjust($message->id, [
                        'thumbnail' => $message->thumbnail,
                        'thumbnailPreview' => $message->thumbnailPreview,
                        'contents' => $message->contents,
                    ]);
                } else {
                    $rtn = $message;
                }
            }

            if ($rtn) {
                DB::commit();
                $this->tmpResourcesDump();
            } else {
                DB::rollback();
            }
        } catch (\Exception $e) {
            DB::rollback();
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
        } catch (\Error $e) {
            DB::rollback();
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
        }

        return $rtn;
    }

    /**
     * delete a record
     *
     * @param Int $id
     * @return Bool
     */
    public function destroy(int $id)
    {
        return $this->repository->annul($id);
    }

    /**
     * upload a file (image/csv)
     *
     * @param UploadedFile $file
     * @param String $fileType
     * @return Bool|String $rtn
     */
    public function upload(UploadedFile $file, string $fileType)
    {
        $rtn = false;

        if (!empty($file) && !empty($fileType)) {
            if ($fileType == 'image') {
                $fileName = Carbon::now()->timestamp . '.' . $file->extension();
                $rtn = Upload::saveImageTemp($file, null, $fileName);
            } elseif ($fileType == 'csv') {
                $fileName = $file->getClientOriginalName();
                $rtn = Upload::saveTemp($file, null, $fileName);
            }
        }

        return $rtn;
    }

    /**
     * send the message to line API messaging
     *
     * @param Message $message
     * @param Bool $isChangeStatusToSend
     * @return Array $rtn
     */
    public function send(Message $message, bool $isChangeStatusToSend = true)
    {
        $messageCntSuccess = 0;
        $messageCntFailed = 0;
        $messageCntNoLineUsers = 0;
        $unionLineIdList = [];
        $unionLineIdCount = 0;

        try {
            if ($message->isNotDraft) {
                $lineIds = [];
                $isValidData = true;
                $umIds = [];
                $ubIds = [];
                $aoIds = [];
                $storeIds = [];
                $sendSuccess = false;

                if ($message->isTargetAll) {
                    //
                } elseif ($message->isTargetUm) {
                    $umIds = $message->sendTargetMessageIdList;
                } elseif ($message->isTargetUb) {
                    $ubIds = $message->sendTargetMessageUbUtilizationBusinessIdList;
                } elseif ($message->isTargetAo) {
                    $aoIds = $message->sendTargetMessageAoAffiliationOfficeIdList;
                } elseif ($message->isTargetSt) {
                    $storeIds = $message->sendTargetMessageStoreIdList;
                } else {
                    $isValidData = false;
                }

                if ($isValidData) {
                    $list = $this->unionLineRepository->NTCacquireAllByUmAoUbOrStoreId($umIds, $ubIds, $aoIds, $storeIds);

                    if ($list->count() != 0) {
                        $lineIds = $list->pluck('LineTokenId')->toArray();
                    }
                    $unionLineIdCount = count($lineIds);

                    if (count($lineIds) != 0) {
                        $url = $message->thumbnail;
                        $urlPreview = $message->thumbnailPreview;

                        if (!empty($url) && !empty($urlPreview)) {
                            $fileName = Upload::getBaseName($url);
                            $fileNamePreview = Upload::getBaseName($urlPreview);

                            $url = explode($fileName, $url)[0];
                            $urlPreview = explode($fileNamePreview, $urlPreview)[0];

                            $fileName = urlencode($fileName);
                            $fileNamePreview = urlencode($fileNamePreview);

                            $url .= $fileName;
                            $urlPreview .= $fileNamePreview;
                        } else {
                            $url = '';
                            $urlPreview = '';
                        }

                        if (!empty($message->contents)) {
                            $contents = str_replace('<br>', "\n", $message->contents);
                            $contents = str_replace('<br/>', "\n", $contents);
                            $contents = str_replace('<br />', "\n", $contents);
                            $contents = str_replace('</p>', "\n", $contents);
                            $contents = str_replace('&nbsp;', " ", $contents);

                            if (strpos($contents, "\n", -1) !== false) {
                                $contents = substr($contents, 0, strpos($contents, "\n", -1));
                            }

                            $contents = strip_tags($contents);
                        } else {
                            $contents = '';
                        }

                        $lineIds = array_chunk($lineIds, PushMessage::MAX_SEND_USER_COUNT);

                        $sendSuccess = true;
                        foreach ($lineIds as $ids) {
                            $rtnSend = PushMessage::send($ids, $contents, $url, $urlPreview);

                            if (!$rtnSend) {
                                $sendSuccess = false;
                            }
                        }
                    }
                }

                $unionLineIdList = $lineIds;

                if ($sendSuccess || count($lineIds) == 0) {
                    if ($isChangeStatusToSend) {
                        $this->repository->NTCadjust($message->id, [
                            'sendFlg' => Message::SENDSATTUS_YES
                        ]);
                    }
                }

                if ($sendSuccess) {
                    $messageCntSuccess++;
                } elseif (count($lineIds) == 0) {
                    $messageCntNoLineUsers++;
                } else {
                    $messageCntFailed++;
                }
            } else {
                \L0g::error('Invalid draft message sending', [
                    'id' => $message->id
                ]);
                \SlackLog::error('Invalid draft message sending. ID: ' . $message->id);
            }
        } catch (\Exception $e) {
            \L0g::error('Exception: ' . $e->getMessage());
            \SlackLog::error('Exception: ' . $e->getMessage());
            $messageCntFailed++;
        } catch (\Error $e) {
            \L0g::error($e->getMessage());
            \SlackLog::error($e->getMessage());
            $messageCntFailed++;
        }

        $rtn = [
            'messageCntSuccess' => $messageCntSuccess,
            'messageCntFailed' => $messageCntFailed,
            'messageCntNoLineUsers' => $messageCntNoLineUsers,
            'unionLineIdList' => $unionLineIdList,
            'unionLineIdCount' => $unionLineIdCount,
        ];

        return $rtn;
    }

    /*======================================================================
     * PRIVATE METHODS
     *======================================================================*/

    /**
     * store thumbnail url to s3
     *
     * @param Null|String $thumbnailUrl
     * @return Bool|String $rtn
     */
    private function storeThumbnailUrlToS3($thumbnailUrl)
    {
        $rtn = '';

        if ($thumbnailUrl) {
            $path = Upload::getCustomPath('messageThumbnail');
            $name = Upload::getBaseName($thumbnailUrl);

            if (Upload::isUrlPublic($thumbnailUrl)) {
                $saved = Upload::saveFromUrl($thumbnailUrl, $path, $name, null, Upload::DISK_S3);

                if ($saved) {
                    $name = 'preview-' . $name;
                    $savedPreview = Upload::saveFromUrl($thumbnailUrl, $path, $name, null, Upload::DISK_S3);

                    if ($savedPreview) {
                        $rtn = [
                            'main' => $saved,
                            'preview' => $savedPreview
                        ];
                        $this->tmpResourcesAdd(self::RESOURCETYPE_IMAGE, $thumbnailUrl);
                    }
                }
            }
        }

        return $rtn;
    }

    /**
     * store contents url to s3
     *
     * @param Null|String $contents
     * @return Bool|String $rtn
     */
    private function storeContentsUrlToS3($contents)
    {
        $rtn = false;

        if ($contents) {
            $rtn = Trumbowyg::moveTemporaryFiles(
                $contents,
                Upload::getCustomPath('messageImageContent'),
                Upload::DISK_S3,
                false,
                false,
                true
            );

            $this->tmpResourcesAdd(self::RESOURCETYPE_IMAGE, $rtn['updatedImages']);
            $rtn = $rtn['html'];
        }

        return $rtn;
    }
}
