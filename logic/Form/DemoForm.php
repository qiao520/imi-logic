<?php
/**
 * User: Roers
 * Date: 2019/6/30
 * Time: 10:55
 */
declare(strict_types=1);

namespace Logic\Form;
echo 'dada';
use Roers\SwLogic\BaseForm;

class DemoForm extends BaseForm
{
    public $name;
    public $age;
    public $sex;

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
            ['name', 'validateName'],
            // 还可以这样用，对多个字段用同一个验证器规则
            [['age', 'sex'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '名字2',
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

        $isNameExist = $this->findNameFromDb($value);

        if ($isNameExist) {
            $this->addError($attribute, "名字{$value}已存在");
            return false;
        }

        return true;
    }

    /**
     * 从数据库查询用户是否存在
     * @param $name
     * @return bool
     */
    private function findNameFromDb($name) {
        return $name == 'Roers.cn';
    }
}