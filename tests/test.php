<?php
/**
 * Created by PhpStorm.
 * User: hanson
 * Date: 16/6/1
 * Time: 上午10:24
 */

require __DIR__ . './../vendor/autoload.php';

$client = 'hanccc';
$secret = '4b3a04ba2bc85d4412f13d882496c8f3';

$testMail = 'hanson@job1.xyz';

$exmail = new \Hanccc\Exmail($client, $secret);

//pass
//print_r($exmail->token);

//print_r($exmail->sync(['action' => \Hanccc\Exmail::MOD, 'alias' => 'hanson@job1.xyz', 'name' => 'hanccc', 'gender' => 1, 'position' => 'ceo', 'slave' => 'han', 'access_token' => $exmail->token]));

//pass
//print_r($exmail->getAuthKey($testMail));
print_r($exmail->login($testMail));

//pass
//print_r($exmail->count($testMail));

//pass
//print_r($exmail->getInfo($testMail));

//pass
//print_r($exmail->check([$testMail, $testMail]));
