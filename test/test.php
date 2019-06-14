<?php

include dirname(__DIR__).'/vendor/autoload.php';

$input = [
    'realname' => 'Dddxiu', // 中文
    'nickname' => 'dooxxx', // 多了反引符号
    'birthday' => '1990-01-01 00:00:00',
    'phone'    => '13312341234',
    'gender'   => 'males', // 多了males
    'interest' => 'basketball,football',
    'mail'     => 'aksjd_qq.com', // 没有 @
    'uid'      => '100000`00000100000002', // 仅需要19位,且位w
    'no'       => '100000`00000100000002', // 必须有
    'weight'   => '62', // 浮点
    'url_p'      => 'http://www.baidu.com',
    'url_e'      => 'www.baidu.com',
    'json_p'     => '{"name":"zhangsan"}',
    'json_e'     => '"name":"zhan"',
    'ip_p'       => '255.255.255.255',
    'ip_e'       => '255.1.',
    'regx_p'     => '55555',
    'regx_e'     => '555555',
    'ts_p'       => '1560501372',
    'ts_e'       => '99560501372',
];

// birthday
$valid_birthday = function($input, $field, $layer, $args)
{
    // $args 可以使用date检查,当前校对器也可以检查
    // TODO:在这儿检查就可以忽略date规则
    $age  = (time() - strtotime($input[$field]))/3600/24/365;
    $pass = $age > 18;
    if ($pass) {
        return $layer::then();
    }
    return $pass;
};
\Dddxiu\Validator::make('birthday', $valid_birthday, ':field 成年人才行!');

// unique
$data = [
    'tb' => [
        'id' => [
            '10000000000100000000',
            '10000000000100000001',
            '10000000000100000002',
            '10000000000100000003',
        ]
    ]
];
$valid_fun = function($input, $field, $layer, $args) use ($data) {
    $pass = in_array($input[$field], $data[$args[0]][$args[1]]);
    if ($pass) {
        return $layer::then();
    }
    return false;
};
\Dddxiu\Validator::make('unique', $valid_fun, ':field 必须唯一值!');


// 校验:
vv($input,
[
    'realname' => 'r|zh|bt:2,5',
    'nickname' => 'r|w|bt:6,20',
    'birthday' => 'r|date:Y-m-d|birthday',
    'phone'    => 'phoneCN',
    'gender'   => 'r|e:male,female,none',
    'interest' => 'r|multi:basketball,football,rugby,dance',
    'mail'     => 'r|mail',
    'uid'      => 'r|w|len:19|unique:tb,id',
    'no'       => 'r|i',
    'weight'   => 'r|f',
    'url_p'      => 'url',
    'url_e'      => 'url',
    'json_p'     => 'json',
    'json_e'     => 'json',
    'ip_p'       => 'ip',
    'ip_e'       => 'ip',
    'regx_p'     => 'regx:/^\d{5}$/',
    'regx_e'     => 'regx:/^\d{5}$/',
    'ts_p'       => 'ts',
    'ts_e'       => 'ts',
],
[
    'name.r' => ':field 必须填写!!!',
    'name.w' => ':field 是英文字母和数字!!!',
    'uid.unique' => ':field 的值必须是唯一的值',
], true);

// 结果
if (!vp()) {
    var_dump(ve(true));
} else {
    echo "pass\n";
}


// vc vp ve