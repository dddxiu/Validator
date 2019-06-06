# Validator

#### 校验器

```

$input = [
    'name'     => 'dooxxx',
    'birthday' => '1990-01-01',
    'phone'    => '13312341234',
    'gender'   => 'male',
    'interest' => 'basketball,football',
    'mail'     => 'aksjd@qq.com',
    'uid'     => '10000000000100000002',
];


// 注册用户函数
$tb = [
    '10000000000100000000',
    '10000000000100000001',
    '10000000000100000002',
    '10000000000100000003',
];
\Dddxiu\Validator::make('unique', function($input, $field) use ($tb) {
    return !in_array($input[$field], $tb);
}, ':field 必须唯一值!');


// 校验
\Dddxiu\Validator::validate($input,
[
    'name'     => 'r|w[6,20]',
    'birthday' => function($birthday){
        return ((time() - strtotime($birthday))/(3600*24*365))>18;
    },
    'phone'    => 'phoneCn',
    'gender'   => 'r|e[male,female,none]',
    'interest' => 'r|em[basketball,football,rugby,dance]',
    'mail'     => 'r|mail',
    'uid'      => 'r|w20|unique'
],
[
    'name.r' => ':field必须填写',
    'name.w' => ':field长度在:min~:max',
    'uid.unique' => ':field的值必须是唯一的值',
], false);

// 结果
if (!\Dddxiu\Validator::pass()) {
    var_dump(\Dddxiu\Validator::errors());
} else {
    echo "pass\n";
}
```
