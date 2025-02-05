<?php

namespace App\Enum;

enum Gender: string
{
    case GIRL = 'Fille';
    case BOY = 'Garçon';
    case UNISEXE = 'Mixte';
    
    public static function getChoices(): array
    {
        return [
            'Fille' => self::GIRL,
            'Garçon' => self::BOY,
            'Mixte' => self::UNISEXE,
        ];
    }
}