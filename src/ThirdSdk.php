<?php

namespace YeThird;

/**
 * Class Third
 * @package YeThird\ThirdSdk
 */
class ThirdSdk
{
    private $options;

    public function __construct(array $options)
    {
        if (!$options['appid'] || !$options['secret'] || !$options['gateway']) {
            throw new \Exception("Third pay init fail!");
        }
        $this->options = $options;
    }

    /**
     * Define env method similar to laravel's
     *
     * @param String $env_param | Environment Param Name
     *
     * @return String | Actual Param
     */
    public function env(string $env_param)
    {
        return $this->options[$env_param];
    }

    /**
     * 请求代收
     */
    public function b2c(array $data)
    {
        $url = $this->env('gateway') .'/api/order/another';
        $data['appid'] = $this->env('appid');
        $requestData = $this->singParam($data);
        $curl_response = $this->httpRequest($url, $requestData);
        return $curl_response;
    }
    
    /**
     * 请求代收
     */
    public function b2cQuery(string $order_no)
    {
        $url = $this->env('gateway') .'/api/order/check_another';
        $data = [
            'appid' => $this->env('appid'),
            'order_no' => $order_no,
        ];
        $requestData = $this->singParam($data);
        $curl_response = $this->httpRequest($url, $requestData);
        return $curl_response;
    }
        
    /**
     * 请求代收
     */
    public function c2b(array $data)
    {
        $url = $this->env('gateway') .'/api/order/unified';
        $data['appid'] = $this->env('appid');
        $requestData = $this->singParam($data);
        $curl_response = $this->httpRequest($url, $requestData);
        return $curl_response;
    }
    
    /**
     * 请求代收
     */
    public function c2bQuery(string $order_no)
    {
        $url = $this->env('gateway') .'/api/order/check_another';
        $data = [
            'appid' => $this->env('appid'),
            'order_no' => $order_no,
        ];
        $requestData = $this->singParam($data);
        $curl_response = $this->httpRequest($url, $requestData);
        return $curl_response;
    }


    /**
     * 获取用户余额
     */
    public function accountBalance()
    {
        $url = $this->env('gateway') .'/api/order/purse';
        $data = [
            'appid' => $this->env('appid')
        ];
        $requestData = $this->singParam($data);
        $curl_response = $this->httpRequest($url, $requestData, false);
        return $curl_response;
    }

    /**
     * http请求
     * @param $url
     * @param $token
     * @param bool $isPost
     * @param null $postData
     * @return bool|string
     */
    protected function httpRequest($url, $postData = null, $isPost = true)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);

        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); //信任任何证书
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        if ($isPost) {
            curl_setopt($curl, CURLOPT_POST, $isPost);
        } else {
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
        }
        if (!empty($postData)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $src = curl_exec($curl);
        curl_close($curl);

        $url_parsed = parse_url($url);
        return $src;
    }

    private function singParam(array $param)
    {
        $this->clearNull($param);
        unset($param['sign'], $param['is_jump']);

        ksort($param);
        $param2 = [];
        foreach ($param as $k => $v) {
            $param2[] = $k . '=' . $v;
        }
        $param2[] = 'secret=' . $this->env('secret');
        $param['sign'] = strtolower(md5(implode('&', $param2)));
        return json_encode($param);
    }

    private function clearNull(&$data = '')
    {
        if ($data === null || $data === false) {
            $data = '';
        }
        if (is_array($data) && !empty($data)) {
            foreach ($data as &$v) {
                if ($v === null || $v === false) {
                    $v = '';
                } elseif (is_array($v)) {
                    $this->clearNull($v);
                } elseif (is_string($v) && stripos($v, '.') === 0) {
    				// $v = '0'.$v;
                }
            }
        }
    }
}
