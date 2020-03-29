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

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Aliyun
{
    protected $config;
    protected $status;

    public function __construct($config=[])
    {
        $this->config = $config;
        if (empty($this->config['access_key']) || empty($this->config['access_secret'])) {
            $this->status = false;
        } else {
            $this->status = true;
            AlibabaCloud::accessKeyClient($this->config['access_key'], $this->config['access_secret'])
                ->regionId($this->config['region_id'])
                ->asDefaultClient();
        }
    }

    public function send($name, $arguments)
    {
        if ($this->status) {
            $conf = $this->config['actions'][$name];
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                ->version($this->config['version'])
                ->action('SendSms')
                ->method('POST')
                ->host($this->config['host'])
                ->options([
                    'query' => [
                        'RegionId' => $this->config['region_id'],
                        'PhoneNumbers' => $arguments[0],
                        'SignName' => $this->config['sign_name'],
                        'TemplateCode' => $conf['template_id'],
                        'TemplateParam' => json_encode($arguments[1]),
                    ],
                ])
                ->request();
            $result = $result->toArray();
            if ($result['Code'] == "OK") {
                $data['code'] = 200;
                $data['msg'] = '发送成功';
            } else {
                $data['code'] = $result['Code'];
                $data['msg'] = '发送失败，'.$result['Message'];
            }
        } else {
            $data['code'] = 103;
            $data['msg'] = '请在后台设置accessKeyId和accessKeySecret';
        }
        return $data;
    }
}