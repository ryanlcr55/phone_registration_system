<?php

namespace App\Http\Controllers;

use App\Contracts\LocationContract;
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
        if (empty($requestData['lat']) && empty($requestData['lon']) && !empty($requestData['address'])) {
            /** @var LocationContract $locationService */
            $locationService = resolve(LocationContract::class);
            $locationInfo = $locationService->getLatLon($requestData['address']);
            [$lan, $lon] = [$locationInfo['lat'], $locationInfo['lon']];
        } else {
            [$lan, $lon] = [$requestData['lat'], $requestData['lon']];
        }

        $store = $this->createService->createStore($requestData['store_name'], $lan, $lon);
        $this->storeExistedService->setStoreToRedis($store);

        return new CustomResponse($store);
    }
}
