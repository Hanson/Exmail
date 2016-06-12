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

    static $instance;

    private $clientID;

    private $clientSecret;

    public $token;

    private $client;

    static $timestamp;

    CONST TOKEN_URL = 'https://exmail.qq.com/cgi-bin/token';

    CONST USER_URL = 'http://openapi.exmail.qq.com:12211/openapi/user/get';

    CONST SYNC_URL = 'http://openapi.exmail.qq.com:12211/openapi/user/sync';

    CONST AUTH_URL = 'http://openapi.exmail.qq.com:12211/openapi/mail/authkey';

    CONST LOGIN_URL = 'https://exmail.qq.com/cgi-bin/login';

    CONST COUNT_URL = 'http://openapi.exmail.qq.com:12211/openapi/mail/newcount';

    CONST CHECK_URL = 'http://openapi.exmail.qq.com:12211/openapi/user/check';
    
    CONST LISTEN_URL = 'http://openapi.exmail.qq.com:12211/openapi/listen';

    CONST LIST_USER_URL = 'http://openapi.exmail.qq.com:12211/openapi/user/list';

    CONST LIST_PARTY_URL = 'http://openapi.exmail.qq.com:12211/openapi/party/list';

    CONST SYNC_PARTY_URL = 'http://openapi.exmail.qq.com:12211/openapi/party/sync';

    CONST ADD_GROUP_URL = 'http://openapi.exmail.qq.com:12211/openapi/group/add';

    CONST DEL_GROUP_URL = 'http://openapi.exmail.qq.com:12211/openapi/group/delete';

    CONST ADD_MEMBER_URL = 'http://openapi.exmail.qq.com:12211/openapi/group/addmember';

    CONST DEL_MEMBER_URL = 'http://openapi.exmail.qq.com:12211/openapi/group/deletemember';

    CONST DEL = 1;

    CONST ADD = 2;

    CONST MOD = 3;

    CONST UN_SETTING = 1;

    CONST ALLOW = 2;

    CONST FORBIDDEN= 3;

    CONST MALE = 1;

    CONST FEMALE = 2;

    public function __construct($clientId, $secret)
    {
        $this->clientID = $clientId;
        $this->clientSecret = $secret;
        $this->client = new Client();
        $this->token = $this->getToken();
    }

    public static function getInstance($clientId, $secret)
    {
        if(!self::$instance || (time() - self::$timestamp) > 86400){
            echo 'time:' . time() . PHP_EOL;
            echo 'timestamp:' . self::$timestamp . PHP_EOL;
            echo(time() - self::$timestamp);
            self::$instance = new Exmail($clientId, $secret);
            return self::$instance;
        }else{
            return self::$instance;
        }
    }

    public function __toString()
    {
        return 'exmail';
    }

    private function getToken()
    {
        $response = $this->sendRequest('POST', self::TOKEN_URL, [
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
        ]);

        $data = json_decode($response);

        self::$timestamp = $data->expires_in;

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
     * @return string
     */
    public function getAuthKey($email)
    {
        $response = $this->sendRequest('POST', self::AUTH_URL, [
            'alias' => $email,
            'access_token' => $this->token,
        ]);
        echo json_decode($response)->auth_key;

        return json_decode($response)->auth_key;
    }

    public function login($email)
    {
        $response = $this->sendRequest('GET', self::LOGIN_URL, [
            'ticket' => $this->getAuthKey($email),
//            'access_token' => $this->token,
            'user' => $email,
            'agent' => $this->clientID,
            'fun' => 'bizopenssologin',
            'method' => 'bizauth',
        ]);

        return $response;
    }

    public function sync($data, $isArray = false)
    {
        array_push($data, ['access_token' => $this->token]);

        $response = $this->sendRequest('POST', self::SYNC_URL, $data);

        return json_decode($response, $isArray);
    }

    /**
     * 获取未读邮件
     * @param $email
     * @param bool $isArray
     * @return mixed
     */
    public function count($email, $isArray = false)
    {
        $response = $this->sendRequest('POST', self::COUNT_URL, [
            'alias' => $email,
            'access_token' => $this->token
        ]);

        return json_decode($response, $isArray);
    }

    /**
     * 检查邮件帐号是否可用
     * @param $emails
     * @param bool $isArray
     * @return mixed
     */
    public function check($emails, $isArray = false)
    {
        $url = self::CHECK_URL . '?' .'email=' . implode('&email=', $emails) . '&access_token=' . $this->token;

        $response = $this->client->get($url)->getBody()->getContents();

        return json_decode($response, $isArray);
    }

    public function syncParty($action, $disPath, $srcPath = null, $isArray = false)
    {
        $response = $this->sendRequest('POST', self::SYNC_PARTY_URL, [
            'Action' => $action,
            'DstPath' => $disPath,
            'SrcPath' => $srcPath,
            'access_token' => $this->token
        ]);

        return json_decode($response, $isArray);
    }

    public function listParty($partyPath = null, $isArray = false)
    {
        $response = $this->sendRequest('POST', self::LIST_PARTY_URL, [
            'partypath' => $partyPath,
            'access_token' => $this->token
        ]);

        return json_decode($response, $isArray);
    }

    public function addGroup($name, $email, $member = null, $isArray = false)
    {
        $response = $this->sendRequest('POST', self::ADD_GROUP_URL, [
            'group_name' => $name,
            'group_admin' => $email,
            'status' => 'all',
            'members' => $member,
            'access_token' => $this->token
        ]);

        return json_decode($response, $isArray);
    }

    public function delGroup($alias, $isArray = false)
    {
        $response = $this->sendRequest('POST', self::DEL_GROUP_URL, [
            'group_alias' => $alias,
            'access_token' => $this->token
        ]);

        return json_decode($response, $isArray);
    }

    public function addMember($alias, $email, $isArray = false)
    {
        $response = $this->sendRequest('POST', self::ADD_MEMBER_URL, [
            'group_alias' => $alias,
            'members' => $email,
            'access_token' => $this->token
        ]);

        return json_decode($response, $isArray);
    }

    public function delMember($alias, $email, $isArray = false)
    {
        $response = $this->sendRequest('POST', self::DEL_MEMBER_URL, [
            'group_alias' => $alias,
            'members' => $email,
            'access_token' => $this->token
        ]);

        return json_decode($response, $isArray);
    }
}