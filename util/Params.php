<?php

/**
 * @desc 参数操作类
 * @author strick@beishanwen.com
 * @date 2018/10/26
 */
class Util_Params
{
    private static $_params = array();
    private static $isLocked = false;

    /**
     * @desc 主要获取入参的执行函数
     * @param string $actionName
     * @return array
     * @author strick@beishanwen.com
     */
    public static function execute($actionName = '')
    {
        Bingo_Timer::start('params');

        switch ($actionName) {
            case "helloAction":
                break;

            case 'faceMatchAction':
                $params['img_url'] = addslashes(Bingo_Http_Request::get('img_url', ''));
                break;

            default:
                break;
        }

        // 公共参数
        $params['user_ip'] = ip2long(Bingo_Http_Ip::getUserClientIp());
        $params['user_agent'] = addslashes(Bingo_Http_Request::getServer('HTTP_USER_AGENT'));
        $params['log_id'] = Bingo_Log::getLogId();
        $params['time'] = time();
        self::$_params = $params;
        Bingo_Timer::end('params');
        return self::$_params;
    }

    /**
     * @desc 获取所有入参或指定入参
     * @param string $strRecord
     * @param string $defVal
     * @return array|mixed|string
     */
    public static function get($strRecord = '', $defVal = '')
    {
        if ($strRecord == '') {
            return self::$_params;
        }
        if (!isset(self::$_params[$strRecord])) {
            return $defVal;
        } else {
            return self::$_params[$strRecord];
        }
    }

    /**
     * @desc 添加参数进入param
     * @param $key
     * @param $value
     * @param int $forceInsert
     * @return bool
     */
    public static function add($key, $value, $forceInsert = 0)
    {
        if (self::$isLocked) {
            Bingo_Log::warning("params have been locked, can not add.");
            return true;
        }
        if ('' == $key && !$forceInsert) {
            Bingo_Log::fatal("add param key is empty");
            return false;
        }
        if (isset(self::$_params[$key])) {
            Bingo_Log::fatal("this param exist. key[$key]");
            return false;
        }
        self::$_params[$key] = $value;
        return true;
    }

    /**
     * @desc 修改参数进入param
     * @param $key
     * @param $value
     * @return bool
     */
    public static function edit($key, $value)
    {
        if (self::$isLocked) {
            Bingo_Log::warning("params have been locked, can not edit.");
            return true;
        }
        if ($value == self::$_params[$key]) {
            return true;
        }
        $oldKey = 'o_' . $key;
        self::$_params[$oldKey] = self::$_params[$key];
        self::$_params[$key] = $value;
        return true;
    }

    /**
     * @desc 删除参数进入param
     * @param $key
     * @return bool
     */
    public static function del($key)
    {
        if (self::$isLocked) {
            Bingo_Log::warning("params have been locked, can not del.");
            return true;
        }
        if (isset(self::$_params[$key])) {
            unset(self::$_params[$key]);
        }
        return true;
    }

    /**
     * @desc 锁定param
     */
    public static function locked()
    {
        self::$isLocked = true;
    }
}
