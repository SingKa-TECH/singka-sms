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

use Singka\UcloudSms\UcloudApiClient;

class Ucloud
{
    protected $config;
    protected $status;
    protected $sms;

    public function __construct($config=[])
    {
        $this->config = $config;
        if ($this->config['public_key'] == '' || $this->config['private_key'] == '' || $this->config['project_id'] == '') {
            $this->status = false;
        } else {
            $this->status = true;
            $this->sms = new UcloudApiClient($this->config['base_url'], $this->config['public_key'], $this->config['private_key'], $this->config['project_id']);
        }
    }

    public function send($name, $arguments)
    {
        if ($this->status) {
            $conf = $this->config['actions'][$name];
            $params['Action'] = "SendUSMSMessage";
            $phoneNumbers = $arguments[0];
            if(is_array($phoneNumbers)){
                foreach($phoneNumbers as $key => $val){
                    $params["PhoneNumbers.".$key] = $val;
                }
            }else{
                $params['PhoneNumbers.0'] = $phoneNumbers;
            }
            $params["SigContent"] = $this->config['sign_name'];
            $templates = $arguments[1];
            $params["TemplateId"] = $conf['template_id'];
            if(is_array($templates)){
                foreach($templates as $key => $val) {
                    $params["TemplateParams.".$key] = $val;
                }
            }else{
                $params["TemplateParams.0"] = $templates;
            }
            $result = $this->sms->get("/", $params);
            if ($result['RetCode'] == 0) {
                $data['code'] = 200;
                $data['msg'] = '发送成功';
            } else {
                $data['code'] = $result['RetCode'];
                $data['msg'] = '发送失败，'.$result['Message'];
            }
        } else {
            $data['code'] = 103;
            $data['msg'] = '请在后台设置PUBLIC_KEY和PRIVATE_KEY';
        }
        return $data;
    }
}