<?php

/**
 * @desc
 * @author strick@beishanwen.com
 * @date 2018/11/02
 */
class Service_FaceMatch
{
    /**
     * @brief 私有构造函数,规范调用方法
     * @param void
     * @return void
     */
    public function __construct()
    {
        Bingo_Timer::start(__CLASS__);
    }

    /**
     * @brief 析构函数
     * @param void
     * @return void
     */
    public function __destruct()
    {
        Bingo_Timer::end(__CLASS__);
    }

    /**
     * @brief
     * @return array
     */
    public function execute()
    {
        $arrInput = Util_Params::get();
        $img_url = $arrInput['img_url'];
        if (empty($img_url)) {
            $destination_folder = "/home/wwwroot/default/WhatsStar/upload/"; //上传文件路径
            $input_file_name = "upfile";
            $max_width = 640;
            $max_height = 1136;
            $img_name = Util_Upload::uploadImg($destination_folder, $input_file_name, $max_width, $max_height); // 调用上传函数
            if (false === $img_name) {
                Bingo_Log::fatal("Util_Upload::uploadImg return false");
                return Ad_Response::arrayRet(Const_Error::FAILED, array());
            }
            $img_url = "https://www.beishanwen.com/WhatsStar/upload/" . $img_name;
        }
        $imgInfos = $this->getSimilarStar($img_url);
        if (false === $imgInfos) {
            Bingo_Log::fatal("getSimilarStar return false");
            return Ad_Response::arrayRet(Const_Error::FAILED, array());
        }

        $ret = array(
            'img_url' => $img_url,
            'match_img_infos' => $imgInfos,
        );

        return Ad_Response::arrayRet(Const_Error::SUCCESS, $ret);
    }

    private function getSimilarStar($img_url)
    {
        $url = "http://www.eyekey.com/eyekey/apply/face_match_many";
        $param = array(
            // 'url' => "https://imgsa.baidu.com/baike/s%3D500/sign=89fc6048d0ca7bcb797bc72f8e0b6b3f/96dda144ad3459824945bc190bf431adcaef846a.jpg",
            // 'url' => 'https://gss3.bdstatic.com/-Po3dSag_xI4khGkpoWK1HF6hhy/baike/c0%3Dbaike150%2C5%2C5%2C150%2C50/sign=fb9c07eda74bd11310c0bf603bc6cf6a/2f738bd4b31c8701928251782d7f9e2f0708ff7c.jpg',
            'url' => $img_url,
            'canvas_type' => 1,
        );
        $ret = self::postData($url, $param);
        $ret = self::decodeHtmlEntity($ret);
        // $ret = json_encode($ret, JSON_UNESCAPED_UNICODE);

        if ('0000' != $ret['res_code']) {
            Bingo_Log::fatal("eye key return failed, data[" . json_encode($ret) . "]");
            return false;
        }

        $imgInfos = array();
        foreach ($ret['result'] as $k => $v) {
            $ret = self::isUrlExist('http://www.eyekey.com' . $v['url']);
            if (false === $ret) {
                // unset($datas['result'][$k]); // 过滤无效的图片链接
                continue;
            }
            $imgInfos[] = array(
                'people_name' => $v['people_name'],
                'similarity' => $v['similarity'],
                'url' => 'http://www.eyekey.com' . $v['url'],
            );
        }
        return $imgInfos;
    }

    private function postData($url, $data)
    {
        $ch = curl_init();
        $timeout = 1000;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, "");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);
        if (!$data) {
            var_dump(curl_error($ch));
        }
        curl_close($ch);
        return $data;
    }

    private function decodeHtmlEntity($data)
    {
        $data = json_decode($data, true);
        foreach ($data['result'] as $k => &$v) {
            $v['people_name'] = html_entity_decode($v['people_name'], ENT_QUOTES);
        }
        // var_dump($data);exit;
        return $data;
    }

    private function isUrlExist($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_NOBODY, 1); // 不下载
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); // 设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 200) {
            return true;
        }
        return false;
    }
}
