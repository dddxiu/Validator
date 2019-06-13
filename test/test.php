<?php

include dirname(__DIR__).'/vendor/autoload.php';

$input = [
    'name'     => 'doo`xxx', // 多了反引符号
    'birthday' => '1990-01-01',
    'phone'    => '13312341234',
    'gender'   => 'males', // 多了males
    'interest' => 'basketball,football',
    'mail'     => 'aksjd_qq.com', // 没有 @
    'uid'      => '100000`00000100000002', // 仅需要19位,且位w
    'no'       => '100000`00000100000002', // 必须有
];


// 注册用户函数
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
$valid_fun = function($input, $field, $rule_val, &$next, &$prev) use ($data) {
    return !in_array($input[$field], $data[$rule_val[0]][$rule_val[1]]);
};
\Dddxiu\Validator::make('unique', $valid_fun, ':field 必须唯一值!');


// 校验:
\Dddxiu\Validator::validate($input,
[
    'name'     => 'r|w|bt:6,20|unique:tb,id',
    'phone'    => 'phoneCN',
    'gender'   => 'r|e:male,female,none',
    'interest' => 'r|multi:basketball,football,rugby,dance',
    'mail'     => 'r|mail',
    'uid'      => 'r|w|len:19',
    'no'       => 'r|i',
],
[
    'name.r' => ':field 必须填写!!!',
    'name.w' => ':field 是英文字母和数字!!!',
    'uid.unique' => ':field 的值必须是唯一的值',
], true);

// 结果
if (!\Dddxiu\Validator::pass()) {
    var_dump(\Dddxiu\Validator::errors(true));
} else {
    echo "pass\n";
}


// vc vp ve