<?php

namespace App\Services;

use DB;
use App\Helpers\Upload;
use Illuminate\Http\UploadedFile;
use App\Interfaces\StampPlanRepositoryInterface;
use App\Interfaces\DisplayTargetStampRepositoryInterface;
use App\Interfaces\DisplayTargetStampAORepositoryInterface;
use App\Interfaces\DisplayTargetStampUBRepositoryInterface;
use App\Interfaces\StampPlanStoreRepositoryInterface;
use App\Interfaces\StampPlanTargetClassRepositoryInterface;
use App\Interfaces\StampPlanProductRepositoryInterface;
use App\Traits\Rules\CsvMemberCodeRuleTrait;
use App\Traits\Rules\CsvSpecifiedProdCategoryRuleTrait;
use App\Traits\Rules\CsvSpecifiedProductRuleTrait;
use App\Traits\Services\DisplayOrSendTargetServiceTrait;
use App\Models\StampPlan;

class StampPlanService extends MainService
{
    use CsvMemberCodeRuleTrait;
    use DisplayOrSendTargetServiceTrait;
    use CsvSpecifiedProdCategoryRuleTrait;
    use CsvSpecifiedProductRuleTrait;

    /*======================================================================
     * CONSTRUCTOR
     *======================================================================*/
    /**
     * @param StampPlanRepositoryInterface $repository
     * @param DisplayTargetStampRepositoryInterface $displayTargetStampRepository
     * @param DisplayTargetStampAORepositoryInterface $displayTargetStampAORepository
     * @param DisplayTargetStampUBRepositoryInterface $displayTargetStampUBRepository
     * @param StampPlanStoreRepositoryInterface $stampPlanStoreRepository
     * @param StampPlanTargetClassRepositoryInterface $stampPlanTargetClassRepository
     * @param StampPlanProductRepositoryInterface $stampPlanProductRepository
     */
    public function __construct(
        StampPlanRepositoryInterface $repository,
        DisplayTargetStampRepositoryInterface $displayTargetStampRepository,
        DisplayTargetStampAORepositoryInterface $displayTargetStampAORepository,
        DisplayTargetStampUBRepositoryInterface $displayTargetStampUBRepository,
        StampPlanStoreRepositoryInterface $stampPlanStoreRepository,
        StampPlanTargetClassRepositoryInterface $stampPlanTargetClassRepository,
        StampPlanProductRepositoryInterface $stampPlanProductRepository
    ) {
        $this->repository = $repository;
        $this->displayTargetStampRepository = $displayTargetStampRepository;
        $this->displayTargetStampAORepository = $displayTargetStampAORepository;
        $this->displayTargetStampUBRepository = $displayTargetStampUBRepository;
        $this->stampPlanStoreRepository = $stampPlanStoreRepository;
        $this->stampPlanTargetClassRepository = $stampPlanTargetClassRepository;
        $this->stampPlanProductRepository = $stampPlanProductRepository;
    }

    /*======================================================================
     * PUBLIC METHODS
     *======================================================================*/

    /**
     * fetch all stamp plan records
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
     * get one stamp plan record.
     *
     * @param Int|Null $id
     * @return Array $rtn
     */
    public function get(int $id = null): array
    {
        $rtn = [
            'data' => $id ? $this->repository->acquire($id) : '',
            'aoList' =>  config('const.listAo'),
            'ubList' =>  config('const.listUb'),
            'storeList' => config('const.listStore'),
        ];

        return $rtn;
    }

