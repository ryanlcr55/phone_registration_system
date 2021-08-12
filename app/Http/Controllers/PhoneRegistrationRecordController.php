<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\PhoneRegistrationRecordCreateRequest;
use App\Http\Responses\CustomResponse;
use App\Libs\Formatter;
use App\Services\PhoneRegisterService;
use App\Services\StoreCodeExistedService;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class PhoneRegistrationRecordController extends BaseController
{
    use DispatchesJobs, ValidatesRequests, Formatter;

    protected $model;

    public function __construct()
    {
    }

    public function create(PhoneRegistrationRecordCreateRequest $request)
    {
        /** @var PhoneRegisterService $phoneRegisterService */
        $phoneRegisterService = app()->make(PhoneRegisterService::class);
        $requestData = $request->validated();
        $formattedPhoneNum = $this->getFormattedPhoneNum($requestData['from']);
        $formattedStoreCode = $this->getFormattedStoreCodeInText($requestData['text']);
        throw_unless(resolve(StoreCodeExistedService::class)->checkStoreCodeExisted($formattedStoreCode),
            new CustomException('store code does not exist', CustomException::ERROR_CODE_STORE_DOSE_NOT_EXISTED));

        $phoneRegisterService->dispatchPhoneRegisterJob($formattedPhoneNum, $formattedStoreCode, Carbon::parse($requestData['time']));

        return new CustomResponse();
    }
}
