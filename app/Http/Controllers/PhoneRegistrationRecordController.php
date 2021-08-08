<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneRegistrationRecordCreateRequest;
use App\Services\PhoneRegisterService;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class PhoneRegistrationRecordController extends BaseController
{
    use DispatchesJobs, ValidatesRequests;

    protected $model;

    public function __construct()
    {
    }

    public function create(PhoneRegistrationRecordCreateRequest $request)
    {
        /** @var PhoneRegisterService $phoneRegisterService */
        $phoneRegisterService = app()->make(PhoneRegisterService::class);
        $requestData = $request->validated();
        $phoneRegisterService->dispatchPhoneRegisterJob($requestData['phone_num'], $requestData['store_code'], Carbon::now());

        return [];
    }
}
