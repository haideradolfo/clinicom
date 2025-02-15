<?php

namespace App\Enum;

enum Role: string
{
    case Admin = 'Admin';
    case Medecin = 'Medecin';
    case Patient = 'Patient';

    public function label(): string
    {
        return match($this) {
            self::Admin => 'Admin',
            self::Medecin => 'Medecin',
            self::Patient => 'Patient'
        };
    }

    public static function fromLabel(string $label): self
    {
        foreach (self::cases() as $case) {
            if ($case->label() === $label) {
                return $case;
            }
        }
        throw new \ValueError("Invalid role label: $label");
    }

    public function toSecurityRole(): string
    {
        return 'ROLE_' . strtoupper($this->value);
    }
}