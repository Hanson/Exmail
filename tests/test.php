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

print_r($exmail->token);
print_r($exmail->getInfo($testMail));

//$exmail->sync(['Action' => \Hanccc\Exmail::MOD, 'Alias' => 'hanson@job1.xyz', 'Name' => 'hanccc']);
