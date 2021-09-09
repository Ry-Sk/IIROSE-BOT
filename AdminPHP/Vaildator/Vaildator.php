<?php


namespace Vaildator;

use Illuminate\Validation\Factory;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;

class Vaildator extends Factory
{
    private static $message = 'OK';
    private static $headers = [
        'empty'   => '验证规则或验证数据为空',
        'neq_arr' => '验证规则或验证数据不是数组'
    ];

    /***
     * 创建实例
     *
     * @return \Illuminate\Validation\Factory
     */
    public static function getInstance()
    {
        static $validator = null;
        if ($validator === null) {
            $translationPath = ROOT.'/lang';
            $translationLocale = 'en';
            $transFileLoader = new FileLoader(new Filesystem, $translationPath);
            $translator = new Translator($transFileLoader, $translationLocale);
            $validator = new Factory($translator);
        }
        return $validator;
    }

    /**
     * @param array $data
     * @param array $rules    验证规则
     * @param array $messages 验证数据
     * @param array $customAttributes
     * @return bool
     */
    public static function validators(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        if (empty($rules) || empty($data)) {
            self::$message = self::$headers['empty'];
            return false;
        }
        if (is_array($rules) && is_array($data)) {
            $v = self::vmake($rules, $data, $messages, $customAttributes);
            if ($v->fails()) {
                self::$message = $v->messages();
                return false;
            }
            return true;
        }
        self::$message = self::$headers['neq_arr'];
        return false;
    }

    /**
     * 验证实例
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return \Illuminate\Validation\Validator
     */
    private static function vmake(array $data, array $rules, array $messages = [], array $customAttributes = [])
    {
        $v = self::getInstance()->make($data, $rules, $messages, $customAttributes);
        return $v;
    }

    /**
     * 获取错误消息
     *
     * @return string
     */
    public static function getMessage()
    {
        return self::$message;
    }
}
