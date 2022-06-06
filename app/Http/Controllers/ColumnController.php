<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ContentPlanRequest;
use App\Http\Requests\FileRequest;
use App\Http\Requests\ImageRequest;
use App\Services\ContentPlanService;
use App\Models\ContentPlan;

class ColumnController extends Controller
{
    /**
     * @param ContentPlanService $service
     * @return void
     */
    public function __construct(ContentPlanService $service)
    {
        $this->service = $service;
    }

    /**
     * column list
     *
     * @return View
     */
    public function index(): View
    {
        $data = $this->service->all(ContentPlan::CONTENTTYPE_COLUMN);
        return view('page.column.index', $data);
    }

    /**
     * column create form
     *
     * @return View
     */
    public function create(): View
    {
        return $this->edit();
    }

    /**
     * column edit form
     *
     * @param int|null $id
     * @return View
     */
    public function edit(int $id = null): View
    {
        $data = $this->service->get($id);
        return view('page.column.detail', $data);
    }

    /**
     * column request store
     *
     * @param ContentPlanRequest $request
     * @return RedirectResponse
     */
    public function store(ContentPlanRequest $request): RedirectResponse
    {
        $rtn = $this->service->store(ContentPlan::CONTENTTYPE_COLUMN);

        if ($rtn) {
            if ($request->has(config('searchQuery.param.copy'))) {
                return redirect()
                    ->route('column.edit', $rtn->id)
                    ->with('success', __('messages.success.create', ['name' => __('words.Column')]));
            } else {
                return redirect()
                    ->route('column.index')
                    ->with('success', __('messages.success.create', ['name' => __('words.Column')]));
            }
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.create', ['name' => __('words.Column')]));
        }
    }

    /**
     * column request update
     *
     * @param ContentPlanRequest $request
     * @param Int $id
     * @return RedirectResponse
     */
    public function update(ContentPlanRequest $request, int $id): RedirectResponse
    {
        $rtn = $this->service->update($id, ContentPlan::CONTENTTYPE_COLUMN);

        if ($rtn) {
            return redirect()
                ->route('column.index')
                ->with('success', __('messages.success.update', ['name' => __('words.Column')]));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.update', ['name' => __('words.Column')]));
        }
    }

    /**
     * column request destroy
     *
     * @param Int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $rtn = $this->service->destroy($id);

        if ($rtn) {
            return redirect()
                ->route('column.index')
                ->with('success', __('messages.success.delete', ['name' => __('words.Column')]));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.delete', ['name' => __('words.Column')]));
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
        $link = $this->service->upload($request->image, 'image');

        if (!empty($link)) {
            $rtn = true;
        }

        return response()->json([
            'success' => $rtn,
            'url' => $link
        ]);
    }
}