    /**
     * store a Stamp plan record with its relation to DisplayTargetStamp, DisplayTargetStampUB, DisplayTargetStampAO, StampPlanTargetClass, StampPlanStore.
     *
     * @return Bool|StampPlan $rtn
     */
    public function store()
    {
        $rtn = false;
        DB::beginTransaction();
        try {
            $startDate = str_replace('/', '', request()->get('startDate'));
            $startTime = date("H:i", strtotime(request()->get('startTime')));
            $endDate = str_replace('/', '', request()->get('endDate'));
            $endTime = date("H:i", strtotime(request()->get('endTime')));
            $stampGrantPurchasesPrice = null;
            $stampGrantPurchasesCount = null;
            $increasePoint = null;
            $increaseCupon = null;
            $storeFlg = empty(request()->get('store')) ? '0' : '1';
            $useFlg = request()->get('useCount') == 0 ? '0' : '1';
            $imageUrl = request()->get('stampImage');
            $stampImg = $this->storeThumbnailUrlToS3($imageUrl);
            
            if (request()->get('stampGrantFlg') == 1) {
                 $stampGrantPurchasesPrice = request()->get('SpecifiedAmount');
                 $stampGrantPurchasesCount = 0;
            } elseif (request()->get('stampGrantFlg') == 2) {
                $stampGrantPurchasesPrice = 0;
                $stampGrantPurchasesCount = request()->get('SpecifiedNumberOfPurchase');
            }
    
            if (request()->get('increaseFlg') == 1) {
                $increasePoint = request()->get('SpecifiedNumberOfPoints');
                $increaseCupon = 0;
            } elseif (request()->get('increaseFlg') == 2) {
                $increaseCupon = request()->get('SpecifiedCouponId');
                $increasePoint = 0;
            }
            $data = [
                    'stampType' => request()->get('stampType'),
                    'stampDisplayFlg' => request()->get('stampDisplayFlg'),
                     'startDate' => $startDate,
                     'startTime' => $startTime,
                     'endDate' => $endDate,
                     'endTime' => $endTime,
                     'useFlg' => $useFlg,
                     'useCount' => request()->get('useCount'),
                     'stampGrantFlg' => request()->get('stampGrantFlg'),
                     'stampGrantPurchasesPrice' => $stampGrantPurchasesPrice,
                     'stampGrantPurchasesCount' => $stampGrantPurchasesCount,
                     'stampAchievement' => request()->get('stampAchievement'),
                     'increaseFlg' => request()->get('increaseFlg'),
                     'increasePoint' => $increasePoint,
                     'increaseCupon' => $increaseCupon,
                     'productFlg' => request()->get('productFlg'),
                     'stampName' => request()->get('stampName'),
                     'stampText' => request()->get('stampText'),
                     'storeFlg' => $storeFlg,
            ];

            $stampPlan = $this->repository->add($data);
            
            $dataStampPlanTargetClass = [
                'stampPlanId' => $stampPlan->id,
                'departmentCode' => request()->get('departmentCode')
            ];

            if (request()->get('productFlg') == 3) {
                $stampPlanTargetClass = $this->stampPlanTargetClassRepository->add($dataStampPlanTargetClass);
            }

            $stampImgAdjust = $this->repository->NTCadjust($stampPlan->id, [
                'stampImg' => $stampImg
            ]);

            $storeIds = request()->get('store') ? request()->get('store') : [];
            $unionMemberIds = static::getValidUnionMemberCodeFromExcelUrl(request()->get('unionMemberCode'));
            $aoIds = request()->get('affiliationOffice') ? request()->get('affiliationOffice', []) : [];
            $ubIds = request()->get('utilizationBusiness') ? request()->get('utilizationBusiness', []) : [];
            $specifiedProdCodeData = static::getValidProductCodeFromExcelUrl(request()->get('specifiedProdCodeCsv'), false);
            $stampPlanProducts = [];
            $stampPlanCategories = [];

            if (!empty(request()->get('specifiedProdCodeCsv'))) {
                foreach ($specifiedProdCodeData as $data) {
                    $standardNameKanji = preg_replace('/\s/u', ' ', $data['standardNameKanji']);
                    $productNameKanji = preg_replace('/\s/u', ' ', $data['productNameKanji']);

                    $stampPlanProducts[] = [
                        'productName' => trim($productNameKanji) . ' ' . trim($standardNameKanji),
                        'productJancode' => $data['productCode'],
                        'productImg' => $stampPlan->stampImg,
                        'productText' => ''
                    ];
                }

                $stampPlanProducts = collect($stampPlanProducts)->unique('productJancode')->toArray();
            } elseif (!empty(request()->get('prodCategoryCsv'))) {
                foreach ($specifiedProdCategoryData as $column) {
                    $stampPlanCategories[] = [
                        'departmentCode' => $column[0],
                        'majorClassificationCode' => $column[0],
                        'middleClassificationCode' => $column[1],
                        'subclassCode' => $column[2]
                    ];
                }
            }
            
            $this->storeUpdateTargetMultipleColsCommon(
                $stampPlanProducts,
                ['productJancode'],
                'stampPlanId',
                $stampPlan,
                $stampPlan->products,
                null,
                null,
                'stampPlanProductList',
                $this->stampPlanProductRepository
            );
            $this->storeUpdateTargetMultipleColsCommon(
                $stampPlanCategories,
                ['departmentCode','middleClassificationCode','subclassCode'],
                'stampPlanId',
                $stampPlan,
                $stampPlan->stampPlanTargetClass,
                null,
                null,
                'stampPlanTargetClassList',
                $this->stampPlanTargetClassRepository
            );

            $this->storeUpdateTargetCommon(
                $storeIds,
                'stampPlanId',
                $stampPlan,
                $stampPlan->stampPlanStore,
                'storeId',
                null,
                null,
                'stampPlanStoreIdList',
                $this->stampPlanStoreRepository
            );
            $this->storeUpdateMemberCode(
                $unionMemberIds,
                'stampPlanId',
                $stampPlan,
                $stampPlan->displayTargetStamp,
                $stampPlan->stampDisplayFlg,
                StampPlan::DSPTARGET_UNIONMEMBER,
                'displayTargetStampIdList',
                $this->displayTargetStampRepository
            );
            $this->storeUpdateAo(
                $aoIds,
                'stampPlanId',
                $stampPlan,
                $stampPlan->displayTargetStampAO,
                $stampPlan->stampDisplayFlg,
                StampPlan::DSPTARGET_AO,
                'displayTargetStampAoAffiliationOfficeIdList',
                $this->displayTargetStampAORepository
            );
            $this->storeUpdateUb(
                $ubIds,
                'stampPlanId',
                $stampPlan,
                $stampPlan->displayTargetStampUB,
                $stampPlan->stampDisplayFlg,
                StampPlan::DSPTARGET_UB,
                'displayTargetStampUbUtilizationBusinessIdList',
                $this->displayTargetStampUBRepository
            );

            /*checking if all repository are not returning as false */
            $checkingRepository = [
                $stampPlan,
                isset($stampImgAdjust) ? $stampImgAdjust : [],
                isset($stampPlanTargetClass) ? $stampPlanTargetClass : [],
            ];

            if (!in_array(false, $checkingRepository, true)) {
                $rtn = true;
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
     * update a Stamp plan record with its relation to DisplayTargetStamp, DisplayTargetStampUB, DisplayTargetStampAO, StampPlanTargetClass, StampPlanStore.
     *
     * @param Int $id
     * @return Bool|StampPlan $rtn
     */
    public function update($id)
    {
        $rtn = false;
        DB::beginTransaction();
        try {
            $startDate = str_replace('/', '', request()->get('startDate'));
            $startTime = date("H:i", strtotime(request()->get('startTime')));
            $endDate = str_replace('/', '', request()->get('endDate'));
            $endTime = date("H:i", strtotime(request()->get('endTime')));
            $stampGrantPurchasesPrice = null;
            $stampGrantPurchasesCount = null;
            $increasePoint = null;
            $increaseCupon = null;
            $storeFlg = empty(request()->get('store')) ? '0' : '1';
            $useFlg = request()->get('useCount') == 0 ? '0' : '1';
            $targetClassId = request()->get('stampPlanTargetClassId') ? request()->get('stampPlanTargetClassId') : 0;
            $imageUrl = request()->get('stampImage');
    
            if (request()->get('stampGrantFlg') == 1) {
                 $stampGrantPurchasesPrice = request()->get('SpecifiedAmount');
                 $stampGrantPurchasesCount = 0;
            } elseif (request()->get('stampGrantFlg') == 2) {
                $stampGrantPurchasesPrice = 0;
                $stampGrantPurchasesCount = request()->get('SpecifiedNumberOfPurchase');
            }
    
            if (request()->get('increaseFlg') == 1) {
                $increasePoint = request()->get('SpecifiedNumberOfPoints');
                $increaseCupon = 0;
            } elseif (request()->get('increaseFlg') == 2) {
                $increaseCupon = request()->get('SpecifiedCouponId');
                $increasePoint = 0;
            }

            $data = [
                    'stampType' => request()->get('stampType'),
                    'stampDisplayFlg' => request()->get('stampDisplayFlg'),
                     'startDate' => $startDate,
                     'startTime' => $startTime,
                     'endDate' => $endDate,
                     'endTime' => $endTime,
                     'useFlg' => $useFlg,
                     'useCount' => request()->get('useCount'),
                     'stampGrantFlg' => request()->get('stampGrantFlg'),
                     'stampGrantPurchasesPrice' => $stampGrantPurchasesPrice,
                     'stampGrantPurchasesCount' => $stampGrantPurchasesCount,
                     'stampAchievement' => request()->get('stampAchievement'),
                     'increaseFlg' => request()->get('increaseFlg'),
                     'increasePoint' => $increasePoint,
                     'increaseCupon' => $increaseCupon,
                     'productFlg' => request()->get('productFlg'),
                     'stampName' => request()->get('stampName'),
                     'stampText' => request()->get('stampText'),
                     'storeFlg' => $storeFlg,
            ];

            $stampPlan = $this->repository->adjust($id, $data);

            $dataStampPlanTargetClass = [
                'stampPlanId' => $id,
                'departmentCode' => request()->get('departmentCode')
            ];

            $stampPlanTargetClass = $this->stampPlanTargetClassRepository->adjustAddTargetClass($targetClassId, $dataStampPlanTargetClass);

            if ($stampPlan->stampImg != $imageUrl) {
                $stampImg = $this->storeThumbnailUrlToS3($imageUrl);
                $stampImgAdjust = $this->repository->NTCadjust($stampPlan->id, [
                    'stampImg' => $stampImg
                ]);
            }

            $storeIds = request()->get('store') ? request()->get('store') : [];
            $unionMemberIds = static::getValidUnionMemberCodeFromExcelUrl(request()->get('unionMemberCode'));
            $aoIds = request()->get('affiliationOffice') ? request()->get('affiliationOffice', []) : [];
            $ubIds = request()->get('utilizationBusiness') ? request()->get('utilizationBusiness', []) : [];
            
            if (is_null(request()->get('unionMemberCode')) && request()->get('stampDisplayFlg') == StampPlan::DSPTARGET_UNIONMEMBER) {
                $unionMemberIds = $stampPlan->displayTargetStampIdList;
            }
                $this->storeUpdateMemberCode(
                    $unionMemberIds,
                    'stampPlanId',
                    $stampPlan,
                    $stampPlan->displayTargetStamp,
                    $stampPlan->stampDisplayFlg,
                    StampPlan::DSPTARGET_UNIONMEMBER,
                    'displayTargetStampIdList',
                    $this->displayTargetStampRepository
                );

            $this->storeUpdateTargetCommon(
                $storeIds,
                'stampPlanId',
                $stampPlan,
                $stampPlan->stampPlanStore,
                'storeId',
                null,
                null,
                'stampPlanStoreIdList',
                $this->stampPlanStoreRepository
            );
            $this->storeUpdateAo(
                $aoIds,
                'stampPlanId',
                $stampPlan,
                $stampPlan->displayTargetStampAO,
                $stampPlan->stampDisplayFlg,
                StampPlan::DSPTARGET_AO,
                'displayTargetStampAoAffiliationOfficeIdList',
                $this->displayTargetStampAORepository
            );
            $this->storeUpdateUb(
                $ubIds,
                'stampPlanId',
                $stampPlan,
                $stampPlan->displayTargetStampUB,
                $stampPlan->stampDisplayFlg,
                StampPlan::DSPTARGET_UB,
                'displayTargetStampUbUtilizationBusinessIdList',
                $this->displayTargetStampUBRepository
            );

            /*checking if all repository are not returning as false */
            $checkingRepository = [
                $stampPlan,
                isset($stampImgAdjust) ? $stampImgAdjust : [],
                isset($stampPlanTargetClass) ? $stampPlanTargetClass : [],
            ];

            if (!in_array(false, $checkingRepository, true)) {
                $rtn = true;
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
     * destroy a Stamp plan record with its relation to DisplayTargetStamp, DisplayTargetStampUB, DisplayTargetStampAO, StampPlanTargetClass, StampPlanStore.
     *
     * @param Int $id
     * @return Bool $rtn
     */
    public function destroy($id)
    {
        $rtn = $this->repository->annul($id);

        return $rtn;
    }

    /**
     * duplicat a Stamp plan record with its relation to DisplayTargetStamp, DisplayTargetStampUB, DisplayTargetStampAO, StampPlanTargetClass, StampPlanStore.
     *
     * @param Int $id
     * @return Bool $rtn
     */
    public function addDuplicateProject($id)
    {
        $rtn = $this->repository->addDuplicateProject($id);

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
     * @return Bool $rtn
     */
    private function storeThumbnailUrlToS3(string $thumbnailUrl)
    {
        $rtn = false;
        $path = Upload::getCustomPath('stampThumbnail');
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
}
