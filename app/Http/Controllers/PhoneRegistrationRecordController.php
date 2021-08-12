<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneRegistrationRecordCreateRequest;
use App\Http\Responses\CustomResponse;
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
        $phoneRegisterService->dispatchPhoneRegisterJob($requestData['from'], $requestData['text'], $requestData['time']);

        return new CustomResponse();
    }
}
