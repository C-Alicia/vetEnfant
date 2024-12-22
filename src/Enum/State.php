<?php

namespace App\Enum;

enum State: string
{
    case NEUFAVECETIQUETTE = 'Neuf avec étiquette';
    case NEUFSANSETIQUETTE = 'Neuf sans étiquette';
    case TRESBONETAT = 'Très bon état';
    case BONETAT = 'Bon état';
    case ETATCORRECT = 'Etat correct';
    case USE = 'Use';
}

