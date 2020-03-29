<?php


namespace singka\sms;

use Qcloud\Sms\SmsSingleSender;

class Qcloud
{
    protected $config;
    protected $sms;
    protected static $snakeCache = [];

    public function __construct($config=[])
    {
        $this->config = array_merge($this->config, $config);
        if (empty($this->config['appid']) || empty($this->config['appkey'])) {
            $data['code'] = 103;
            $data['msg'] = '请在后台设置appid和appkey';
            return $data;
            exit();
        } else {
            $this->sms = new SmsSingleSender($this->config['appid'], $this->config['appkey']);
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
        $phoneNumbers   = $arguments[0];
        $params        = $arguments[1];
        $templateId  = $conf['template_id'];
        $smsSign      = $this->config['sign_name'];
        $templateParam = $conf['template_param'];
        foreach ($templateParam as $k => $v) {
            $templateParam[$k] = empty($params[$k]) ? '' : $params[$k];
        }
        return $this->sms->sendWithParam("86", $phoneNumbers, $templateId, $templateParam, $smsSign, "", "");
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