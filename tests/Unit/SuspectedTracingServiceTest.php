<?php
namespace Tests\Unit;

use App\Entities\PhoneRegistrationRecord;
use App\Entities\StoreCode;
use App\Services\SuspectedTracingService;
use Carbon\Carbon;
use Tests\TestCase;

class SuspectedTracingServiceTest extends TestCase
{
    /** @var SuspectedTracingService $suspectedTracingService  */
    protected $suspectedTracingService;
    protected $numberPerPage;
    protected $rageDays;
    protected $storeCode;

    public function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->suspectedTracingService = resolve(SuspectedTracingService::class);
        $this->numberPerPage = 10;
        $this->rageDays = 7;
        $this->storeCode = StoreCode::factory()->create()->refresh();

    }

    public function testGetRegistrations()
    {
        $referenceDateTime = Carbon::now();

        // 感染者在第二天進入商店
        $infectedRecord = PhoneRegistrationRecord::factory()->create([
            'phone_num' => '0911111',
            'store_code' => $this->storeCode->store_code,
            'registration_datetime' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
        ])->refresh();

        // 民眾A 在第八天與感染者進入同商店 (感染者進入該商店往後推七天內), 應被撈出
        $suspectedA = PhoneRegistrationRecord::factory()->create([
            'phone_num' => '0922222',
            'store_code' => $infectedRecord->store_code,
            'registration_datetime' => Carbon::now()->addDays(8)->format('Y-m-d H:i:s'),
        ])->refresh();

        // 民眾B 在第10天與感染者進入同商店, (感染者進入該商店往後推超過七天), 應不被撈出
        $suspectedB = PhoneRegistrationRecord::factory()->create([
            'phone_num' => '09133333',
            'store_code' => $infectedRecord->store_code,
            'registration_datetime' => Carbon::now()->addDays(10)->format('Y-m-d H:i:s'),
        ])->refresh();

        $result = $this->suspectedTracingService->getSuspectedRegistrations($infectedRecord->phone_num, $referenceDateTime, $this->rageDays, $this->numberPerPage, 1);

        // 應只有撈出一筆
        $this->assertEquals(1, $result['total']);
        // 且該筆資料為 在設定時間 (7天)內的 顧客A
        $result['data']->first();
        $this->assertEquals($suspectedA->phone_num, $result['data']->first()['phone_num']);

    }
}
