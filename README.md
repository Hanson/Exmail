# Exmail
腾讯企业邮箱API

## Install

`composer require hanson/exmail`

## Notice

具体返回参数请查看 [在线文档](http://wenku.baidu.com/view/71d452dc2cc58bd63186bdd2.html?re=view)

## Usage

```

# 初始化
$exmail = new \Hanccc\Exmail($client_id, $client_secret);

# 获取AuthKey
$authKey = $exmail->getAuthKey($email);

# 单点登录
$link = $exmail->login($email);
<a href="<?= $link ?>">

# 获取未读邮件数量
$count = $exmail->count($email);

# 获取邮箱信息
$user = $exmail->getInfo($email);
echo $user->Name;
echo $user->Gender;
echo $user->Position;
...

# 检查邮箱是否可用
$exmail->check([$email, $email]);

# 增加邮件群组
$exmail->addGroup('tech', 'tech@mail.com');

# 删除邮件群组
$exmail->delGroup('tech@mail.com');

# 增加邮箱到某邮件群组
$exmail->addMember('tech@mail.com', $email);

# 删除某邮件群组的邮箱
$exmail->delMember('tech@mail.com', $email);

# 同步邮件群组
//Exmail::MOD 修改， Exmail::ADD 增加， Exmail::DEL 删除
$exmail->syncParty(\Hanccc\Exmail::DEL, '市场部');

# 列出组织架构
$exmail->listParty();

```
