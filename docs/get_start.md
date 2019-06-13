
# 使用说明


初始化加载 自定义验证 类型



### 规则映射表

|字符|英文|解释|示例|扩展|
|---|---|---|---|---|
|r|required|必须填写,即isset|`r`|无|
|n|nullable|可以为空,默认值|`r`|无|
|a|alpha|希腊字母|`alpha`|同`s`|
|w|[A-Za-z0-9]|字符|`w`|同`s`|
|s|string|任何字符|`s`|`s`|
|d|digit|包含浮点数|`d`|无|
|i|integer|整数|`i`|无|
|f|folat|浮点数|`f`|无|
|e|enum|枚举|`e:male,female,none`|单选`e:male,female,none`|
|multi|multi|枚举多选|`multi:male,female,none`|多选`multi:male,female,none`|
|mail|mail|邮件|`mail`|无|
|date|date|strtotime|`date:Y-m-d`|无|
|ts|timestamp|有效的unix|`ts`|偏移年限`ts:100`|
|url|url|有效的`http://demo.com`|`url`|无|
|json|json|json_decode|`json`|无|
|ip|ip|ip|`ip`|无|
|regx|regx|正则|`regx:/\w{6,20}/isU`|无|
|zh|zh|汉字|`zh`|无|
|phoneCN|phoneCN|中文|`zh`|无|
|len|length|指定长度|`len:6`|无|
|bt|between|之间(包含)|`bt:5,12`|数字大小,字符长短,时间早晚|
|min|min|指定长度|`min:6`|数字大小,字符长短,时间早晚|
|max|max|指定长度|`max:6`|数字大小,字符长短,时间早晚|

