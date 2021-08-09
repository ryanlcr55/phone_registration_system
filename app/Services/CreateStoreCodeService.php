<?php

namespace App\Services;


use App\Entities\StoreCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class CreateStoreCodeService
{
    protected $storeCodeEntity;

    public function __construct(
        StoreCode $storeCodeEntity
    ) {
        $this->storeCodeEntity = $storeCodeEntity;
    }

    public function createStoreCode(string $storeName,float $lan,float $lon) {
        try {
            DB::beginTransaction();
            $storeCode = $this->storeCodeEntity::query()
                ->create([
                    'store_name' => $storeName,
                    'lan' => $lan,
                    'lon' => $lon,
                ]);
            $storeCode->update([
                'store_code' => base_convert($storeCode->id, 10, 16),
            ]);
            $storeCode = $this->storeCodeEntity::query()->find($storeCode->id);
            DB::commit();

            Redis::hset($this->storeCodeEntity::REDIS_KEY, $storeCode, $this->storeCodeEntity::redisDataForm($storeCode));
            return $storeCode;
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }
}
