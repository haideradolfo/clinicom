<?php

namespace App\Enum;

enum Role: string
{
    case Admin = 'Admin';
    case Medecin = 'Medecin';
    case Patient = 'Patient';

    public function getLabel(): string
    {
        return match ($this) {
            self::Admin => 'Administrateur',
            self::Medecin => 'Médecin',
            self::Patient => 'Patient',
        };
    }
}
