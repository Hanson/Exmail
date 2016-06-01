<?php
/**
 * Created by PhpStorm.
 * User: hanson
 * Date: 16/6/1
 * Time: 上午10:19
 */

namespace Hanccc;


use GuzzleHttp\Client;

class Exmail
{
    private $clientID;

    private $clientSecret;

    public $token;

    private $client;

    CONST TOKEN_URL = 'https://exmail.qq.com/cgi-bin/token';

    CONST USER_URL = 'http://openapi.exmail.qq.com:12211/openapi/user/get';

    CONST SYNC_URL = 'http://openapi.exmail.qq.com:12211/openapi/user/sync';

    CONST AUTH_URL = 'http://openapi.exmail.qq.com:12211/openapi/mail/authkey';

    CONST LOGIN_URL = 'https://exmail.qq.com/cgi-bin/login';

    CONST COUNT_URL = 'http://openapi.exmail.qq.com:12211/openapi/mail/newcount';

    CONST CHECK_URL = 'http://openapi.exmail.qq.com:12211/openapi/user/check';

    CONST DEL = 1;

    CONST ADD = 2;

    CONST MOD = 3;

    public function __construct($clientId, $secret)
    {
        $this->clientID = $clientId;
        $this->clientSecret = $secret;
        $this->client = new Client();
        $this->token = $this->getToken();
    }

    private function getToken()
    {
        $response = $this->sendRequest('POST', self::TOKEN_URL, [
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
        ]);

        return json_decode($response)->access_token;
    }

    private function sendRequest($method, $url, $query = [])
    {
        return $this->client->request($method, $url, [
            'query' => $query
        ])->getBody()->getContents();
    }

    public function getInfo($email)
    {
        $response = $this->sendRequest('POST', self::USER_URL, [
            'alias' => $email,
            'access_token' => $this->token,
        ]);

        return json_decode($response);
    }

    /**
     * 获取 Authkey
     * @param $email
     * @return mixed
     */
    public function getAuthKey($email)
    {
        $response = $this->sendRequest('POST', self::AUTH_URL, [
            'alias' => $email,
            'access_token' => $this->token,
        ]);

        return json_decode($response)->auth_key;
    }

    public function login($email)
    {
        $response = $this->sendRequest('POST', self::LOGIN_URL, [
            'ticket' => $this->getAuthKey($email),
            'access_token' => $this->token,
            'user' => $email,
            'agent' => $this->clientID,
            'fun' => 'bizopenssologin',
            'method' => 'bizauth',
        ]);

        return $response;
    }

    public function sync($data, $array = false)
    {
        array_push($data, ['access_token' => $this->token]);

        $response = $this->sendRequest('POST', self::SYNC_URL, $data);

        return json_decode($response, $array);
    }

    /**
     * 获取未读邮件
     * @param $email
     * @param bool $array
     * @return mixed
     */
    public function count($email, $array = false)
    {
        $response = $this->sendRequest('POST', self::COUNT_URL, [
            'alias' => $email,
            'access_token' => $this->token
        ]);

        return json_decode($response, $array);
    }

    /**
     * 检查邮件帐号是否可用
     * @param $emails
     * @param bool $array
     * @return mixed
     */
    public function check($emails, $array = false)
    {
        $url = self::CHECK_URL . '?' .'email=' . implode('&email=', $emails) . '&access_token=' . $this->token;

        $response = $this->client->get($url)->getBody()->getContents();

        return json_decode($response, $array);
    }
}