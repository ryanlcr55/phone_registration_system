<?php

namespace Tests\Feature;

use App\Entities\Store;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Tests\TestCase;

class StoreCreateTest extends TestCase
{
    public function testApiCreateStore()
    {
        $response = $this->post('/api/store/', [
            'store_name' => Str::random(4),
            'lat' => 23.1234567,
            'lon' => 123.1234567,
        ]);

        $response->assertSuccessful();
        $store = Store::query()->find($response->json('result.id'));

        $this->assertNotEmpty($store);
        $this->assertTrue((bool) Redis::hexists(Store::REDIS_KEY, $store->store_code));
    }
}
