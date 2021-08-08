<?php

namespace App\Services;


use App\Entities\PhoneRegistrationRecord;
use App\Entities\StoreCode;
use App\Jobs\PhoneRegister;
use Illuminate\Support\Facades\Log;

class PhoneRegisterService
{
    protected $phoneRegistrationRecordEntity;
    protected $storeCodeEntity;

    public function __construct(
        PhoneRegistrationRecord $phoneRegistrationRecordEntity,
        StoreCode $storeCodeEntity
    ) {
        $this->phoneRegistrationRecordEntity = $phoneRegistrationRecordEntity;
        $this->storeCodeEntity = $storeCodeEntity;
    }

    public function dispatchPhoneRegisterJob(string $phoneNum, string $storeCode, string $registrationDatetime)
    {
        PhoneRegister::dispatch(...func_get_args())
            ->onConnection('database')
            ->onQueue('create_phone_registration_recode');
    }

    public function register(string $phoneNum, string $storeCode, string $registrationDatetime)
    {
        try {
            throw_unless($this->checkStoreCodeExist($storeCode),
                new \Exception('store code does not exist'));

            $this->phoneRegistrationRecordEntity::query()->create([
                'phone_num' => $phoneNum,
                'store_code' => $storeCode,
                'registration_datetime' => $registrationDatetime,
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    /**
     * @param  string  $storeCode
     * @return bool
     */
    public function checkStoreCodeExist(string  $storeCode) : bool
    {
        return $this->storeCodeEntity::query()
            ->where('store_code', '=', $storeCode)
            ->exists();
    }
}
