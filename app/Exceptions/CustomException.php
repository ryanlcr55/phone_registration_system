<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{

    const ERROR_CODE_UNKNOWN = 0x001;

    const ERROR_CODE_STORE_CODE_FAIL_TO_GENERATE = 0x100;
    const ERROR_CODE_STORE_DOSE_NOT_EXISTED = 0x101;
}
