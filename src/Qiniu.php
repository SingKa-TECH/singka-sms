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
use Qiniu\Auth;
use Qiniu\Sms\Sms;


class Qiniu
{
    protected $config;
    protected $status;
    protected $sms;

    public function __construct($config=[])
    {
        $this->config = $config;
        if ($this->config['AccessKey'] == null || $this->config['SecretKey'] == null) {
            $this->status = false;
        } else {
            $this->status = true;
            $auth = new Auth($this->config['AccessKey'], $this->config['SecretKey']);
            $this->sms = new Sms($auth);
        }
    }

    public function send($name, $arguments)
    {
        if ($this->status) {
            $conf = $this->config['actions'][$name];
            $phoneNumbers = $arguments[0];
            $templateId = $conf['template_id'];
            $result = $this->sms->sendMessage($templateId, $phoneNumbers, $arguments[1]);
            if (isset($result[0]['job_id'])) {
                $data['code'] = 200;
                $data['msg'] = '发送成功';
            } else {
                $data['code'] = 102;
                $data['msg'] = '发送失败';
            }
        } else {
            $data['code'] = 103;
            $data['msg'] = '请在后台设置AccessKey和SecretKey';
        }
        return $data;
    }
}