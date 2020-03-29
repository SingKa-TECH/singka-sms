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

use Qcloud\Sms\SmsSingleSender;

class Qcloud
{
    protected $config;
    protected $status;
    protected $sms;

    public function __construct($config=[])
    {
        $this->config = $config;
        if ($this->config['appid'] == null || $this->config['appkey'] == null) {
            $this->status = false;
        } else {
            $this->status = true;
            $this->sms = new SmsSingleSender($this->config['appid'], $this->config['appkey']);
        }
    }

    public function send($name, $arguments)
    {
        if ($this->status) {
            $sms = new SmsSingleSender($this->config['appid'], $this->config['appkey']);
            $conf = $this->config['actions'][$name];
            $phoneNumbers = $arguments[0];
            $templateId = $conf['template_id'];
            $smsSign = $this->config['sign_name'];
            $result = $sms->sendWithParam("86", $phoneNumbers, $templateId, $arguments[1], $smsSign, "", "");
            $result = json_decode($result,true);
            if ($result['result'] == 0) {
                $data['code'] = 200;
                $data['msg'] = '发送成功';
            } else {
                $data['code'] = $result['result'];
                $data['msg'] = '发送失败，'.$result['errmsg'];
            }
        } else {
            $data['code'] = 103;
            $data['msg'] = '请在后台设置appid和appkey';
        }
        return $data;
    }
}