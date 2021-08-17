<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\PhoneRegistrationGetSuspectedRecordRequest;
use App\Http\Requests\PhoneRegistrationRecordCreateRequest;
use App\Http\Responses\CustomResponse;
use App\Libs\Formatter;
use App\Services\PhoneRegisterService;
use App\Services\StoreExistedService;
use App\Services\SuspectedTracingService;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class PhoneRegistrationRecordController extends BaseController
{
    use DispatchesJobs, ValidatesRequests, Formatter;

    public function create(PhoneRegistrationRecordCreateRequest $request)
    {
        /** @var PhoneRegisterService $phoneRegisterService */
        $phoneRegisterService = resolve(PhoneRegisterService::class);
        $requestData = $request->validated();
        $formattedPhoneNum = $this->getFormattedPhoneNum($requestData['from']);
        $formattedStoreCode = $this->getFormattedStoreCodeInText($requestData['text']);
        throw_unless(resolve(StoreExistedService::class)->checkStoreExisted($formattedStoreCode),
            new CustomException('store code does not exist', CustomException::ERROR_CODE_STORE_DOSE_NOT_EXISTED));

        $phoneRegisterService->dispatchPhoneRegisterJob($formattedPhoneNum, $formattedStoreCode,
            Carbon::parse($requestData['time']));

        return new CustomResponse();
    }

    public function getSuspectedRecode(PhoneRegistrationGetSuspectedRecordRequest $request)
    {
        /** @var SuspectedTracingService $suspectedTracingService */
        $suspectedTracingService = resolve(SuspectedTracingService::class);
        $rangeDays = config('tracking.get_registration_range_days');
        $numberPerPage = config('tracking.number_per_page');
        $requestData = $request->validated();

        $formattedPhoneNum = $this->getFormattedPhoneNum($requestData['from']);
        return $suspectedTracingService->getSuspectedRegistrations($formattedPhoneNum, Carbon::parse($requestData['time']),
            $rangeDays, $numberPerPage, $requestData['page']);
    }
}
