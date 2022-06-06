<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\CouponPlanRequest;
use App\Http\Requests\FileRequest;
use App\Http\Requests\ImageRequest;
use App\Services\CouponPlanService;

class CouponPlanController extends Controller
{
    /**
     * @param CouponPlanService $service
     *
     * @return void
     */
    public function __construct(CouponPlanService $service)
    {
        $this->service = $service;
    }

    /**
     * list
     *
     * @return View
     */
    public function index(): View
    {
        $data = $this->service->all();
        return view('page.coupon.index', $data);
    }

    /**
     * create form
     *
     * @return View
     */
    public function create(): View
    {
        return $this->edit();
    }

    /**
     * edit form
     *
     * @param Int|Null $id
     * @return View
     */
    public function edit(int $id = null): View
    {
        $data = $this->service->get($id);
        return view('page.coupon.detail', $data);
    }

    /**
     * request store
     *
     * @param CouponPlanRequest $request
     * @return RedirectResponse
     */
    public function store(CouponPlanRequest $request): RedirectResponse
    {
        $rtn = $this->service->store();

        if ($rtn) {
            if ($request->has(config('searchQuery.param.copy'))) {
                return redirect()
                    ->route('coupon.edit', $rtn->id)
                    ->with('success', __('messages.success.create', ['name' => __('words.Coupon')]));
            } else {
                return redirect()
                    ->route('coupon.index')
                    ->with('success', __('messages.success.create', ['name' => __('words.Coupon')]));
            }
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.create', ['name' => __('words.Coupon')]));
        }
    }

    /**
     * request update
     *
     * @param CouponPlanRequest $request
     * @param Int $id
     * @return RedirectResponse
     */
    public function update(CouponPlanRequest $request, int $id): RedirectResponse
    {
        $rtn = $this->service->update($id);

        if ($rtn) {
            return redirect()
                ->route('coupon.index')
                ->with('success', __('messages.success.update', ['name' => __('words.Coupon')]));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.update', ['name' => __('words.Coupon')]));
        }
    }

    /**
     * request destroy
     *
     * @param Int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $rtn = $this->service->destroy($id);

        if ($rtn) {
            return redirect()
                ->route('coupon.index')
                ->with('success', __('messages.success.delete', ['name' => __('words.Coupon')]));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.delete', ['name' => __('words.Coupon')]));
        }
    }

    /**
     * upload images or csv
     *
     * @param FileRequest $request
     * @return JsonResponse []
     */
    public function upload(FileRequest $request): JsonResponse
    {
        $rtn = false;
        $url = '';
        $file = '';
        $fileType = '';
        $fileType = $request->get('fileType', '');

        switch ($fileType) {
            case \Globals::FILETYPE_IMAGE:
            case \Globals::FILETYPE_CSV:
                if ($request->has($fileType)) {
                    $fileType = $fileType;
                    $file = $request->file($fileType);
                }
                break;
        }

        $rtn = $this->service->upload($file, $fileType);

        if (!empty($rtn)) {
            $url = $rtn;
        }

        return response()->json([
            'success' => !!$rtn,
            'url' => $url
        ]);
    }

    /**
     * upload trumbowyg images
     *
     * @param ImageRequest $request
     * @return JsonResponse []
     */
    public function uploadTrumbowygImage(ImageRequest $request): JsonResponse
    {
        $rtn = false;
        $link = $this->service->upload($request->image, \Globals::FILETYPE_IMAGE);

        if (!empty($link)) {
            $rtn = true;
        }

        return response()->json([
            'success' => $rtn,
            'url' => $link
        ]);
    }
}
