<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\BatchLogService;

class BatchLogController extends Controller
{
    /**
     * @param BatchLogService $service
     * @return void
     */
    public function __construct(BatchLogService $service)
    {
        $this->service = $service;
    }

    /**
     * This method displays all the batchLog.
     *
     * @return View
     */
    public function index(): View
    {
        $data = $this->service->allByDate();
        return view('page.batchLog.index', $data);
    }

    /**
     * This method displays the contents of a batchLog.
     *
     * @return RedirectJson
     */
    public function show()
    {
        $contents = $this->service->getContent();
        return response()->json([
            'contents' => $contents
        ]);
    }
}
