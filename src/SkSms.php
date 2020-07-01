<?php
// +----------------------------------------------------------------------
// | 胜家云 [ SingKa Cloud ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2020 https://www.singka.net All rights reserved.
// +----------------------------------------------------------------------
// | 宁波晟嘉网络科技有限公司
// +----------------------------------------------------------------------
// | Author: ShyComet <shycomet@qq.com>
// +----------------------------------------------------------------------

namespace SingKa\Sms;

use SingKa\Sms\Aliyun;
use SingKa\Sms\Qcloud;
use SingKa\Sms\Ucloud;
use SingKa\Sms\Qiniu;
use SingKa\Sms\Upyun;
use SingKa\Sms\Huawei;

class SkSms
{
    protected $type;
    protected $config;

    public function __construct($type,$config)
    {
        if($type == '' || is_array($config) == false) {
            $data['code'] = 101;
            $data['msg'] = '参数错误';
            return $data;
        } else {
            $this->type = $type;
            $this->config = $config;
        }
    }

    public function __call($name, $arguments)
    {
        if (empty($this->config['actions'][$name])) {
            $data['code'] = 104;
            $data['msg'] = '没有找到操作类型:'.$name;
            return $data;
            exit();
        } else {
            switch ($this->type) {
                case 'aliyun':
                    $sms = new Aliyun($this->config);
                    return $sms->send($name, $arguments);
                    break;
                case 'qcloud':
                    $sms = new Qcloud($this->config);
                    return $sms->send($name, $arguments);
                    break;
                case 'ucloud':
                    $sms = new Ucloud($this->config);
                    return $sms->send($name, $arguments);
                    break;
                case 'qiniu':
                    $sms = new Qiniu($this->config);
                    return $sms->send($name, $arguments);
                    break;
                case 'upyun':
                    $sms = new Upyun($this->config);
                    return $sms->send($name, $arguments);
                    break;
                case 'huawei':
                    $sms = new Huawei($this->config);
                    return $sms->send($name, $arguments);
                    break;
                default:
                    $data['code'] = 102;
                    $data['msg'] = 'type类型不存在';
                    return $data;
            }
        }
    }
}