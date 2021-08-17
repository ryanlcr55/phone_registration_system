<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\PhoneRegistrationRecordCreateRequest;
use App\Http\Responses\CustomResponse;
use App\Libs\Formatter;
use App\Services\PhoneRegisterService;
use App\Services\StoreExistedService;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class PhoneRegistrationRecordController extends BaseController
{
    use DispatchesJobs, ValidatesRequests, Formatter;

    /** @var PhoneRegisterService $phoneRegisterService */
    protected $phoneRegisterService;

    public function __construct(PhoneRegisterService $phoneRegisterService)
    {
        $this->phoneRegisterService = $phoneRegisterService;
    }

    public function create(PhoneRegistrationRecordCreateRequest $request)
    {
        $requestData = $request->validated();
        $formattedPhoneNum = $this->getFormattedPhoneNum($requestData['from']);
        $formattedStoreCode = $this->getFormattedStoreCodeInText($requestData['text']);
        throw_unless(resolve(StoreExistedService::class)->checkStoreExisted($formattedStoreCode),
            new CustomException('store code does not exist', CustomException::ERROR_CODE_STORE_DOSE_NOT_EXISTED));

        $this->phoneRegisterService->dispatchPhoneRegisterJob($formattedPhoneNum, $formattedStoreCode, Carbon::parse($requestData['time']));

        return new CustomResponse();
    }
}
