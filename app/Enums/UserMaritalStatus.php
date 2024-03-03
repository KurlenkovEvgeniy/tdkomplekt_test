<?php

namespace App\Enums;

use OutOfRangeException;

enum UserMaritalStatus: string
{
    case None = 'none';
    case Single = 'single';
    case Married = 'married';
    case Divorced = 'divorced';
    case Widow = 'widow';

    public static function getArrayValues(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getArrayNames(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function randomValue(): string
    {
        $arr = self::getArrayValues();

        return $arr[array_rand($arr)];
    }

    public static function randomName(): string
    {
        $arr = self::getArrayNames();

        return $arr[array_rand($arr)];
    }

    public static function getLocalized($value = false): array|string
    {
        $tmp = [
            self::None->value => 'Не указано',
            self::Single->value => 'Холост/не замужем',
            self::Married->value => 'Женат/замужем',
            self::Divorced->value => 'В разводе',
            self::Widow->value => 'Вдовец/вдова',
        ];

        $output = '';
        if ($value !== false) {
            if (isset($tmp[$value]))
                $output = $tmp[$value];
            else {
                throw new OutOfRangeException("Элемент со значением '$value' не найден");
            }
        }
        else {
            $output = $tmp;
        }

        return $output;
    }
}
