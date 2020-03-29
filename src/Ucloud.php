<?php


namespace singka\sms;

use Singka\UcloudSms\UcloudApiClient;

class Ucloud
{
    protected $config;
    protected $sms;
    protected static $snakeCache = [];

    public function __construct($config=[])
    {
        $this->config = array_merge($this->config, $config);
        if (empty($this->config['public_key']) || empty($this->config['private_key']) || empty($this->config['project_id'])) {
            $data['code'] = 103;
            $data['msg'] = '请在后台设置PUBLIC_KEY和PRIVATE_KEY';
            return $data;
            exit();
        } else {
            $this->sms = new UcloudApiClient($this->config['base_url'], $this->config['public_key'], $this->config['private_key'], $this->config['project_id']);
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
        }
        $conf          = $this->config['actions'][$name];
        $params['Action'] = "SendUSMSMessage";
        $phoneNumbers   = $arguments[0];
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
        return $this->sms->get("/", $params);
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