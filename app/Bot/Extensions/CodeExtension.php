<?php


namespace Bot\Extensions;

trait CodeExtension
{
    public static function decode($data)
    {
        $s = [];
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $s[$k] = self::decode($v);
            }
            return $s;
        }
        return html_entity_decode($data, ENT_QUOTES);
    }

    public static function encode($data)
    {
        $s = [];
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $s[$k] = self::encode($v);
            }
            return $s;
        }
        $o = '';
        $table = [
            '\\'=>'&#092;',
        ];
        $strlen = mb_strlen($data);
        while ($strlen) {
            $char=mb_substr($data, 0, 1);
            $o .= @$table[$char] ?: html_entity_decode($char, ENT_QUOTES);
            $data = mb_substr($data, 1, $strlen);
            $strlen = mb_strlen($data);
        }
        return $o;
    }
}
