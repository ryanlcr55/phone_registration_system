<?php

namespace Tests\Unit;

use App\Entities\StoreCode;
use App\Services\PhoneRegisterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesApplication;
use Tests\TestCase;

class PhoneRegisterServiceTest extends TestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    /** @var  PhoneRegisterService $phoneRegisterService  */
    protected $phoneRegisterService;
    protected $storeCode;

    public function setUp(): void
    {
        parent::setUp();
        $this->phoneRegisterService = resolve(PhoneRegisterService::class);
        $this->storeCode = StoreCode::factory()->create()->refresh();
    }

    /**
     * @dataProvider phone_num_provider
     */
    public function test_get_formatted_phone_num($phones, $expects)
    {
        $this->assertEquals($this->phoneRegisterService->getFormattedPhoneNum($phones), $expects);
    }

    public function phone_num_provider()
    {
        return [
            [ '0912345678', '0912345678' ],
            [ '0912-345-678', '0912345678'],
            [ '(+886)912345678', '0912345678'],
        ];
    }}
