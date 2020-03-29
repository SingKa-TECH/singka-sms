<?php


namespace singka\sms;

use singka\sms\Aliyun;
use singka\sms\Qcloud;
use singka\sms\Ucloud;

class sksms
{
    public function __construct($type,$config)
    {
        if($type == '' || is_array($config) == false) {
            $data['code'] = 101;
            $data['msg'] = '参数错误';
            return $data;
        } else {
            $data['code'] = 200;
            $data['msg'] = '初始化成功';
            if ($type == 'aliyun') {
                $data['sms'] = new Aliyun($config);
            } elseif ($type == 'qcloud') {
                $data['sms'] = new Qcloud($config);
            } elseif ($type == 'ucloud') {
                $data['sms'] = new Ucloud($config);
            } else {
                $data['code'] = 102;
                $data['msg'] = 'type类型不存在';
            }
            return $data;
        }
    }
}