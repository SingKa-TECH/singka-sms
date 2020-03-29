<?php
/*配置示例*/
return [
    'aliyun'       => [
        'version'       => '2017-05-25',
        'host'          => 'dysmsapi.aliyuncs.com',
        'scheme'        => 'http',
        'region_id'     => 'cn-hangzhou',
        'access_key'    => 'your aliyun accessKeyId',
        'access_secret' => 'your aliyun accessSecret',
        'sign_name'       => '胜家云',
        'actions'       => [
            'register'        => [
                'actions_name'      => '注册验证',
                'template_id'  => 'SMS_53115055',
                'template_param' => [
                    'code'    => '',
                    'product' => '',
                ],
            ],
            'login'           => [
                'actions_name'      => '登录验证',
                'template_id'  => 'SMS_53115057',
                'template_param' => [
                    'code'    => '',
                    'product' => '',
                ],
            ],
            'change_password' => [
                'actions_name'      => '修改密码',
                'template_id'  => 'SMS_53115053',
                'template_param' => [
                    'code'    => '',
                    'product' => '',
                ],
            ],
            'change_userinfo' => [
                'actions_name'      => '变更信息',
                'template_id'  => 'SMS_53115052',
                'template_param' => [
                    'code'    => '',
                    'product' => '',
                ],
            ],
        ],
    ],
    'ucloud'       => [
        'public_key'   =>  '',
        'private_key'  =>  '',
        'project_id'   =>  '',
        'base_url'     =>  'https://api.ucloud.cn',
        'sign_name'       => '胜家云',
        'actions'       => [
            'register'        => [
                'actions_name'      => '注册验证',
                'template_id'  => 'SMS_67105498',
                'template_param' => [
                    'code'    => '',
                ],
            ],
            'login'           => [
                'actions_name'      => '登录验证',
                'template_id'  => 'SMS_67105500',
                'template_param' => [
                    'code'    => '',
                ],
            ],
            'change_password' => [
                'actions_name'      => '变更验证',
                'template_id'  => 'SMS_67105496',
                'template_param' => [
                    'code'    => '',
                ],
            ],
        ],
    ],
    'qcloud'       => [
        'appid'   =>  '',
        'appkey'  =>  '',
        'sign_name'       => '胜家云',
        'actions'       => [
            'register'        => [
                'actions_name'      => '注册验证',
                'template_id'  => 'SMS_67105498',
            ],
            'login'           => [
                'actions_name'      => '登录验证',
                'template_id'  => 'SMS_67105500',
            ],
            'change_password' => [
                'actions_name'      => '变更验证',
                'template_id'  => 'SMS_67105496',
            ],
        ],
    ]
];