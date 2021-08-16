<?php

namespace Tests\Unit;

use App\Entities\StoreCode;
use App\Services\StoreCodeExistedService;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class StoreCodeExistedServiceTest extends TestCase
{
    /** @var  StoreCodeExistedService $storeCodeExistedService  */
    protected $storeCodeExistedService;
    protected $storeCode;

    public function setUp(): void
    {
        parent::setUp();
        $this->storeCodeExistedService = resolve(StoreCodeExistedService::class);
        $this->storeCode = StoreCode::factory()->create()->refresh();
    }

    public function testSetStoreCodeToRedis()
    {
        $this->storeCodeExistedService->setStoreCodeToRedis($this->storeCode);

        $storeCodeInRedis = json_decode(Redis::hget(StoreCode::REDIS_KEY, $this->storeCode->store_code), true);
        self::assertEquals($this->storeCode->store_code, $storeCodeInRedis['store_code']);
    }

    public function testCheckStoreCodeExisted()
    {
        $this->assertTrue($this->storeCodeExistedService->checkStoreCodeExisted($this->storeCode->store_code));

        // test when redis missed
        Redis::hdel(StoreCode::REDIS_KEY, $this->storeCode->store_code);
        $this->assertFalse((bool) Redis::hexists(StoreCode::REDIS_KEY, $this->storeCode->store_code));
        $this->assertTrue($this->storeCodeExistedService->checkStoreCodeExisted($this->storeCode->store_code));

        // when redis missed and existed in sql, will set to redis after check
        $this->assertTrue((bool) Redis::hexists(StoreCode::REDIS_KEY, $this->storeCode->store_code));
    }

    public function testCheckStoreCodeDoesNotExist()
    {
        $storeCode = 'aaaaa';
        StoreCode::query()->where('store_code', $storeCode)->delete();
        Redis::hdel(StoreCode::REDIS_KEY, $storeCode);
        $this->assertFalse($this->storeCodeExistedService->checkStoreCodeExisted($storeCode));
    }
}
