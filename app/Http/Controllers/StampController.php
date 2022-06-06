<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Upload;
use App\Http\Requests\StampPlanRequest;
use App\Services\StampPlanService;
use App\Models\StampPlan;
use App\Http\Requests\FileRequest;

class StampController extends Controller
{
    /**
     * @param StampPlanService $service
     * @return void
     */
    public function __construct(StampPlanService $service)
    {
        $this->service = $service;
    }

    /**
     * This method displys all the stamp.
     *
     * @return View
     */
    public function index(): View
    {
        $data = $this->service->all();
        return view('page.stamp.index', $data);
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
        return view('page.stamp.detail')->with($data);
    }

    /**
     * This method store stamp data.
     *
     * @param StampPlanRequest $request
     * @return RedirectResponse
     */
    public function store(StampPlanRequest $request): RedirectResponse
    {
        $rtn = $this->service->store();

        if ($rtn) {
            if ($request->has(config('searchQuery.param.copy'))) {
                return redirect()
                    ->route('stamp.edit', $rtn->id)
                    ->with('success', __('messages.success.create', ['name' => __('words.Stamp')]));
            } else {
                return redirect()
                    ->route('stamp.index')
                    ->with('success', __('messages.success.create', ['name' => __('words.Stamp')]));
            }
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.create', ['name' => __('words.Stamp')]));
        }
    }

    /**
     * This method update stamp data.
     *
     * @param StampPlanRequest $request
     * @param Int $id
     * @return RedirectResponse
     */
    public function update(StampPlanRequest $request, int $id): RedirectResponse
    {
        $rtn = $this->service->update($id);

        if ($rtn) {
            return redirect()
                ->route('stamp.index')
                ->with('success', __('messages.success.update', ['name' => __('words.Stamp')]));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.update', ['name' => __('words.Stamp')]));
        }
    }

    /**
     * This method delete stamp data.
     *
     * @param Int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $rtn = $this->service->destroy($id);

        if ($rtn) {
            return redirect()
                ->route('stamp.index')
                ->with('success', __('messages.success.delete', ['name' => __('words.Stamp')]));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.delete', ['name' => __('words.Stamp')]));
        }
    }

    /**
     * This method duplicate a data.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function storeDuplicate($id): RedirectResponse
    {
        $rtn = $this->service->addDuplicateProject($id);

        if ($rtn) {
            return redirect()
                ->route('stamp.index')
                 ->with('success', __('messages.success.create', ['name' => __('words.Stamp')]));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.create', ['name' => __('words.Stamp')]));
        }
    }

    /**
     * This method delete stamp data.
     *
     * @param Request $request
     * @return JsonResponse []
     */
    public function storeCsv(Request $request): JsonResponse
    {
        $file = $request->file('csvuploadUnionMember');
        $filename = $file->getClientOriginalName();
        $linkname = Upload::saveTemp($file);

            return response()->json([
                'filename' => $filename,
                'linkname' => $linkname,
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
