<?php

namespace App\Enums;

enum LaboratoryOrderEstado: string
{
    case PENDIENTE = 'pendiente';
    case PROCESO = 'proceso';
    case VALIDADO = 'validado';
    case ENTREGADO = 'entregado';
}
