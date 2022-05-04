<?php

namespace App\Constant;

class Result
{
    const CODE_OK               = 0;
    const CODE_FAIL             = 1;
    const CODE_NOT_FOUND        = 30;
    const CODE_FORBIDDEN        = 60;
    const CODE_UNAUTHORIZED     = 100;
    const CODE_METHOD_NOT_ALLOW = 40;
    const CODE_UNKNOWN_ERROR    = 90;

    public static $message = [
        self::CODE_OK               => 'Success',
        self::CODE_FAIL             => 'Fail',
        self::CODE_FORBIDDEN        => 'FORBIDDEN',
        self::CODE_UNAUTHORIZED     => 'Unauthorized',
        self::CODE_UNKNOWN_ERROR    => 'Unknown error',
        self::CODE_METHOD_NOT_ALLOW => 'Method not allowed',
    ];

    public static function getMessage($code)
    {
        if (!empty(self::$message[$code])) {
            return self::convertLang(self::$message[$code]);
        } else {
            return self::convertLang(self::$message[self::CODE_UNKNOWN_ERROR]);
        }
    }
    private static function convertLang($string)
    {
        return __($string);
    }

}
