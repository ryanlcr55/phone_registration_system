<?php

namespace App\Services;


use App\Entities\PhoneRegistrationRecord;
use App\Entities\StoreCode;
use App\Exceptions\CustomException;
use App\Jobs\PhoneRegister;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class PhoneRegisterService
{
    protected $phoneRegistrationRecordModel;
    protected $storeCodeModel;

    public function __construct(
        PhoneRegistrationRecord $phoneRegistrationRecordModel,
        StoreCode $storeCodeModel
    ) {
        $this->phoneRegistrationRecordModel = $phoneRegistrationRecordModel;
        $this->storeCodeModel = $storeCodeModel;
    }

    public function dispatchPhoneRegisterJob(string $phoneNum, string $storeCode, string $registrationDatetime)
    {
        throw_unless($this->checkStoreCodeExist($storeCode),
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

    /**
     * @param  string  $storeCode
     * @return bool
     */
    public function checkStoreCodeExist(string $storeCode): bool
    {
        if (Redis::hexists($this->storeCodeModel::REDIS_KEY, $storeCode)) {
            return true;
        }

        $existedStoreCode =  $this->storeCodeModel::query()
            ->where('store_code', '=', $storeCode)
            ->first();
        if ($existedStoreCode) {
            Redis::hset($this->storeCodeModel::REDIS_KEY, $storeCode, $this->storeCodeModel::redisDataForm($existedStoreCode));
            return true;
        }

        return false;
    }

}
