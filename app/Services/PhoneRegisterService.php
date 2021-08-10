<?php

namespace App\Services;


use App\Entities\PhoneRegistrationRecord;
use App\Entities\StoreCode;
use App\Exceptions\CustomException;
use App\Jobs\PhoneRegister;
use Illuminate\Support\Facades\Log;

class PhoneRegisterService
{
    protected $phoneRegistrationRecordModel;

    public function __construct(
        PhoneRegistrationRecord $phoneRegistrationRecordModel,
    ) {
        $this->phoneRegistrationRecordModel = $phoneRegistrationRecordModel;
    }

    public function dispatchPhoneRegisterJob(string $phoneNum, string $storeCode, string $registrationDatetime)
    {
        throw_unless(resolve(StoreCodeExistedService::class)->checkStoreCodeExisted($storeCode),
            new CustomException('store code does not exist', CustomException::ERROR_CODE_STORE_DOSE_NOT_EXISTED));

        PhoneRegister::dispatch(...func_get_args())
            ->onQueue('create_phone_registration_recode');
    }

    public function register(string $phoneNum, string $storeCode, string $registrationDatetime)
    {
        try {
            $this->phoneRegistrationRecordModel::query()->create([
                'phone_num' => $phoneNum,
                'store_code' => $storeCode,
                'registration_datetime' => $registrationDatetime,
            ]);
        } catch (\Exception $e) {
            Log::error("[register fail] phone:$phoneNum, store code: $storeCode, time: $storeCode, reason: ".$e->getMessage());
        }
    }
}
