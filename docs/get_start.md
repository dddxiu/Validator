
# 使用说明

```
// 自定义验证器
// phone
Validator::make('phone', function($arg){
    if (preg_match("/^1[34578]\d{9}$/", $arg)) {
        return true;
    }
    return '手机号不合法!';
});
// unique:tb_user,id
Validator::make('unique', function($arg, $rule_args) use ($db){
    list($tb, $field) = explode(',', $rule_args);
    $ret = $db($tb)->where("{$field}={$arg}")->find();
    if ($ret) {
        return true;
    }
    return '已经存在信息了!';
});

// 验证 第三个参数或第四个参数直接给true,会直接返回json格式错误,不会继续往下走
$ret = Validator::validate($input,
[
    'name' => 'r|s[6,10]',
    'age'  => function($age){return string|boolean;},
    'phone'=> 'r|phone|unique:t_user,phone',
],
[
    name.r => '必须填写',
    name.s => '长度在6~10'
], false);
if ($ret !== false) {
    var_dump($ret);
    exit;
}
```

初始化加载 自定义验证 类型



### 规则映射表

|字符|英文|解释|示例|扩展|
|---|---|---|---|---|
|r|required|必须填写,即isset|`r`|无|
|n|nullable|可以为空,默认值|`r`|无|
|s|string|任何字符|`s`|`s[2,3]`,`s[2,3)`,`s(2,3]`,`s3`|
|d|digit|数字, 即is_numeric|`r`|同s,是数字范围大小,非数字长短|
|w|[A-Za-z0-9_]|字符|`w`|同`s`|
|e|enum|枚举|`e[male,female,none]`|多选`e[male,female,none]m`|
|mail|mail|邮件|`email`|无|
|date|date|strtotime|`date:Y-m-d`|格式,默认兼容strtotime|
|ts|timestamp|有效的unix|`ts`|偏移年限`ts[100, 10000]`|
|url|url|有效的`http://demo.com`|`url`|无|
|json|json|json_decode|`json`|无|
|ip|ip|ip|`ip`|无|
|regx|regx|正则|`regx:/\w{6,20}/isU`|无|
|alpha|alpha|希腊字母|`alpha`|同s|
|zh|zh|汉字|`zh`|同s|

