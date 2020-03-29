<?php


namespace singka\sms;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class Aliyun
{
    protected $config;
    protected $error             = '没有错误';
    protected static $snakeCache = [];

    public function __construct($config = [])
    {
        $this->config = array_merge($this->config, $config);
        if (empty($this->config['access_key']) || empty($this->config['access_secret'])) {
            $data['code'] = 103;
            $data['msg'] = '请在后台设置accessKeyId和accessKeySecret';
            return $data;
            exit();
        } else {
            AlibabaCloud::accessKeyClient($this->config['access_key'], $this->config['access_secret'])
                ->regionId($this->config['region_id'])
                ->asDefaultClient();
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
        $phoneNumber   = $arguments[0];
        $params        = $arguments[1];
        $templateCode  = $conf['template_id'];
        $signName      = $this->config['sign_name'];
        $templateParam = $conf['template_param'];
        foreach ($templateParam as $k => $v) {
            $templateParam[$k] = empty($params[$k]) ? '' : $params[$k];
        }
        return $this->send($phoneNumber, $signName, $templateCode, $templateParam);
    }

    public function send(string $phoneNumber, string $signName, string $templateCode, array $templateParam = [])
    {
        $action = 'SendSms';
        $query  = [
            'PhoneNumbers'  => $phoneNumber,
            'SignName'      => $signName,
            'TemplateCode'  => $templateCode,
            'TemplateParam' => json_encode($templateParam),
        ];
        return $this->request($action, $query);
    }

    public function request(string $action, array $query = [])
    {
        $query['RegionId'] = $this->config['region_id'];
        try {
            $result = AlibabaCloud::rpc()
                ->product($this->config['api'])
                ->scheme($this->config['scheme']) // https | http
                ->version($this->config['version'])
                ->action($action)
                ->method('POST')
                ->host($this->config['host'])
                ->options([
                    'query' => $query,
                ])
                ->request();
            $result->toArray();
            if ($result['Code'] != 'OK') {
                throw new \Exception($result['Message']);
            }
            return $result;
        } catch (ClientException $e) {
            $this->error = $e->getErrorMessage();
        } catch (ServerException $e) {
            $this->error = $e->getErrorMessage();
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
        return false;
    }

    public function getError(): string
    {
        return $this->error;
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