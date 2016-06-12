# Exmail
腾讯企业邮箱API


## Usage

```

#初始化
$exmail = new \Hanccc\Exmail($client, $secret);

# 获取AuthKey
print_r($exmail->getAuthKey($testMail));

# 单点登录
print_r($exmail->login($testMail));

# 获取未读邮件数量
print_r($exmail->count($testMail));

# 获取邮箱信息
print_r($exmail->getInfo($testMail));

# 检查邮箱是否可用
print_r($exmail->check([$testMail, $testMail]));

# 增加邮件群组
print_r($exmail->addGroup('tech1112', 'tech113@job1.xyz'));

# 删除邮件群组
print_r($exmail->delGroup('tech1@job1.xyz'));

# 增加邮箱到某邮件群组
print_r($exmail->addMember('tech13@job1.xyz', $testMail));

# 删除某邮件群组的邮箱
print_r($exmail->delMember('tech13@job1.xyz', $testMail));

# 同步邮件群组
print_r($exmail->syncParty(\Hanccc\Exmail::DEL, '市场部'));

# 列出组织架构
print_r($exmail->listParty());

```