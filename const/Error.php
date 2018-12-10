<?php
/**
 * @brief
 * @author strick@beishanwen.com
 * @date 2018-10-01
 */

class Const_Error
{
    const SUCCESS = 0;
    const FAILED = 1;
    const PARAMS_INVALID = 2;
    const SYSTEM_ERROR = 3;

    /**
     *错误码对应的错误信息变量
     */
    private static $errMsg = array(
        self::SUCCESS => 'success',
        self::FAILED => 'failed',
        self::PARAMS_INVALID => 'params invalid, check it please',
        self::SYSTEM_ERROR => 'system error',
    );

    /**
     *通过错误码获取对应的错误信息
     * @author chenbinwen@baidu.com
     * @param int $errno
     * @param $fromResponse
     * @return string $errMsg
     */
    public static function getErrMsg($errno, $fromResponse = false)
    {
        if (isset(self::$errMsg[$errno])) {
            return self::$errMsg[$errno];
        } else {
            if (!$fromResponse) {
                Bingo_Log::fatal("errno is not defined. errno[$errno]");
            }
            return false;
        }
    }
}
