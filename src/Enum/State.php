<?php

namespace App\Enum;

enum State: string
{
    case NEUFAVECETIQUETTE = 'Neuf avec étiquette';
    case NEUFSANSETIQUETTE = 'Neuf sans étiquette';
    case TRESBONETAT = 'Très bon état';
    case BONETAT = 'Bon état';
    case ETATCORRECT = 'Etat correct';


    public static function getChoices(): array
    {
        return [
            'Neuf avec étiquette' => self::NEUFAVECETIQUETTE,
            'Neuf sans étiquette' => self::NEUFSANSETIQUETTE,
            'Très bon état' => self::TRESBONETAT,
            'Bon état' => self::BONETAT,
            'Etat correct' => self::ETATCORRECT,
        ];
    }
}

