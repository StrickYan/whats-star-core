<?php
/**
 * @desc
 * @author strick@beishanwen.com
 * @date 2018/11/02
 */

class faceMatchAction
{
    /**
     * @desc 构造函数
     * @param  void
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @desc 析构函数
     * @param  void
     * @return void
     */
    public function __destruct()
    {
    }

    /**
     * @desc 执行函数，主要调用_execute
     * @param  null
     * @return string $res
     */
    public function execute()
    {
        try {
            $arrData = self::_execute();
            return $arrData;
        } catch (Exception $e) {
            Bingo_Log::warning("error.errmsg[" . $e->getMessage() . "]");
            return false;
        }
    }

    /**
     * @desc 主要执行函数
     * @param  null
     * @return string $res
     * @throws
     */
    private function _execute()
    {
        $params = Util_Params::execute(__CLASS__);
        Bingo_Log::pushNotice('params', serialize($params));

        $obj = new Service_FaceMatch();
        $ret = $obj->execute();

        Bingo_Log::pushNotice('errno', $ret['errno']);
        return Ad_Response::json($ret['errno'], $ret['data']);
    }
}
