<?php

namespace App\Libs;

use App\Exceptions\CustomException;

Trait Formatter
{
    /**
     * Get Phone number in three type of string. ex:(+886)912345678, 0912345678, 0912-345-678
     * @param  string  $phoneNum
     * @return string
     * @throws CustomException
     */
    public function getFormattedPhoneNum(string $phoneNum): string
    {
        if (preg_match('/^(\(\+886\))(\d{9})$/', $phoneNum, $matches)) {
            // (+886)912345678 type
            return '0'.$matches[2];
        } elseif (preg_match('/^(09)(\d{8})$/', $phoneNum, $matches)) {
            // 0912345678 type
            return $matches[0];
        } elseif (preg_match('/^(09)(\d{2})\-(\d{3})\-(\d{3})$/', $phoneNum, $matches)) {
            // 0912-345-678
            return $matches[1].$matches[2].$matches[3].$matches[4];
        }

        throw new CustomException('Phone number format is invalid',
            CustomException::ERROR_CODE_PHONE_NUMBER_FORMAT_IS_INVALID);
    }

    /**
     * Get the first set of 15 consecutive digits
     * @param  string  $text
     * @return string
     * @throws CustomException
     */
    public function getFormattedStoreCodeInText(string $text): string
    {
        // clear space and add '.' to match preg_replace's pattern
        $textWithoutSpace = '.' . preg_replace('/\s+/', '', $text).',';
        if (preg_match("/[^0-9]]*([\d]{15})\D/i", $textWithoutSpace, $matches)) {
            return $matches[1];
        }

        throw new CustomException('Store Code format is invalid',
            CustomException::ERROR_CODE_STORE_CODE_FORMAT_IS_INVALID);
    }
}
