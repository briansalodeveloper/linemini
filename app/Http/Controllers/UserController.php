<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\UnionLineService;

class UserController extends Controller
{
    /**
     * @param UnionLineService $service
     * @return void
     */
    public function __construct(UnionLineService $service)
    {
        $this->service = $service;
    }

    /**
     * display the index page.
     *
     * @return View
     */
    public function index(): View
    {
        $data = $this->service->allByFilter();
        return view('page.user.index', $data);
    }

    /**
     * Update the incident column in UnionlineId table
     *
     * @return RedirectResponse
     */
    public function updateIncident(): RedirectResponse
    {
        $rtn = $this->service->updateIncident();

        if ($rtn) {
            return redirect()
                ->back()
                ->with('success', __('messages.success.update', ['name' => __('words.CardNumber')]));
        } else {
            return redirect()
                ->back()
                ->with('error', __('messages.failed.update', ['name' => __('words.CardNumber')]));
        }
    }
}
