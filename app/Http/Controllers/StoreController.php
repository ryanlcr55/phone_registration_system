<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCreateRequest;
use App\Http\Responses\CustomResponse;
use App\Services\StoreCreateService;
use App\Services\StoreExistedService;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class StoreController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    /** @var StoreCreateService $createStoreService */
    protected $createService;
    /** @var StoreExistedService $storeExistedService */
    protected $storeExistedService;

    public function __construct(StoreCreateService $createService, StoreExistedService $storeExistedService)
    {
        $this->createService = $createService;
        $this->storeExistedService = $storeExistedService;
    }

    public function create(StoreCreateRequest $request)
    {
        $requestData = $request->validated();
        $store = $this->createService->createStore($requestData['store_name'], $requestData['lat'], $requestData['lon']);
        $this->storeExistedService->setStoreToRedis($store);

        return new CustomResponse($store);
    }
}
