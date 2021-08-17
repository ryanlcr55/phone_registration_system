<?php

namespace App\Services;

use App\Entities\Store;
use Illuminate\Support\Facades\Redis;

class StoreExistedService
{
    protected $storeModel;

    public function __construct(Store $storeModel){
        $this->storeModel = $storeModel;
    }

    /**
     * @param  string  $storeCode
     * @return bool
     */
    public function checkStoreExisted(string $storeCode): bool
    {
        if (Redis::hexists($this->storeModel::REDIS_KEY, $storeCode)) {
            return true;
        }

        $store = $this->storeModel::query()
            ->where('store_code', '=', $storeCode)
            ->first();
        if ($store) {
            $this->setStoreToRedis($store);
            return true;
        }

        return false;
    }

    public function setStoreToRedis(Store $store)
    {
        return Redis::hset($this->storeModel::REDIS_KEY, $store->store_code,
            $this->storeModel::redisDataForm($store));
    }
}
