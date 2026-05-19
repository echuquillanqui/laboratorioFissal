<?php

namespace App\Enums;

enum LaboratoryTypeDato: string
{
    case TEXTO = 'texto';
    case NUMERICO = 'numerico';
    case OPCION = 'opcion';
    case BOOLEANO = 'booleano';
    case MULTILINEA = 'multilinea';

    public static function values(): array
    {
        return array_map(fn (self $type) => $type->value, self::cases());
    }
}
