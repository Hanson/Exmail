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

    CONST SYNC_URL = 'https://exmail.qq.com/openapi/user/sync';

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

    public function sync($data)
    {
        $response = $this->sendRequest('POST', self::USER_URL, $data);

        return json_decode($response);
    }

    public function isNameEqualToEmailName($name, $email)
    {
        return $this->exmailValidate($name, $email);
    }

    public function exmailValidate($name, $email)
    {
        $response = $this->getEmailInfo($email);

        return (property_exists($response, 'Name') && $response->Name === $name) ? true : false;
    }

}