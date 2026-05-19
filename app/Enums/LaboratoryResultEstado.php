<?php

namespace App\Enums;

enum LaboratoryResultEstado: string
{
    case PENDIENTE = 'pendiente';
    case REGISTRADO = 'registrado';
    case VALIDADO = 'validado';
}
