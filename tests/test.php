<?php
/**
 * Created by PhpStorm.
 * User: hanson
 * Date: 16/6/1
 * Time: 上午10:24
 */

require __DIR__ . './../vendor/autoload.php';

$client = 'your client';
$secret = 'your secret';

$testMail = 'test email';

$exmail = new \Hanccc\Exmail($client, $secret);


print_r($exmail->getAuthKey($testMail));


print_r($exmail->login($testMail));


print_r($exmail->count($testMail));


print_r($exmail->getInfo($testMail));


print_r($exmail->check([$testMail, $testMail]));


print_r($exmail->count($testMail));


print_r($exmail->addGroup('tech1112', 'tech113@job1.xyz'));


print_r($exmail->delGroup('tech1@job1.xyz'));


print_r($exmail->addMember('tech13@job1.xyz', $testMail));


print_r($exmail->delMember('tech13@job1.xyz', $testMail));


print_r($exmail->syncParty(\Hanccc\Exmail::DEL, '市场部'));


print_r($exmail->listParty());