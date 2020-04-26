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

class Upyun
{
    protected $config;
    protected $status;

    public function __construct($config=[])
    {
        $this->config = $config;
        if ($this->config['token'] == null) {
            $this->status = false;
        } else {
            $this->status = true;
        }
    }

    public function send($name, $arguments)
    {
        if ($this->status) {
            $conf = $this->config['actions'][$name];
            $msg['mobile'] = $arguments[0];
            $msg['template_id'] = $conf['template_id'];
            $msg['vars'] = $arguments[1];
            $url = $this->config['apiurl'] ?: 'https://sms-api.upyun.com/api/messages';
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\nAuthorization: ".$this->config['token'],
                    'method'  => 'POST',
                    'content' => http_build_query($msg)
                )
            );
            $context  = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            $result = json_decode($result,true);
            if (isset($result['message_ids']['0']['message_id'])) {
                $data['code'] = 200;
                $data['msg'] = '发送成功';
            } else {
                $data['code'] = 102;
                $data['msg'] = '发送失败';
            }
        } else {
            $data['code'] = 103;
            $data['msg'] = '请在后台设置Token';
        }
        return $data;
    }
}