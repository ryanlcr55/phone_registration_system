<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCodeCreateRequest;
use App\Http\Responses\CustomResponse;
use App\Services\StoreCodeCreateService;
use App\Services\StoreCodeExistedService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class StoreCodeController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /** @var StoreCodeCreateService $createStoreCodeService */
    protected $createStoreCodeService;
    /** @var StoreCodeExistedService $storeCodeExistedService */
    protected $storeCodeExistedService;

    public function __construct(StoreCodeCreateService $createStoreCodeService, StoreCodeExistedService $storeCodeExistedService)
    {
        $this->createStoreCodeService = $createStoreCodeService;
        $this->storeCodeExistedService = $storeCodeExistedService;
    }

    public function create(StoreCodeCreateRequest $request)
    {
        /** @var StoreCodeCreateService $createStoreCodeService */
        $createStoreCodeService = app()->make(StoreCodeCreateService::class);
        $requestData = $request->validated();
        $storeCode = $createStoreCodeService->createStoreCode($requestData['store_name'], $requestData['lat'], $requestData['lon']);
        $this->storeCodeExistedService->setStoreCodeToRedis($storeCode);

        return new CustomResponse($storeCode);
    }
}
