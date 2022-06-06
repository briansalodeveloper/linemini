<?php

namespace App\Services;

use DB;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use App\Helpers\Trumbowyg;
use App\Helpers\Upload;
use App\Interfaces\ContentPlanRepositoryInterface;
use App\Interfaces\Content\DisplayTargetContentRepositoryInterface;
use App\Interfaces\Content\DisplayTargetContentAORepositoryInterface;
use App\Interfaces\Content\DisplayTargetContentUBRepositoryInterface;
use App\Models\ContentPlan;
use App\Traits\Rules\CsvMemberCodeRuleTrait;
use App\Traits\Services\DisplayOrSendTargetServiceTrait;

class ContentPlanService extends MainService
{
    use CsvMemberCodeRuleTrait;
    use DisplayOrSendTargetServiceTrait;

    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetContentRepositoryInterface
     */
    private $displayTargetContentRepository;

    /**
     * @var DisplayTargetContentAORepositoryInterface
     */
    private $displayTargetContentAORepository;

    /**
     * @var DisplayTargetContentUBRepositoryInterface
     */
    private $displayTargetContentUBRepository;

    /*======================================================================
     * CONSTRUCTOR
     *======================================================================*/

    /**
     * @param ContentPlanRepositoryInterface $repository
     * @param DisplayTargetContentRepositoryInterface $displayTargetContentUBRepository
     * @param DisplayTargetContentAORepositoryInterface $displayTargetContentAORepository
     * @param DisplayTargetContentUBRepositoryInterface $displayTargetContentRepository
     */
    public function __construct(
        ContentPlanRepositoryInterface $repository,
        DisplayTargetContentRepositoryInterface $displayTargetContentRepository,
        DisplayTargetContentAORepositoryInterface $displayTargetContentAORepository,
        DisplayTargetContentUBRepositoryInterface $displayTargetContentUBRepository
    ) {
        $this->repository = $repository;
        $this->displayTargetContentRepository = $displayTargetContentRepository;
        $this->displayTargetContentAORepository = $displayTargetContentAORepository;
        $this->displayTargetContentUBRepository = $displayTargetContentUBRepository;
    }

    /*======================================================================
     * PUBLIC METHODS
     *======================================================================*/

