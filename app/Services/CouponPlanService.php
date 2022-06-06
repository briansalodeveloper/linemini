<?php

namespace App\Services;

use DB;
use Carbon\Carbon;
use App\Helpers\Trumbowyg;
use App\Helpers\Upload;
use Illuminate\Http\UploadedFile;
use App\Interfaces\Coupon\CouponPlanProductRepositoryInterface;
use App\Interfaces\CouponPlanRepositoryInterface;
use App\Interfaces\Coupon\CouponPlanStoreRepositoryInterface;
use App\Interfaces\Coupon\CouponPlanTargetClassRepositoryInterface;
use App\Interfaces\Coupon\DisplayTargetCouponRepositoryInterface;
use App\Interfaces\Coupon\DisplayTargetCouponAORepositoryInterface;
use App\Interfaces\Coupon\DisplayTargetCouponUBRepositoryInterface;
use App\Models\CouponPlan;
use App\Traits\Rules\CsvMemberCodeRuleTrait;
use App\Traits\Rules\CsvSpecifiedProdCategoryRuleTrait;
use App\Traits\Rules\CsvSpecifiedProductRuleTrait;
use App\Traits\Services\DisplayOrSendTargetServiceTrait;

class CouponPlanService extends MainService
{
    use CsvMemberCodeRuleTrait;
    use DisplayOrSendTargetServiceTrait;
    use CsvSpecifiedProdCategoryRuleTrait;
    use CsvSpecifiedProductRuleTrait;

    /*======================================================================
     * PROPERTIES
     *======================================================================*/

    /**
     * @var DisplayTargetCouponRepositoryInterface
     */
    private $displayTargetCouponRepository;

    /**
     * @var DisplayTargetCouponAORepositoryInterface
     */
    private $displayTargetCouponAORepository;

    /**
     * @var DisplayTargetCouponUBRepositoryInterface
     */
    private $displayTargetCouponUBRepository;

    /**
     * @var CouponPlanStoreRepositoryInterface
     */
    private $couponPlanStoreRepository;

    /**
     * @var CouponPlanProductRepositoryInterface
     */
    private $couponPlanProductRepository;

    /**
     * @var CouponPlanTargetClassRepositoryInterface
     */
    private $couponPlanTargetClassRepository;

    /*======================================================================
     * CONSTRUCTOR
     *======================================================================*/

