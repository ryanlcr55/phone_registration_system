<?php

namespace App\Services;


use App\Entities\PhoneRegistrationRecord;
use App\Exceptions\CustomException;
use App\Jobs\PhoneRegister;
use Carbon\Carbon;
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
        $formattedPhoneNum = $this->getFormattedPhoneNum($phoneNum);
        $formattedStoreCode = $this->getFormattedStoreCodeInText($storeCode);

        throw_unless(resolve(StoreCodeExistedService::class)->checkStoreCodeExisted($formattedStoreCode),
            new CustomException('store code does not exist', CustomException::ERROR_CODE_STORE_DOSE_NOT_EXISTED));

        PhoneRegister::dispatch($formattedPhoneNum, $formattedStoreCode, $registrationDatetime, Carbon::parse($registrationDatetime))
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
     * Get Phone number in three type of string. ex:(+886)912345678, 0912345678, 0912-345-678
     * @param  string  $phoneNum
     * @return string
     * @throws CustomException
     */
    public function getFormattedPhoneNum(string $phoneNum): string
    {
        if (preg_match('/^\(\+886\)(\d{9})(\d{3})(\d{4})$/', $phoneNum, $matches)) {
            // (+886)912345678 type
            return '0'.$matches[2];
        } elseif (preg_match('/^(09)(\d{8})$/', $phoneNum, $matches)) {
            // 0912345678 type
            return $matches[0];
        } elseif (preg_match('/^(09)(\d{2})\-(\d{3})\-(\d{3})$/', $phoneNum, $matches)) {
            // 0912-345-678
            return $matches[1].$matches[2].$matches[3].$matches[4];
        }

        throw new CustomException('Phone number format is invalid',
            CustomException::ERROR_CODE_PHONE_NUMBER_FORMAT_IS_INVALID);
    }

    /**
     * Get the first set of 15 consecutive digits
     * @param  string  $text
     * @return string
     * @throws CustomException
     */
    public function getFormattedStoreCodeInText(string $text): string
    {
        // clear space and add '.' to match preg_replace's pattern
        $textWithoutSpace = preg_replace('/\s+/', '', $text).',';
        if (preg_match("/[^0-9]]*([\d]{15})\D/i", $textWithoutSpace, $matches)) {
            return $matches[1];
        }

        throw new CustomException('Store Code format is invalid',
            CustomException::ERROR_CODE_STORE_CODE_FORMAT_IS_INVALID);
    }
}
