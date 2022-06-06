<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\FileRequest;
use App\Http\Requests\ImageRequest;
use App\Services\AdminService;
use App\Models\Admin;

class AdminController extends Controller
{
    /**
     * @param AdminService $service
     * @return void
     */
    public function __construct(AdminService $service)
    {
        $this->service = $service;
    }

    /**
     * Show user page
     *
     * @return View
     */
    public function index(): View
    {
        $data = $this->service->all();
        return view('page.admin.index', $data);
    }

    /**
     * admin create form
     *
     * @return View
     */
    public function create(): View
    {
        return $this->edit();
    }

    /**
     * admin edit form
     *
     * @param Int|Null $id
     * @return View
     */
    public function edit(int $id = null): View
    {
        $data = $this->service->get($id);
        return view('page.admin.detail', $data);
    }

    /**
     * save the admin
     *
     * @param AdminRequest $request
     * @return RedirectResponse
     */
    public function store(AdminRequest $request): RedirectResponse
    {
        $rtn = $this->service->store();

        if ($rtn) {
            return redirect()
                ->route('admin.index')
                ->with('success', __('messages.success.create', ['name' => __('words.Administrator')]));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.create', ['name' => __('words.Administrator')]));
        }
    }

    /**
     * update the admin
     *
     * @param ContentPlanRequest $request
     * @param Int $id
     * @return RedirectResponse
     */
    public function update(AdminRequest $request, int $id): RedirectResponse
    {
        $rtn = $this->service->update($id);

        if ($rtn) {
            return redirect()
                ->route('admin.index')
                ->with('success', __('messages.success.update', ['name' => __('words.Administrator')]));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.update', ['name' => __('words.Administrator')]));
        }
    }

    /**
     * delete the admin
     *
     * @param Int $id
     * @return RedirectResponse
     */
    public function destroy(int $id): RedirectResponse
    {
        $rtn = $this->service->destroy($id);

        if ($rtn) {
            return redirect()
                ->route('admin.index')
                ->with('success', __('messages.success.delete', ['name' => __('words.Administrator')]));
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.failed.delete', ['name' => __('words.Administrator')]));
        }
    }
}
