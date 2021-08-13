<?php

namespace Tests\Feature;

use App\Entities\StoreCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreCodeCreateTest extends TestCase
{
    public function test_api_create_store_code()
    {
        $response = $this->post('/api/storeCode/', [
            'store_name' => Str::random(4),
            'lat' => 23.1234567,
            'lon' => 123.1234567,
        ]);

        $response->assertSuccessful();
        $storeCode = StoreCode::query()->find($response->json('result.id'));

        $this->assertNotEmpty($storeCode);
        $this->assertTrue((bool) Redis::hexists(StoreCode::REDIS_KEY, $storeCode->store_code));
    }
}
