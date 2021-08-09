<?php

namespace App\Services;


use App\Entities\StoreCode;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CreateStoreCodeService
{
    protected $storeCodeModel;

    public function __construct(
        StoreCode $storeCodeModel
    ) {
        $this->storeCodeModel = $storeCodeModel;
    }

    public function createStoreCode(string $storeName,float $lan,float $lon) {
        try {
            DB::beginTransaction();
            $storeCode = $this->storeCodeModel::query()
                ->create([
                    'store_name' => $storeName,
                    'lan' => $lan,
                    'lon' => $lon,
                ]);
            $storeCode->update([
                'store_code' => base_convert($storeCode->id, 10, 16),
            ]);
            $storeCode = $this->storeCodeModel::query()->find($storeCode->id);
            DB::commit();

            Redis::hset($this->storeCodeModel::REDIS_KEY, $storeCode, $this->storeCodeModel::redisDataForm($storeCode));
            return $storeCode;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new CustomException('Failed to generate store code', CustomException::ERROR_CODE_STORE_CODE_FAIL_TO_GENERATE);
        }
    }
}
