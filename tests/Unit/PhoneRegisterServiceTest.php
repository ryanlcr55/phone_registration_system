<?php

namespace Tests\Unit;

use App\Services\PhoneRegisterService;
use Tests\TestCase;

class PhoneRegisterServiceTest extends TestCase
{
    /** @var  PhoneRegisterService $phoneRegisterService */
    protected $phoneRegisterService;

    public function setUp(): void
    {
        parent::setUp();
        $this->phoneRegisterService = resolve(PhoneRegisterService::class);
    }

    /**
     * @dataProvider phone_num_provider
     */
    public function test_get_formatted_phone_num($phones, $expects)
    {
        $this->assertEquals($this->phoneRegisterService->getFormattedPhoneNum($phones), $expects);
    }

    /**
     * @dataProvider store_code_text_provider
     */
    public function test_get_formatted_store_code_in_text($texts, $expects)
    {
        $this->assertEquals($this->phoneRegisterService->getFormattedStoreCodeInText($texts), $expects);
    }

    public function phone_num_provider()
    {
        return [
            ['0912345678', '0912345678'],
            ['0912-345-678', '0912345678'],
            ['(+886)912345678', '0912345678'],
        ];
    }

    public function store_code_text_provider()
    {
        return [
            ['場所代碼：1111 1111 1111 111\n本簡訊是簡訊實聯制發送，限防疫目的使用', '111111111111111'],
            ['場所代碼：1111 1111 1111 111 本簡訊是簡訊實聯制發送，限防疫目的使用, 分店代號 2222 2222 2222 222', '111111111111111'],
            ['限防疫目的使用, 分店代號 2222 22\n 場所代碼：1111 1111 1111 111\n本簡訊是簡訊實聯制發送，限防疫目的使用', '111111111111111'],
            ['簡訊是簡訊實聯制發送，限防疫目的使用\n 場所代碼： 111 111 111 111 111', '111111111111111'],
            ['111111111111111', '111111111111111'],
        ];
    }
}