    /**
     * fetch all records
     *
     * @param Int $type
     * @return Array $rtn
     */
    public function all(int $type): array
    {
        $rtn = [
            'data' => $this->repository->acquireAll($type)
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
        $rtn = [
            'data' => $this->repository->acquire($id),
            'listAo' =>  config('const.listAo'),
            'listUb' =>  config('const.listUb'),
            'umList' => $this->displayTargetContentRepository->acquireAllKumicdByContentPlan($id)
        ];

        return $rtn;
    }

    /**
     * store a record
     *
     * @param Int $type
     * @return Bool|ContentPlan $rtn
     */
    public function store(int $type)
    {
        $rtn = false;
        DB::beginTransaction();
        try {
            $typeStr = '';
            $copyFrom = null;

            if ($type == ContentPlan::CONTENTTYPE_NOTICE) {
                $typeStr = 'notice';
            } elseif ($type == ContentPlan::CONTENTTYPE_RECIPE) {
                $typeStr = 'recipe';
            } elseif ($type == ContentPlan::CONTENTTYPE_PRODUCTINFO) {
                $typeStr = 'productInformation';
            } elseif ($type == ContentPlan::CONTENTTYPE_COLUMN) {
                $typeStr = 'column';
            }

            if (request()->get(config('searchQuery.param.copy'), config('searchQuery.value.copyNo'))) {
                $copyFrom = $this->repository->NTCacquire(request()->get('contentPlanId'));

                $data = [
                    'contentType' => $copyFrom->contentType,
                    'contentTypeNews' => $copyFrom->contentTypeNews,
                    'displayTargetFlg' => $copyFrom->displayTargetFlg,
                    'startDateTime' => $copyFrom->startDateTime,
                    'endDateTime' => $copyFrom->endDateTime,
                    'openingLetter' => $copyFrom->openingLetter . ' - ' . __('words.Copy'),
                    'openingImg' => $copyFrom->openingImg,
                    'contents' => $copyFrom->contents,
                ];
            } else {
                $data = [
                    'contentType' => $type,
                    'contentTypeNews' => request()->get('contentTypeNews'),
                    'displayTargetFlg' => request()->get('displayTargetFlg'),
                    'startDateTime' => request()->get('startDateTime'),
                    'endDateTime' => request()->get('endDateTime'),
                    'openingLetter' => request()->get('openingLetter'),
                    'openingImg' => request()->get('openingImg') ? request()->get('openingImg', '') : '',
                    'contents' => request()->get('contents') ? request()->get('contents', '') : '',
                ];
            }

            $contentPlan = $this->repository->NTCadd($data);

            if ($contentPlan) {
                if (request()->get(config('searchQuery.param.copy'), config('searchQuery.value.copyNo'))) {
                    $unionMemberIds = $copyFrom->displayTargetContentIdList;
                    $aoIds = $copyFrom->displayTargetContentAoAffiliationOfficeIdList;
                    $ubIds = $copyFrom->displayTargetContentUbUtilizationBusinessIdList;
                } else {
                    $unionMemberIds = static::getValidUnionMemberCodeFromExcelUrl(request()->get('unionMemberCsv'));
                    $aoIds = request()->get('affiliationOffice') ? request()->get('affiliationOffice', []) : [];
                    $ubIds = request()->get('utilizationBusiness') ? request()->get('utilizationBusiness', []) : [];
                }

                $this->storeUpdateMemberCode(
                    $unionMemberIds,
                    'contentPlanId',
                    $contentPlan,
                    $contentPlan->displayTargetContent,
                    $contentPlan->displayTargetFlg,
                    ContentPlan::DSPTARGET_UNIONMEMBER,
                    'displayTargetContentIdList',
                    $this->displayTargetContentRepository
                );
                $this->storeUpdateAo(
                    $aoIds,
                    'contentPlanId',
                    $contentPlan,
                    $contentPlan->displayTargetContentAO,
                    $contentPlan->displayTargetFlg,
                    ContentPlan::DSPTARGET_AO,
                    'displayTargetContentAoAffiliationOfficeIdList',
                    $this->displayTargetContentAORepository
                );
                $this->storeUpdateUb(
                    $ubIds,
                    'contentPlanId',
                    $contentPlan,
                    $contentPlan->displayTargetContentUB,
                    $contentPlan->displayTargetFlg,
                    ContentPlan::DSPTARGET_UB,
                    'displayTargetContentUbUtilizationBusinessIdList',
                    $this->displayTargetContentUBRepository
                );

                if (request()->get(config('searchQuery.param.copy'), config('searchQuery.value.copyNo'))) {
                    $rtn = $contentPlan;
                } else {
                    $thumbnail = $this->storeThumbnailUrlToS3(request()->get('openingImg'), $typeStr);
                    $contents = $this->storeContentsUrlToS3(request()->get('contents'), $typeStr);

                    if ($thumbnail) {
                        $contentPlan->openingImg = $thumbnail;
                    }

                    if ($contents) {
                        $contentPlan->contents = $contents;
                    }

                    if ($thumbnail || $contents) {
                        $rtn = $this->repository->NTCadjust($contentPlan->id, [
                            'openingImg' => $contentPlan->openingImg,
                            'contents' => $contentPlan->contents,
                        ]);
                    } else {
                        $rtn = $contentPlan;
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
     * @param Int $type
     * @return Bool|ContentPlan $rtn
     */
    public function update(int $id, int $type)
    {
        $rtn = false;

        DB::beginTransaction();
        try {
            $typeStr = '';

            if ($type == ContentPlan::CONTENTTYPE_NOTICE) {
                $typeStr = 'notice';
            } elseif ($type == ContentPlan::CONTENTTYPE_RECIPE) {
                $typeStr = 'recipe';
            } elseif ($type == ContentPlan::CONTENTTYPE_PRODUCTINFO) {
                $typeStr = 'productInformation';
            } elseif ($type == ContentPlan::CONTENTTYPE_COLUMN) {
                $typeStr = 'column';
            }

            $data = [
                'contentType' => $type,
                'contentTypeNews' => request()->get('contentTypeNews'),
                'displayTargetFlg' => request()->get('displayTargetFlg'),
                'startDateTime' => request()->get('startDateTime'),
                'endDateTime' => request()->get('endDateTime'),
                'openingLetter' => request()->get('openingLetter'),
                'openingImg' => request()->get('openingImg') ? request()->get('openingImg', '') : '',
                'contents' => request()->get('contents') ? request()->get('contents', '') : ''
            ];

            $contentPlan = $this->repository->NTCadjust($id, $data);

            if ($contentPlan) {
                $unionMemberIds = static::getValidUnionMemberCodeFromExcelUrl(request()->get('unionMemberCsv'));
                $aoIds = request()->get('affiliationOffice') ? request()->get('affiliationOffice', []) : [];
                $ubIds = request()->get('utilizationBusiness') ? request()->get('utilizationBusiness', []) : [];

                if (is_null(request()->get('unionMemberCsv')) && request()->get('displayTargetFlg') == ContentPlan::DSPTARGET_UNIONMEMBER) {
                    $unionMemberIds = $contentPlan->displayTargetContentIdList;
                }

                $this->storeUpdateMemberCode(
                    $unionMemberIds,
                    'contentPlanId',
                    $contentPlan,
                    $contentPlan->displayTargetContent,
                    $contentPlan->displayTargetFlg,
                    ContentPlan::DSPTARGET_UNIONMEMBER,
                    'displayTargetContentIdList',
                    $this->displayTargetContentRepository
                );
                $this->storeUpdateAo(
                    $aoIds,
                    'contentPlanId',
                    $contentPlan,
                    $contentPlan->displayTargetContentAO,
                    $contentPlan->displayTargetFlg,
                    ContentPlan::DSPTARGET_AO,
                    'displayTargetContentAoAffiliationOfficeIdList',
                    $this->displayTargetContentAORepository
                );
                $this->storeUpdateUb(
                    $ubIds,
                    'contentPlanId',
                    $contentPlan,
                    $contentPlan->displayTargetContentUB,
                    $contentPlan->displayTargetFlg,
                    ContentPlan::DSPTARGET_UB,
                    'displayTargetContentUbUtilizationBusinessIdList',
                    $this->displayTargetContentUBRepository
                );

                $thumbnail = $this->storeThumbnailUrlToS3(request()->get('openingImg'), $typeStr);
                $contents = $this->storeContentsUrlToS3(request()->get('contents'), $typeStr);

                if ($thumbnail) {
                    $contentPlan->openingImg = $thumbnail;
                }

                if ($contents) {
                    $contentPlan->contents = $contents;
                }

                if ($thumbnail || $contents) {
                    $rtn = $this->repository->NTCadjust($contentPlan->id, [
                        'openingImg' => $contentPlan->openingImg,
                        'contents' => $contentPlan->contents,
                    ]);
                } else {
                    $rtn = $contentPlan;
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
            if ($fileType == \Globals::FILETYPE_IMAGE) {
                $fileName = Carbon::now()->timestamp . '.' . $file->extension();
                $rtn = Upload::saveImageTemp($file, null, $fileName);
            } elseif ($fileType == \Globals::FILETYPE_CSV) {
                $fileName = $file->getClientOriginalName();
                $rtn = Upload::saveTemp($file, null, $fileName);
            }
        }

        return $rtn;
    }

    /*======================================================================
     * PRIVATE METHODS
     *======================================================================*/

    /**
     * store thumbnail url to s3
     *
     * @param String $thumbnailUrl
     * @param String $typeStr
     * @return Bool|String $rtn
     */
    private function storeThumbnailUrlToS3(string $thumbnailUrl, string $typeStr)
    {
        $rtn = false;
        $path = Upload::getCustomPath($typeStr . 'Thumbnail');
        $name = Upload::getBaseName($thumbnailUrl);

        if (Upload::isUrlPublic($thumbnailUrl)) {
            $saved = Upload::saveFromUrl($thumbnailUrl, $path, $name, null, Upload::DISK_S3);

            if ($saved) {
                $rtn = $saved;
                $this->tmpResourcesAdd(self::RESOURCETYPE_IMAGE, $thumbnailUrl);
            }
        }

        return $rtn;
    }

    /**
     * store contents url to s3
     *
     * @param String|Null $contents
     * @param String $typeStr
     * @return Bool|String $rtn
     */
    private function storeContentsUrlToS3($contents, string $typeStr)
    {
        $rtn = false;

        if ($contents) {
            $rtn = Trumbowyg::moveTemporaryFiles(
                $contents,
                Upload::getCustomPath($typeStr . 'ImageContent'),
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
