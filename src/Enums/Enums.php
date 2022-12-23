<?php

namespace Laraditz\Gkash\Enums;

class Enums
{
    public static function getValue($var)
    {
        $oClass = new \ReflectionClass(get_called_class());
        $constant = $oClass->getConstants();

        return data_get($constant, $var);
    }

    public static function getDescription($var)
    {
        $oClass = new \ReflectionClass(get_called_class());
        $constant = $oClass->getConstants();

        return array_search($var, $constant);
    }
}
