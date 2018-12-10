<?php
/**
 * @file helloAction.php
 * @author strick@beishanwen.com
 * @date 2018/10/30 12:00:00
 * @brief
 *
 **/

class helloAction
{
    public function __construct()
    {
    }

    public function __destruct()
    {
    }

    /**
     * 执行函数
     * @author strick@beishanwen.com
     * @param null
     * @return string $json_res
     */
    public function execute()
    {
        try {
            return self::_execute();
        } catch (Exception $e) {
            Bingo_Log::fatal("error.errmsg[" . $e->getMessage() . "]");
            return false;
        }
    }

    /**
     * 执行函数
     * @author strick@beishanwen.com
     * @return string
     * @throws
     */
    private function _execute()
    {
        // 初始化参数
        Util_Params::execute(__CLASS__);
        echo "hello world";
    }

}
