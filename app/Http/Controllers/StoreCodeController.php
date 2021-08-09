<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCodeCreateRequest;
use App\Http\Responses\CustomResponse;
use App\Services\CreateStoreCodeService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class StoreCodeController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected $model;

    public function __constrt()
    {
    }

    public function create(StoreCodeCreateRequest $request)
    {
        /** @var CreateStoreCodeService $createStoreCodeService */
        $createStoreCodeService = app()->make(CreateStoreCodeService::class);
        $requestData = $request->validated();

        return new CustomResponse($createStoreCodeService->createStoreCode($requestData['store_name'], $requestData['lan'], $requestData['lon']));
    }
}