    /**
     * @param CouponPlanRepositoryInterface $repository
     * @param DisplayTargetCouponRepositoryInterface $displayTargetCouponRepository
     * @param DisplayTargetCouponAORepositoryInterface $displayTargetCouponAORepository
     * @param DisplayTargetCouponUBRepositoryInterface $displayTargetCouponUBRepository
     * @param CouponPlanStoreRepositoryInterface $couponPlanStoreRepository
     * @param CouponPlanProductRepositoryInterface $couponPlanProductRepository
     */
    public function __construct(
        CouponPlanRepositoryInterface $repository,
        DisplayTargetCouponRepositoryInterface $displayTargetCouponRepository,
        DisplayTargetCouponAORepositoryInterface $displayTargetCouponAORepository,
        DisplayTargetCouponUBRepositoryInterface $displayTargetCouponUBRepository,
        CouponPlanStoreRepositoryInterface $couponPlanStoreRepository,
        CouponPlanProductRepositoryInterface $couponPlanProductRepository,
        CouponPlanTargetClassRepositoryInterface $couponPlanTargetClassRepository
    ) {
        $this->repository = $repository;
        $this->displayTargetCouponRepository = $displayTargetCouponRepository;
        $this->displayTargetCouponAORepository = $displayTargetCouponAORepository;
        $this->displayTargetCouponUBRepository = $displayTargetCouponUBRepository;
        $this->couponPlanStoreRepository = $couponPlanStoreRepository;
        $this->couponPlanProductRepository = $couponPlanProductRepository;
        $this->couponPlanTargetClassRepository = $couponPlanTargetClassRepository;
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
     * fetch all records
     *
     * @param Int|Null $id
     * @return Array $rtn
     */
    public function get(int $id = null): array
    {
        $data = $this->repository->acquireWith($id, 'stores');

        $rtn = [
            'data' => $data,
            'listAo' => config('const.listAo'),
            'listUb' => config('const.listUb'),
            'listCouponType' => CouponPlan::COUPONTYPE_LIST,
            'listDisplayTargetOptions' => CouponPlan::DSPTARGET_OPTIONS,
            'listHighLevelDisplayOptions' => CouponPlan::HIGHLVLDISPLAY_OPTIONS,
            'listRegLevelDisplayOptions' => CouponPlan::REGULARLVLDISPLAY_OPTIONS,
            'listAutoEntryOptions' => CouponPlan::AUTOENTRY_OPTIONS,
            'listPoinGrantFlgOptions' => CouponPlan::POINTGRANTFLG_OPTIONS,
            'listPublicationOptions' => CouponPlan::PUBLICATION_OPTIONS[$data->isNotEmpty],
            'listUseFlagOptions' => CouponPlan::USEFLG_OPTIONS,
            'listIncreaseFlagOptions' => CouponPlan::INCREASEFLG_OPTIONS,
            'listStore' => config('const.listCouponStore'),
            'listCsvAcceptedExtension' => \Globals::CSV_ACCEPTEDEXTENSION,
            'listImageAcceptedExtension' => \Globals::IMG_ACCEPTEDEXTENSION,
            'umList' => $this->displayTargetCouponRepository->acquireAllKumicdByCoupon($id),
            'listTargetProdOptions' => CouponPlan::TARGETPROD_OPTIONS,
            'listStoreFlgOptions' => CouponPlan::STOREFLG_OPTIONS
        ];

        return $rtn;
    }

    /**
     * store a record
     *
     * @return Bool|CouponPlan $rtn
     */
    public function store()
    {
        $rtn = false;
        DB::beginTransaction();
        try {
            $copyFrom = null;

            if (request()->get(config('searchQuery.param.copy'), config('searchQuery.value.copyNo'))) {
                $copyFrom = $this->repository->NTCacquire(request()->get('couponPlanId'));

                $data = [
                    'cuponName' => $copyFrom->cuponName . ' - ' . __('words.Copy'),
                    'cuponType' => $copyFrom->cuponType,
                    'priorityDisplayFlg' => $copyFrom->priorityDisplayFlg,
                    'cuponDisplayFlg' => $copyFrom->cuponDisplayFlg,
                    'startDate' => $copyFrom->startDate,
                    'startTime' => $copyFrom->startTime,
                    'endDate' => $copyFrom->endDate,
                    'endTime' => $copyFrom->endTime,
                    'useFlg' => $copyFrom->useFlg,
                    'useCount' => $copyFrom->useCount,
                    'useTime' => $copyFrom->useTime,
                    'pointGrantFlg' => $copyFrom->pointGrantFlg,
                    'pointGrantPurchasesPrice' => $copyFrom->pointGrantPurchasesPrice,
                    'pointGrantPurchasesCount' => $copyFrom->pointGrantPurchasesCount,
                    'increaseFlg' => $copyFrom->increaseFlg,
                    'grantPoint' => $copyFrom->grantPoint,
                    'cuponImg' => $copyFrom->cuponImg,
                    'cuponText' => $copyFrom->cuponText,
                    'autoEntryFlg' => $copyFrom->autoEntryFlg,
                    'productFlg' => $copyFrom->productFlg,
                    'storeFlg' => $copyFrom->storeFlg,
                    'grantPointSub' => $copyFrom->grantPointSub,
                    'grantCuponPlanId' => $copyFrom->grantCuponPlanId
                ];
            } else {
                $data = [
                    'cuponName' => request()->get('cuponName'),
                    'cuponType' => request()->get('cuponType'),
                    'priorityDisplayFlg' => request()->get('priorityDisplayFlg'),
                    'cuponDisplayFlg' => request()->get('cuponDisplayFlg'),
                    'startDate' => request()->get('startDateTime'),
                    'startTime' => request()->get('startDateTime'),
                    'endDate' => request()->get('endDateTime'),
                    'endTime' => request()->get('endDateTime'),
                    'useFlg' => request()->get('useFlg'),
                    'useCount' => request()->get('useCount'),
                    'useTime' => request()->get('useTime'),
                    'pointGrantFlg' => request()->get('pointGrantFlg'),
                    'pointGrantPurchasesPrice' => request()->get('pointGrantPurchasesPrice'),
                    'pointGrantPurchasesCount' => request()->get('pointGrantPurchasesCount'),
                    'increaseFlg' => request()->get('increaseFlg'),
                    'grantPoint' => request()->get('grantPoint'),
                    'cuponImg' => request()->get('cuponImg'),
                    'cuponText' => request()->get('cuponText') ? request()->get('cuponText', '') : '',
                    'autoEntryFlg' => request()->get('autoEntryFlg'),
                    'productFlg' => request()->get('productFlg'),
                    'storeFlg' => request()->get('storeFlg'),
                    'grantPointSub' => request()->get('grantPointSub'),
                    'grantCuponPlanId' => request()->get('grantCuponPlanId')
                ];
            }

            $couponPlan = $this->repository->NTCadd($data);

            if ($couponPlan) {
                $thumbnail = $this->storeThumbnailUrlToS3(request()->get('cuponImg'));
                $couponText = $this->storeContentsUrlToS3(request()->get('cuponText'));

                if ($thumbnail) {
                    $couponPlan->cuponImg = $thumbnail;
                }

                if ($couponText) {
                    $couponPlan->cuponText = $couponText;
                }

                if ($thumbnail || $couponText) {
                    $rtn = $this->repository->NTCadjust($couponPlan->id, [
                        'cuponImg' => $couponPlan->cuponImg,
                        'cuponText' => $couponPlan->cuponText
                    ]);
                } else {
                    $rtn = $couponPlan;
                }

                if (request()->get(config('searchQuery.param.copy'), config('searchQuery.value.copyNo'))) {
                    $unionMemberIds = $copyFrom->displayTargetCouponIdList;
                    $aoIds = $copyFrom->displayTargetCouponAoAffiliationOfficeIdList;
                    $ubIds = $copyFrom->displayTargetCouponUbUtilizationBusinessIdList;
                    $storeIds = $copyFrom->couponPlanStoreIdList;
                    $cuponPlanProducts = $copyFrom->couponPlanProductList;
                    $cuponPlanCategories = $copyFrom->couponPlanTargetClassList;
                } else {
                    $unionMemberIds = static::getValidUnionMemberCodeFromExcelUrl(request()->get('unionMemberCsv'));
                    $aoIds = request()->get('affiliationOffice') ? request()->get('affiliationOffice', []) : [];
                    $ubIds = request()->get('utilizationBusiness') ? request()->get('utilizationBusiness', []) : [];
                    $storeIds = request()->get('stores') ? request()->get('stores', []) : [];
                    $specifiedProdCodeData = static::getValidProductCodeFromExcelUrl(request()->get('specifiedProdCodeCsv'), false);
                    $specifiedProdCategoryData = static::getValidProductCategoryFromExcelUrl(request()->get('prodCategoryCsv'));
                    $cuponPlanProducts = [];
                    $cuponPlanCategories = [];

                    if (!empty(request()->get('specifiedProdCodeCsv'))) {
                        foreach ($specifiedProdCodeData as $data) {
                            $standardNameKanji = preg_replace('/\s/u', ' ', $data['standardNameKanji']);
                            $productNameKanji = preg_replace('/\s/u', ' ', $data['productNameKanji']);

                            $cuponPlanProducts[] = [
                                'productName' => trim($productNameKanji) . ' ' . trim($standardNameKanji),
                                'productJancode' => $data['productCode'],
                                'productImg' => $couponPlan->cuponImg,
                                'productText' => ''
                            ];
                        }

                        $cuponPlanProducts = collect($cuponPlanProducts)->unique('productJancode')->toArray();
                    } elseif (!empty(request()->get('prodCategoryCsv'))) {


                        foreach ($specifiedProdCategoryData as $column) {
                            $cuponPlanCategories[] = [
                                'departmentCode' => $column[0],
                                'majorClassificationCode' => $column[0],
                                'middleClassificationCode' => $column[1],
                                'subclassCode' => $column[2]
                            ];
                        }
                    }
                }

                $this->storeUpdateMemberCode(
                    $unionMemberIds,
                    'cuponPlanId',
                    $couponPlan,
                    $couponPlan->displayTargetCoupon,
                    $couponPlan->cuponDisplayFlg,
                    CouponPlan::DSPTARGET_UNIONMEMBER,
                    'displayTargetCouponIdList',
                    $this->displayTargetCouponRepository
                );
                $this->storeUpdateAo(
                    $aoIds,
                    'cuponPlanId',
                    $couponPlan,
                    $couponPlan->displayTargetCouponAO,
                    $couponPlan->cuponDisplayFlg,
                    CouponPlan::DSPTARGET_AO,
                    'displayTargetCouponAoAffiliationOfficeIdList',
                    $this->displayTargetCouponAORepository
                );
                $this->storeUpdateUb(
                    $ubIds,
                    'cuponPlanId',
                    $couponPlan,
                    $couponPlan->displayTargetCouponUB,
                    $couponPlan->cuponDisplayFlg,
                    CouponPlan::DSPTARGET_UB,
                    'displayTargetCouponUbUtilizationBusinessIdList',
                    $this->displayTargetCouponUBRepository
                );
                $this->storeUpdateTargetCommon(
                    $storeIds,
                    'cuponPlanId',
                    $couponPlan,
                    $couponPlan->stores,
                    'storeId',
                    null,
                    null,
                    'couponPlanStoreIdList',
                    $this->couponPlanStoreRepository
                );

                $this->storeUpdateTargetMultipleColsCommon(
                    $cuponPlanProducts,
                    ['productJancode'],
                    'cuponPlanId',
                    $couponPlan,
                    $couponPlan->products,
                    null,
                    null,
                    'couponPlanProductList',
                    $this->couponPlanProductRepository
                );

                $this->storeUpdateTargetMultipleColsCommon(
                    $cuponPlanCategories,
                    ['departmentCode','middleClassificationCode','subclassCode'],
                    'cuponPlanId',
                    $couponPlan,
                    $couponPlan->couponPlanTargetClass,
                    null,
                    null,
                    'couponPlanTargetClassList',
                    $this->couponPlanTargetClassRepository
                );
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
     * @return Bool|CouponPlan $rtn
     */
    public function update(int $id)
    {
        $rtn = false;

        DB::beginTransaction();
        try {
            $data = [
                'cuponName' => request()->get('cuponName'),
                'cuponType' => request()->get('cuponType'),
                'priorityDisplayFlg' => request()->get('priorityDisplayFlg'),
                'cuponDisplayFlg' => request()->get('cuponDisplayFlg'),
                'startDate' => request()->get('startDateTime'),
                'startTime' => request()->get('startDateTime'),
                'endDate' => request()->get('endDateTime'),
                'endTime' => request()->get('endDateTime'),
                'useFlg' => request()->get('useFlg'),
                'useCount' => request()->get('useCount'),
                'useTime' => request()->get('useTime'),
                'pointGrantFlg' => request()->get('pointGrantFlg'),
                'pointGrantPurchasesPrice' => request()->get('pointGrantPurchasesPrice'),
                'pointGrantPurchasesCount' => request()->get('pointGrantPurchasesCount'),
                'increaseFlg' => request()->get('increaseFlg'),
                'grantPoint' => request()->get('grantPoint'),
                'cuponImg' => request()->get('cuponImg') ? request()->get('cuponImg', '') : '',
                'cuponText' => request()->get('cuponText') ? request()->get('cuponText', '') : '',
                'productFlg' => request()->get('productFlg'),
                'storeFlg' =>  request()->get('storeFlg'),
                'grantPointSub' => request()->get('grantPointSub'),
                'grantCuponPlanId' => request()->get('grantCuponPlanId')
            ];

            $couponPlan = $this->repository->NTCadjust($id, $data);
            if ($couponPlan) {
                $thumbnail = $this->storeThumbnailUrlToS3(request()->get('cuponImg'));
                $cuponText = $this->storeContentsUrlToS3(request()->get('cuponText'));

                if ($thumbnail) {
                    $couponPlan->cuponImg = $thumbnail;
                }

                if ($cuponText) {
                    $couponPlan->cuponText = $cuponText;
                }

                if ($thumbnail || $cuponText) {
                    $rtn = $this->repository->NTCadjust($couponPlan->id, [
                        'cuponImg' => $couponPlan->cuponImg,
                        'cuponText' => $couponPlan->cuponText,
                    ]);
                } else {
                    $rtn = $couponPlan;
                }

                $unionMemberIds = static::getValidUnionMemberCodeFromExcelUrl(request()->get('unionMemberCsv'));
                $aoIds = request()->get('affiliationOffice') ? request()->get('affiliationOffice', []) : [];
                $ubIds = request()->get('utilizationBusiness') ? request()->get('utilizationBusiness', []) : [];
                $storeIds = request()->get('stores');
                $cuponPlanProducts = [];
                $cuponPlanCategories = [];

                if (is_null(request()->get('unionMemberCsv')) && request()->get('cuponDisplayFlg') == CouponPlan::DSPTARGET_UNIONMEMBER) {
                    $unionMemberIds = $couponPlan->displayTargetCouponIdList;
                }

                if (!empty(request()->get('specifiedProdCodeCsv'))) {
                    $specifiedProdCodeData = static::getValidProductCodeFromExcelUrl(request()->get('specifiedProdCodeCsv'), false);

                    foreach ($specifiedProdCodeData as $data) {
                        $standardNameKanji = preg_replace('/\s/u', ' ', $data['standardNameKanji']);
                        $productNameKanji = preg_replace('/\s/u', ' ', $data['productNameKanji']);

                        $cuponPlanProducts[] = [
                            'productName' => trim($productNameKanji) . ' ' . trim($standardNameKanji),
                            'productJancode' => $data['productCode'],
                            'productImg' => $couponPlan->cuponImg,
                            'productText' => ''
                        ];
                    }

                    $cuponPlanProducts = collect($cuponPlanProducts)->unique('productJancode')->toArray();
                } elseif (is_null(request()->get('specifiedProdCodeCsv')) && request()->get('productFlg') == CouponPlan::CODE_PRODUCTDESIGNATION) {
                    $cuponPlanProducts = $couponPlan->couponPlanProductList;
                }

                if (!empty(request()->get('prodCategoryCsv'))) {
                    $specifiedProdCategoryData = static::getValidProductCategoryFromExcelUrl(request()->get('prodCategoryCsv'));

                    foreach ($specifiedProdCategoryData as $column) {
                        $cuponPlanCategories[] = [
                            'departmentCode' => $column[0],
                            'majorClassificationCode' => $column[0],
                            'middleClassificationCode' => $column[1],
                            'subclassCode' => $column[2]
                        ];
                    }
                } elseif (is_null(request()->get('prodCategoryCsv')) && request()->get('productFlg') == CouponPlan::CODE_CATEGORYDESIGNATION) {
                    $cuponPlanCategories = $couponPlan->couponPlanTargetClassList;
                }

                $this->storeUpdateMemberCode(
                    $unionMemberIds,
                    'cuponPlanId',
                    $couponPlan,
                    $couponPlan->displayTargetCoupon,
                    $couponPlan->cuponDisplayFlg,
                    CouponPlan::DSPTARGET_UNIONMEMBER,
                    'displayTargetCouponIdList',
                    $this->displayTargetCouponRepository
                );
                $this->storeUpdateAo(
                    $aoIds,
                    'cuponPlanId',
                    $couponPlan,
                    $couponPlan->displayTargetCouponAO,
                    $couponPlan->cuponDisplayFlg,
                    CouponPlan::DSPTARGET_AO,
                    'displayTargetCouponAoAffiliationOfficeIdList',
                    $this->displayTargetCouponAORepository
                );
                $this->storeUpdateUb(
                    $ubIds,
                    'cuponPlanId',
                    $couponPlan,
                    $couponPlan->displayTargetCouponUB,
                    $couponPlan->cuponDisplayFlg,
                    CouponPlan::DSPTARGET_UB,
                    'displayTargetCouponUbUtilizationBusinessIdList',
                    $this->displayTargetCouponUBRepository
                );
                $this->storeUpdateTargetCommon(
                    $storeIds,
                    'cuponPlanId',
                    $couponPlan,
                    $couponPlan->stores,
                    'storeId',
                    null,
                    null,
                    'couponPlanStoreIdList',
                    $this->couponPlanStoreRepository
                );
                $this->storeUpdateTargetMultipleColsCommon(
                    $cuponPlanProducts,
                    ['productJancode'],
                    'cuponPlanId',
                    $couponPlan,
                    $couponPlan->products,
                    null,
                    null,
                    'couponPlanProductList',
                    $this->couponPlanProductRepository
                );
                $this->storeUpdateTargetMultipleColsCommon(
                    $cuponPlanCategories,
                    ['departmentCode','middleClassificationCode','subclassCode'],
                    'cuponPlanId',
                    $couponPlan,
                    $couponPlan->couponPlanTargetClass,
                    null,
                    null,
                    'couponPlanTargetClassList',
                    $this->couponPlanTargetClassRepository
                );
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
     */
    public function destroy(int $id)
    {
        $rtn = $this->repository->annul($id);

        return $rtn;
    }

    /**
     * upload a file (image/csv)
     *
     * @param UploadedFile $file
     * @param String $fileType
     * @return Bool/String $rtn
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
     * @return Bool $rtn
     */
    private function storeThumbnailUrlToS3(string $thumbnailUrl)
    {
        $rtn = false;
        $path = Upload::getCustomPath('couponThumbnail');
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
     * @return Bool $rtn
     */
    private function storeContentsUrlToS3($contents)
    {
        $rtn = false;

        if ($contents) {
            $rtn = Trumbowyg::moveTemporaryFiles(
                $contents,
                Upload::getCustomPath('couponImageContent'),
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
