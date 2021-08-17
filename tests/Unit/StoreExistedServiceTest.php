<?php

namespace Tests\Unit;

use App\Entities\Store;
use App\Services\StoreExistedService;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class StoreExistedServiceTest extends TestCase
{
    /** @var  StoreExistedService $storeExistedService  */
    protected $storeExistedService;
    protected $store;

    public function setUp(): void
    {
        parent::setUp();
        $this->storeExistedService = resolve(StoreExistedService::class);
        $this->store = Store::factory()->create()->refresh();
    }

    public function testSetStoreCodeToRedis()
    {
        $this->storeExistedService->setStoreToRedis($this->store);

        $storeCodeInRedis = json_decode(Redis::hget(Store::REDIS_KEY, $this->store->store_code), true);
        self::assertEquals($this->store->store_code, $storeCodeInRedis['store_code']);
    }

    public function testCheckStoreExisted()
    {
        $this->assertTrue($this->storeExistedService->checkStoreExisted($this->store->store_code));

        // test when redis missed
        Redis::hdel(Store::REDIS_KEY, $this->store->store_code);
        $this->assertFalse((bool) Redis::hexists(Store::REDIS_KEY, $this->store->store_code));
        $this->assertTrue($this->storeExistedService->checkStoreExisted($this->store->store_code));

        // when redis missed and existed in sql, will set to redis after check
        $this->assertTrue((bool) Redis::hexists(Store::REDIS_KEY, $this->store->store_code));
    }

    public function testCheckStoreDoesNotExist()
    {
        $storeCode = 'aaaaa';
        Store::query()->where('store_code', $storeCode)->delete();
        Redis::hdel(Store::REDIS_KEY, $storeCode);
        $this->assertFalse($this->storeExistedService->checkStoreExisted($storeCode));
    }
}
