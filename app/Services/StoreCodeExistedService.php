<?php

namespace App\Services;

use App\Entities\StoreCode;
use Illuminate\Support\Facades\Redis;

class StoreCodeExistedService
{
    protected $storeCodeModel;

    public function __construct(StoreCode $storeCodeModel){
        $this->storeCodeModel = $storeCodeModel;
    }

    /**
     * @param  string  $storeCode
     * @return bool
     */
    public function checkStoreCodeExisted(string $storeCode): bool
    {
        if (Redis::hexists($this->storeCodeModel::REDIS_KEY, $storeCode)) {
            return true;
        }

        $existedStoreCode = $this->storeCodeModel::query()
            ->where('store_code', '=', $storeCode)
            ->first();
        if ($existedStoreCode) {
            $this->setStoreCodeToRedis($existedStoreCode);
            return true;
        }

        return false;
    }

    public function setStoreCodeToRedis(StoreCode $storeCode)
    {
        return Redis::hset($this->storeCodeModel::REDIS_KEY, $storeCode->store_code,
            $this->storeCodeModel::redisDataForm($storeCode));
    }
}
