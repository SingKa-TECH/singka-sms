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

namespace singka\sms;

use SingKa\Sms\Aliyun;
use SingKa\Sms\Qcloud;
use SingKa\Sms\Ucloud;

class sksms
{
    protected $type;
    protected $config;
    protected static $snakeCache = [];

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
        $name = static::snake($name);
        if (empty($this->config['actions'][$name])) {
            $data['code'] = 104;
            $data['msg'] = '没有找到操作类型:'.$name;
            return $data;
            exit();
        } else {
            if ($this->type == 'aliyun') {
                return (new Aliyun($this->config))->send($name,$arguments);
            } elseif ($this->type == 'qcloud') {
                $sms = new Qcloud($this->config);
                return $sms->send($name,$arguments);
            } elseif ($this->type == 'ucloud') {
                $sms = new Ucloud($this->config);
                return $sms->send($name,$arguments);
            } else {
                $data['code'] = 102;
                $data['msg'] = 'type类型不存在';
                return $data;
            }
        }
    }

    public static function snake(string $value, string $delimiter = '_'): string
    {
        $key = $value;

        if (isset(static::$snakeCache[$key][$delimiter])) {
            return static::$snakeCache[$key][$delimiter];
        }

        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', $value);

            $value = mb_strtolower($value(preg_replace('/(.)(?=[A-Z])/u', '$1' . $delimiter, $value)), 'UTF-8');
        }

        return static::$snakeCache[$key][$delimiter] = $value;
    }
}