<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\FileRequest;
use App\Http\Requests\FlyerPlanRequest;
use App\Services\FlyerPlanService;

class FlyerController extends Controller
{
    /**
     * @param FlyerPlanService $service
     * @return void
     */
    public function __construct(FlyerPlanService $service)
    {
        $this->service = $service;
    }

    /**
     * This method displys all the flyers.
     *
     * @return View
     */
    public function index(): View
    {
        $data = $this->service->all();
        return view('page.flyer.index', $data);
    }

    /**
     * This method display a create form.
     *
     * @return View
     */
    public function create(): View
    {
        return $this->edit();
    }

    /**
     * This method display a edit form.
     *
     * @param Int/Null $id
     * @return View
     */
    public function edit(int $id = null): View
    {
        $data = $this->service->get($id);
        return view('page.flyer.detail', $data);
    }

    /**
     * This method store flyer data.
     *
     * @param FlyerPlanRequest $request
     * @return RedirectResponse
     */
    public function store(FlyerPlanRequest $request): RedirectResponse
    {
        $rtn = $this->service->store();

        if ($rtn) {
            if ($request->has(config('searchQuery.param.copy'))) {
                return redirect()
                    ->route('flyer.edit', $rtn->id)
                    ->with('success', __('messages.success.create', ['name' => __('words.Flyer')]));
            } else {
                return redirect()
                    ->route('flyer.index')
                    ->with('success', __('messages.success.create', ['name' => __('words.Flyer')]));
            }
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.create', ['name' => __('words.Flyer')]));
        }
    }

    /**
     * This method update flyer data.
     *
     * @param FlyerPlanRequest $request
     * @param Int $id
     * @return RedirectResponse
     */
    public function update(FlyerPlanRequest $request, int $id): RedirectResponse
    {
        $rtn = $this->service->update($id);

        if ($rtn) {
            return redirect()
                ->route('flyer.index')
                ->with('success', __('messages.success.update', ['name' => __('words.Flyer')]));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.update', ['name' => __('words.Flyer')]));
        }
    }

    /**
     * This method delete flyer data.
     *
     * @param Int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $rtn = $this->service->destroy($id);

        if ($rtn) {
            return redirect()
                ->route('flyer.index')
                ->with('success', __('messages.success.delete', ['name' => __('words.Flyer')]));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.delete', ['name' => __('words.Flyer')]));
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
}
