# 概述

IMI框架很优秀，但有点过渡使用注解了（个人意见哈），我希望能有一个开发调试方便的一个组件专门来做业务开发，所以我另外开发了swoole-logic组件。
这个仓库是IMI框架结合swoole-logic组件的使用示例。可以clone下来直接看演示效果。

# 什么是swoole-logic组件

为swoole框架设计的务逻辑封装组件，将业务代码抽离出来以减少控制器代码量、代码重载，同时封装了高性能表单数据验证。

我们可以这么理解，一个请求是一个业务，一个业务会对应由一个Form表单类去封装处理。
一个健全的系统少不了请求参数数据验证、易维护性。这个组件就是为了帮您轻松做这些事情。

主要是解决如下2个问题：
  - Logic层热加载，修改业务代码后不用重启能立即生效
  - 高性能、方便使用的表单验证

---

# 如何实现Logic层热加载

> 我将业务逻辑层（Logic）抽离独立出来，该层不受服务启动时扫描，会在work进程启动后进行加载，所以，可以通过$server->reload接口对代码进行重载，达到热加载效果，不用频繁重启服务，以加快开发调试速度。

操作步骤如下：
1. 下载该仓库代码：git clone https://github.com/qiao520/imi-logic
2. 下载依赖包：composer install
注意：如果出现安装不了qiao520/swoole-logic，请先执行这个设置命令：composer config repositories.qiao520/swoole-logic vcs https://github.com/qiao520/swoole-logic
3. 启动服务（具体自行操作）
4. 然后给你的IDE配置启动项，新增一个“PHP HTTP Request”启动项，具体设置，自行摸索，摸索了还不行，请联系我（380552499）
5. 启动IMI服务，浏览器访问http://192.168.99.100:8080/logic
6. 修改\logic\Form\DemoForm.php逻辑代码，然后点击IDE上的run按钮（快捷键shift+f10）或者浏览器请求（http://192.168.99.100:8080/reload）
7. 然后再到浏览器访问http://192.168.99.100:8080/logic，代码秒重载

注：192.168.99.100是我window电脑上安装的虚拟机ip

# 表单验证使用示例

组件仓库：https://github.com/qiao520/swoole-logic
组件安装：composer require qiao520/swoole-logic:~1.0.0

表单Form类（示例代码）
```
<?php
declare(strict_types=1);

namespace Roers\Demo;

use Roers\SwLogic\BaseForm;

class DemoForm extends BaseForm
{
    // 以下是表单属性
    public $name;
    public $email;
    public $age;
    public $sex;
    public $others;
    public $default = 0;

    // 以下是覆盖父类的默认设置
    protected $isAutoTrim = true;   // 开启自动去空格（默认开启）
    protected $defaultRequired = true;   // 开启所有属性为必填（默认未开启）
    protected $defaultErrorMessage = '{attribute}格式错误';  // 覆盖自定义错误提示信息

    /**
     * 定义验证规则
     * @return array
     */
    public function rules()
    {
        return [
            // 验证6到30个字符的字符串
            ['name', 'string', 'min' => 6, 'max' => 30, 'maxMinMessage' => '名字必须在{min}~{max}个字符范围内'],
            // 验证年龄必须是整数
            ['age', 'integer', 'min' => 18, 'max' => 100],
            // 集合验证器，验证性别必须是1或2
            ['sex', 'in', 'in' => [1, 2],],
            // 使用自定义验证器，验证名字不能重复
            ['name', 'validateName'],
            // 还可以这样用，对多个字段用同一个验证器规则
            [['age', 'sex'], 'integer'],
            // 验证邮箱格式，并且必填required对所有校验器都有效
            [['email'], 'email', 'required' => true],
            // 验证是否是数组，并对数组元素进行格式校验
            [['others'], 'array', 'validator' => 'string'],
        ];
    }

    /**
     * 字段名称映射关系
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'name' => '名字',
            'age' => '年龄',
        ];
    }

    /**
     * 业务处理
     * @return array
     */
    public function handle()
    {
        // do something here

        // 返回业务处理结果
        return ['name' => $this->name, 'age' => $this->age];
    }

    /**
     * 自定义验证器
     * @param $attribute
     * @param $options
     * @return bool
     */
    public function validateName($attribute, $options)
    {
        $value = $this->{$attribute};

        if ($value == 'Roers.cn') {
            $this->addError($attribute, "名字{$value}已存在");
            return false;
        }

        return true;
    }
}

```
---
demo.php
```
<?php
use Roers\Demo\DemoForm;

function debug($msg) {
    echo $msg, PHP_EOL;
}

// 表单提交的数据
$data = [
    'name' => 'zhongdalong',
    'age' => '31',
    'sex' => '',
];
// 演示默认所有字段为非必填项
$form = DemoForm::instance($data);
if ($form->validate()) {
    $result = $form->handle();
    debug('验证通过，业务处理结果：' . json_encode($result));
} else {
    debug('验证不通过，错误提示信息：' .  $form->getError());
}

debug(str_repeat('-------', 10));

// 演示默认所有字段为必填项
$form = DemoForm::instance($data, true);
if ($form->validate()) {
    $result = $form->handle();
    debug('验证通过，业务处理结果：' . json_encode($result));
} else {
    debug('验证不通过，错误提示信息：' .  $form->getError());
}

debug(str_repeat('-------', 10));


// 演示未成年注册场景
$data['age'] = 17;
$form = DemoForm::instance($data);
if ($form->validate()) {
    $result = $form->handle();
    debug('验证通过，业务处理结果：' . json_encode($result));
} else {
    debug('验证不通过，错误提示信息：' .  $form->getError());
}

debug(str_repeat('-------', 10));


// 演示自定义验证器
$data['age'] = 18;
$data['name'] = 'Roers.cn';
$form = DemoForm::instance($data);
if ($form->validate()) {
    $result = $form->handle();
    debug('验证通过，业务处理结果：' . json_encode($result));
} else {
    debug('验证不通过，错误提示信息：' .  $form->getError());
}

debug(str_repeat('-------', 10));
```
---
执行结果
```
验证通过，业务处理结果：{"name":"zhongdalong","age":"31"}
----------------------------------------------------------------------
验证不通过，错误提示信息：Sex是必填项
----------------------------------------------------------------------
验证不通过，错误提示信息：年龄必须在18 ~ 100范围内
----------------------------------------------------------------------
验证不通过，错误提示信息：名字Roers.cn已存在
----------------------------------------------------------------------
```


# 最后

喜欢的朋友点个赞，有什么意见想法或bug，联系我QQ：380552499