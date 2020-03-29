# ThinkPHP集成短信发送平台

#### 介绍
本项目是集成了各大云服务厂商的短信业务平台，支持ThinkPHP5.0、ThinkPHP5.1和ThinkPHP6.0，由宁波晟嘉网络科技有限公司维护，目前支持阿里云、腾讯云和Ucloud，接下来将接入华为云、七牛云等国内较大的公有云服务厂商。

#### 安装教程

使用 `composer require singka/singka-sms` 命令行安装即可。

安装完成后会自动生成 `config/sms.php` 配置文件，内容如下：

```
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
    ]
];
```

#### 使用示例（基于ThinkPHP6.0）


```
<?php
namespace app\home\controller;

use SingKa\Sms\sksms;
use think\facade\Config;

class Index
{
	public function test()
	{
		//腾讯云短信发送示例
		$type = 'qcloud';
        $config = Config::get('sms.'.$type);
        $sms = new sksms($type,$config);
        //可以根据不同的actions设置场景化验证，比如登录、注册、重置密码等
        $result = $sms->register('13868680000',['987654']);
        return json($result);
        //阿里云短信发送示例，注意阿里云的发送数组要根据你的短信模板传入数组的键名，否则将会报错，腾讯云和Ucloud则不需要传入键名，按照顺序将数组排列即可
		$type = 'aliyun';
        $config = Config::get('sms.'.$type);
        $sms = new sksms($type,$config);
        $result = $sms->login('13868680000',['code'=>'987654']);
        return json($result);
        //ucloud短信发送示例
        $type = 'ucloud';
        $config = Config::get('sms.'.$type);
        $sms = new sksms($type,$config);
        $result = $sms->change_password('13868680000',['987654']);
        return json($result);
    }
```

返回的$result数组中如果code=200，说明短信发送成功，否则可以根据错误码和错误提示去各个云服务查找相关信息。

#### 其他说明

返回的相关错误码请查阅：[Ucloud](https://docs.ucloud.cn/management_monitor/usms/error_code)、[阿里云](https://help.aliyun.com/document_detail/101346.html?spm=a2c4g.11186623.6.621.31fd2246LCMXWw)、[腾讯云](https://cloud.tencent.com/document/product/382/3771)