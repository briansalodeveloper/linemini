<?php

namespace App\Services;

use DB;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use App\Helpers\Upload;
use App\Interfaces\Flyer\DisplayTargetFlyerRepositoryInterface;
use App\Interfaces\Flyer\DisplayTargetFlyerUBRepositoryInterface;
use App\Interfaces\Flyer\DisplayTargetFlyerAORepositoryInterface;
use App\Interfaces\Flyer\FlyerDisplayStoreRepositoryInterface;
use App\Interfaces\FlyerPlanRepositoryInterface;
use App\Models\FlyerPlan;
use App\Traits\Rules\CsvMemberCodeRuleTrait;
use App\Traits\Services\DisplayOrSendTargetServiceTrait;

class FlyerPlanService extends MainService
{
    use CsvMemberCodeRuleTrait;
    use DisplayOrSendTargetServiceTrait;

    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var FlyerDisplayStoreRepositoryInterface
     */
    private $flyerDisplayStoreRepository;

    /**
     * @var DisplayTargetFlyerRepositoryInterface
     */
    private $displayTargetFlyerRepository;

    /**
     * @var DisplayTargetFlyerAORepositoryInterface
     */
    private $displayTargetFlyerAORepository;

    /**
     * @var DisplayTargetFlyerUBRepositoryInterface
     */
    private $displayTargetFlyerUBRepository;

    /*======================================================================
     * CONSTRUCTOR
     *======================================================================*/

    /**
     * @param FlyerPlanRepositoryInterface $repository
     * @param DisplayTargetFlyerUBRepositoryInterface $displayTargetFlyerUBRepository
     * @param DisplayTargetFlyerAORepositoryInterface $displayTargetFlyerAORepository
     * @param DisplayTargetFlyerRepositoryInterface $displayTargetFlyerRepository
     */
    public function __construct(
        FlyerPlanRepositoryInterface $repository,
        FlyerDisplayStoreRepositoryInterface $flyerDisplayStoreRepository,
        DisplayTargetFlyerRepositoryInterface $displayTargetFlyerRepository,
        DisplayTargetFlyerAORepositoryInterface $displayTargetFlyerAORepository,
        DisplayTargetFlyerUBRepositoryInterface $displayTargetFlyerUBRepository
    ) {
        $this->repository = $repository;
        $this->flyerDisplayStoreRepository = $flyerDisplayStoreRepository;
        $this->displayTargetFlyerRepository = $displayTargetFlyerRepository;
        $this->displayTargetFlyerAORepository = $displayTargetFlyerAORepository;
        $this->displayTargetFlyerUBRepository = $displayTargetFlyerUBRepository;
    }

    /*======================================================================
     * PUBLIC METHODS
     *======================================================================*/

    /**
     * acquire all FlyerPlan records
     *
     * @return Array $rtn
     */
    public function all()
    {
        $rtn = [
            'data' => $this->repository->acquireAll()
        ];

        return $rtn;
    }

