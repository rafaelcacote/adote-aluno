<?php

namespace App\Enums;

enum TipoAluno: string
{
    case Colegio = 'colegio';
    case Faculdade = 'faculdade';

    public function label(): string
    {
        return match ($this) {
            self::Colegio => 'Colégio',
            self::Faculdade => 'Faculdade',
        };
    }
}
