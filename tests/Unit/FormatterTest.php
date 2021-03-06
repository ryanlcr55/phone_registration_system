<?php

namespace Tests\Unit;

use App\Libs\Formatter;
use Tests\TestCase;

class FormatterTest extends TestCase
{
    use Formatter;

    /**
     * @dataProvider phoneNumProvider
     */
    public function testGetFormattedPhoneNum($phones, $expects)
    {
        $this->assertEquals($this->getFormattedPhoneNum($phones), $expects);
    }

    /**
     * @dataProvider storeCodeTextProvider
     */
    public function testGetFormattedStoreCodeInText($texts, $expects)
    {
        $this->assertEquals($this->getFormattedStoreCodeInText($texts), $expects);
    }

    public function phoneNumProvider()
    {
        return [
            ['0912345678', '0912345678'],
            ['0912-345-678', '0912345678'],
            ['(+886)912345678', '0912345678'],
        ];
    }

    public function storeCodeTextProvider()
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