    /**
     * get one FlyerPlan record.
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
            'umList' => $this->displayTargetFlyerRepository->acquireAllKumicdByFlyer($id)
        ];

        return $rtn;
    }

    /**
     * store a FlyerPlan record with its relation to DisplayTargetFlyer, DisplayTargetFlyerUB, DisplayTargetFlyerAO.
     *
     * @return Bool|FlyerPlan $rtn
     */
    public function store()
    {
        $rtn = false;
        DB::beginTransaction();
        try {
            $copyFrom = null;

            if (request()->get(config('searchQuery.param.copy'), config('searchQuery.value.copyNo'))) {
                $copyFrom = $this->repository->NTCacquire(request()->get('flyerPlanId'));

                $data = [
                    'displayTargetFlg' => $copyFrom->displayTargetFlg,
                    'startDateTime' => $copyFrom->startDateTime,
                    'endDateTime' => $copyFrom->endDateTime,
                    'flyerName' => $copyFrom->flyerName . ' - ' . __('words.Copy'),
                    'flyerImg' => $copyFrom->flyerImg,
                    'flyerUraImg' => $copyFrom->flyerUraImg,
                ];
            } else {
                $data = [
                    'displayTargetFlg' => request()->get('displayTargetFlg'),
                    'startDateTime' => request()->get('startDateTime'),
                    'endDateTime' => request()->get('endDateTime'),
                    'flyerName' => request()->get('flyerName'),
                    'flyerImg' => request()->get('flyerImg') ? request()->get('flyerImg', '') : '',
                    'flyerUraImg' => request()->get('flyerUraImg') ? request()->get('flyerUraImg', '') : '',
                ];
            }

            $flyerPlan = $this->repository->NTCadd($data);

            if ($flyerPlan) {
                if (request()->get(config('searchQuery.param.copy'), config('searchQuery.value.copyNo'))) {
                    $displayStoreIds = $copyFrom->flyerDisplayStoreIdList;
                    $unionMemberIds = $copyFrom->displayTargetFlyerIdList;
                    $aoIds = $copyFrom->displayTargetFlyerAoAffiliationOfficeIdList;
                    $ubIds = $copyFrom->displayTargetFlyerUbUtilizationBusinessIdList;
                } else {
                    $displayStoreIds = request()->get('displayStore') ? request()->get('displayStore', []) : [];
                    $unionMemberIds = static::getValidUnionMemberCodeFromExcelUrl(request()->get('unionMemberCsv'));
                    $aoIds = request()->get('affiliationOffice') ? request()->get('affiliationOffice', []) : [];
                    $ubIds = request()->get('utilizationBusiness') ? request()->get('utilizationBusiness', []) : [];
                }

                $this->storeUpdateTargetCommon(
                    $displayStoreIds,
                    'flyerPlanId',
                    $flyerPlan,
                    $flyerPlan->flyerDisplayStore,
                    'storeId',
                    null,
                    null,
                    'flyerDisplayStoreIdList',
                    $this->flyerDisplayStoreRepository
                );
                $this->storeUpdateMemberCode(
                    $unionMemberIds,
                    'flyerPlanId',
                    $flyerPlan,
                    $flyerPlan->displayTargetFlyer,
                    $flyerPlan->displayTargetFlg,
                    FlyerPlan::DSPTARGET_UNIONMEMBER,
                    'displayTargetFlyerIdList',
                    $this->displayTargetFlyerRepository
                );
                $this->storeUpdateAo(
                    $aoIds,
                    'flyerPlanId',
                    $flyerPlan,
                    $flyerPlan->displayTargetFlyerAO,
                    $flyerPlan->displayTargetFlg,
                    FlyerPlan::DSPTARGET_AO,
                    'displayTargetFlyerAoAffiliationOfficeIdList',
                    $this->displayTargetFlyerAORepository
                );
                $this->storeUpdateUb(
                    $ubIds,
                    'flyerPlanId',
                    $flyerPlan,
                    $flyerPlan->displayTargetFlyerUB,
                    $flyerPlan->displayTargetFlg,
                    FlyerPlan::DSPTARGET_UB,
                    'displayTargetFlyerUbUtilizationBusinessIdList',
                    $this->displayTargetFlyerUBRepository
                );

                if (request()->get(config('searchQuery.param.copy'), config('searchQuery.value.copyNo'))) {
                    $rtn = $flyerPlan;
                } else {
                    $flyerImg = $this->storeThumbnailUrlToS3(request()->get('flyerImg'));
                    $flyerUraImg = $this->storeThumbnailUrlToS3(request()->get('flyerUraImg'));

                    if ($flyerImg) {
                        $flyerPlan->flyerImg = $flyerImg;
                    }

                    if ($flyerUraImg) {
                        $flyerPlan->flyerUraImg = $flyerUraImg;
                    }

                    if ($flyerImg || $flyerUraImg) {
                        $rtn = $this->repository->NTCadjust($flyerPlan->id, [
                            'flyerImg' => $flyerPlan->flyerImg,
                            'flyerUraImg' => $flyerPlan->flyerUraImg,
                        ]);
                    } else {
                        $rtn = $flyerPlan;
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
     * update a Flyer record with its relation to DisplayTargetFlyer, DisplayTargetFlyerUB, DisplayTargetFlyerAO.
     *
     * @param Int $id
     * @return Bool|FlyerPlan $rtn
     */
    public function update($id)
    {
        $rtn = false;

        DB::beginTransaction();
        try {
            $data = [
                'displayStore' => request()->get('displayStore'),
                'displayTargetFlg' => request()->get('displayTargetFlg'),
                'startDateTime' => request()->get('startDateTime'),
                'endDateTime' => request()->get('endDateTime'),
                'flyerName' => request()->get('flyerName'),
                'flyerImg' => request()->get('flyerImg') ? request()->get('flyerImg', '') : '',
                'flyerUraImg' => request()->get('flyerUraImg') ? request()->get('flyerUraImg', '') : '',
            ];

            $flyerPlan = $this->repository->NTCadjust($id, $data);

            if ($flyerPlan) {
                $displayStoreIds = request()->get('displayStore') ? request()->get('displayStore', []) : [];
                $unionMemberIds = static::getValidUnionMemberCodeFromExcelUrl(request()->get('unionMemberCsv'));
                $aoIds = request()->get('affiliationOffice') ? request()->get('affiliationOffice', []) : [];
                $ubIds = request()->get('utilizationBusiness') ? request()->get('utilizationBusiness', []) : [];

                if (is_null(request()->get('unionMemberCsv')) && request()->get('displayTargetFlg') == FlyerPlan::DSPTARGET_UNIONMEMBER) {
                    $unionMemberIds = $flyerPlan->displayTargetFlyerIdList;
                }

                $this->storeUpdateTargetCommon(
                    $displayStoreIds,
                    'flyerPlanId',
                    $flyerPlan,
                    $flyerPlan->flyerDisplayStore,
                    'storeId',
                    null,
                    null,
                    'flyerDisplayStoreIdList',
                    $this->flyerDisplayStoreRepository
                );
                $this->storeUpdateMemberCode(
                    $unionMemberIds,
                    'flyerPlanId',
                    $flyerPlan,
                    $flyerPlan->displayTargetFlyer,
                    $flyerPlan->displayTargetFlg,
                    FlyerPlan::DSPTARGET_UNIONMEMBER,
                    'displayTargetFlyerIdList',
                    $this->displayTargetFlyerRepository
                );
                $this->storeUpdateAo(
                    $aoIds,
                    'flyerPlanId',
                    $flyerPlan,
                    $flyerPlan->displayTargetFlyerAO,
                    $flyerPlan->displayTargetFlg,
                    FlyerPlan::DSPTARGET_AO,
                    'displayTargetFlyerAoAffiliationOfficeIdList',
                    $this->displayTargetFlyerAORepository
                );
                $this->storeUpdateUb(
                    $ubIds,
                    'flyerPlanId',
                    $flyerPlan,
                    $flyerPlan->displayTargetFlyerUB,
                    $flyerPlan->displayTargetFlg,
                    FlyerPlan::DSPTARGET_UB,
                    'displayTargetFlyerUbUtilizationBusinessIdList',
                    $this->displayTargetFlyerUBRepository
                );

                $flyerImg = $this->storeThumbnailUrlToS3(request()->get('flyerImg'));
                $flyerUraImg = $this->storeThumbnailUrlToS3(request()->get('flyerUraImg'));

                if ($flyerImg) {
                    $flyerPlan->flyerImg = $flyerImg;
                }

                if ($flyerUraImg) {
                    $flyerPlan->flyerUraImg = $flyerUraImg;
                }

                if ($flyerImg || $flyerUraImg) {
                    $rtn = $this->repository->NTCadjust($flyerPlan->id, [
                        'flyerImg' => $flyerPlan->flyerImg,
                        'flyerUraImg' => $flyerPlan->flyerUraImg,
                    ]);
                } else {
                    $rtn = $flyerPlan;
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
     * annul a FlyerPlan record with its relation to DisplayTargetFlyer, DisplayTargetFlyerUB, DisplayTargetFlyerAO.
     *
     * @param Int $id
     * @return Bool
     */
    public function destroy($id)
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

    /*======================================================================
     * PRIVATE METHODS
     *======================================================================*/

    /**
     * store thumbnail url to s3
     *
     * @param String $thumbnailUrl
     * @return Bool|String $rtn
     */
    private function storeThumbnailUrlToS3(string $thumbnailUrl)
    {
        $rtn = false;
        $path = Upload::getCustomPath('flyerThumbnail');
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
}
