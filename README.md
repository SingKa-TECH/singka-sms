# ThinkPHP6集成短信发送平台

#### 介绍
本项目是集成了各大云服务厂商的短信业务平台，支持ThinkPHP5.0、ThinkPHP5.1和ThinkPHP6.0，由宁波晟嘉网络科技有限公司维护，目前支持阿里云、腾讯云、七牛云、又拍云和Ucloud，接下来将接入华为云等国内较大的公有云服务厂商。

#### 安装教程

使用 `composer require singka/singka-sms` 命令行安装即可。

安装完成后会自动生成 `config/sms.php` 配置文件，内容如下：

```php
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
return [
    'aliyun'       => [
        'version'       => '2017-05-25',
        'host'          => 'dysmsapi.aliyuncs.com',
        'scheme'        => 'http',
        'region_id'     => 'cn-hangzhou',
        'access_key'    => '',
        'access_secret' => '',
        'sign_name'     => '',
        'actions'       => [
            'register'        => [
                'actions_name'      => '注册验证',
                'template_id'  => 'SMS_53115055',
            ],
            'login'           => [
                'actions_name'      => '登录验证',
                'template_id'  => 'SMS_53115057',
            ],
            'change_password' => [
                'actions_name'      => '修改密码',
                'template_id'  => 'SMS_53115053',
            ],
            'change_userinfo' => [
                'actions_name'      => '变更信息',
                'template_id'  => 'SMS_53115052',
            ],
        ],
    ],
    'ucloud'       => [
        'public_key'   =>  '',
        'private_key'  =>  '',
        'project_id'   =>  '',
        'base_url'     =>  'https://api.ucloud.cn',
        'sign_name'       => '',
        'actions'       => [
            'register'        => [
                'actions_name'      => '注册验证',
                'template_id'  => 'UTA1910164E29F4',
            ],
            'login'           => [
                'actions_name'      => '登录验证',
                'template_id'  => 'UTA1910164E29F4',
            ],
            'change_password' => [
                'actions_name'      => '修改密码',
                'template_id'  => 'UTA1910164E29F4',
            ],
            'change_userinfo' => [
                'actions_name'      => '变更信息',
                'template_id'  => 'UTA1910164E29F4',
            ],
        ],
    ],
    'qcloud'       => [
        'appid'   =>  '',
        'appkey'  =>  '',
        'sign_name'       => '',
        'actions'       => [
            'register'        => [
                'actions_name'      => '注册验证',
                'template_id'  => '566198',
            ],
            'login'           => [
                'actions_name'      => '登录验证',
                'template_id'  => '566197',
            ],
            'change_password' => [
                'actions_name'      => '修改密码',
                'template_id'  => '566199',
            ],
            'change_userinfo' => [
                'actions_name'      => '变更信息',
                'template_id'  => '566200',
            ],
        ],
    ],
    'qiniu'       => [
        'AccessKey'   =>  '',
        'SecretKey'  =>  '',
        'actions'       => [
            'register'        => [
                'actions_name'      => '注册验证',
                'template_id'  => '1246849772845797376',
            ],
            'login'           => [
                'actions_name'      => '登录验证',
                'template_id'  => '1246849654881001472',
            ],
            'change_password' => [
                'actions_name'      => '修改密码',
                'template_id'  => '1246849964902977536',
            ],
            'change_userinfo' => [
                'actions_name'      => '变更信息',
                'template_id'  => '1246849860733243392',
            ],
        ],
    ],
    'upyun'       => [
        'id'   =>  '',
        'token'  =>  '',
        'apiurl'  =>  '',
        'actions'       => [
            'register'        => [
                'actions_name'      => '注册验证',
                'template_id'  => '2591',
            ],
            'login'           => [
                'actions_name'      => '登录验证',
                'template_id'  => '2592',
            ],
            'change_password' => [
                'actions_name'      => '修改密码',
                'template_id'  => '2590',
            ],
            'change_userinfo' => [
                'actions_name'      => '变更信息',
                'template_id'  => '2589',
            ],
        ],
    ]
];
```

#### 使用示例（基于ThinkPHP6.0）


```php
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
namespace app\home\controller;

use SingKa\Sms\sksms;
use think\facade\Config;

class Index extends Base
{
    /**
    * 短信发送示例
    *
    * @mobile  短信发送对象手机号码
    * @action  短信发送场景，会自动传入短信模板
    * @parme   短信内容数组
    */
    public function sendSms($mobile,$action,$parme)
    {
        //$this->SmsDefaultDriver是从数据库中读取的短信默认驱动
        $SmsDefaultDriver = $this->SmsDefaultDriver ?: 'aliyun'; 
        //$this->SmsConfig是从数据库中读取的短信配置
        $config = $this->SmsConfig ?: Config::get('sms.'.$SmsDefaultDriver);
        $sms = new sksms($SmsDefaultDriver,$config);//传入短信驱动和配置信息
        //判断短信发送驱动，非阿里云和七牛云，需将内容数组主键序号化
        if ($this->SmsDefaultDriver == 'aliyun') {
            $result = $sms->$action($mobile,$parme);
        } elseif ($this->SmsDefaultDriver == 'qiniu') {
            $result = $sms->$action([$mobile],$parme);
        } elseif ($this->SmsDefaultDriver == 'upyun') {
            $result = $sms->$action($mobile,implode('|',$this->restore_array($parme)));
        } else {
            $result = $sms->$action($mobile,$this->restore_array($parme));
        }
        if ($result['code'] == 200) {
            $data['code'] = 200;
            $data['msg'] = '短信发送成功';
        } else {
            $data['code'] = $result['code'];
            $data['msg'] = $result['msg'];
        }
        return $data;
    }
  	
    /**
    * 数组主键序号化
    *
    * @arr  需要转换的数组
    */
    public function restore_array($arr)
    {
        if (!is_array($arr)){
            return $arr;
        }
        $c = 0;
        $new = [];
        foreach ($arr as $key => $value) {
            $new[$c] = $value;
            $c++;
        }
        return $new;
    }
}
```

返回的$result['code']的值等于200，说明短信发送成功，否则可以根据错误码和错误提示去各个云服务查找相关信息。

#### 其他说明

返回的相关错误码请查阅：[Ucloud](https://docs.ucloud.cn/management_monitor/usms/error_code)、[阿里云](https://help.aliyun.com/document_detail/101346.html?spm=a2c4g.11186623.6.621.31fd2246LCMXWw)、[腾讯云](https://cloud.tencent.com/document/product/382/3771)、[七牛云](https://developer.qiniu.com/sms/api/5849/sms-error-code)、[又拍云](https://help.upyun.com/knowledge-base/sms-api-error-code/)

