<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Dominio;

/**
 * Escala literal del Currículo Nacional: AD, A, B y C.
 *
 * La equivalencia numérica para promediar es un ejemplo; en el sistema real
 * la define cada institución en su configuración.
 */
final class EscalaLiteral implements Escala
{
    private const VALORES = ['AD' => 4, 'A' => 3, 'B' => 2, 'C' => 1];

    public function nombre(): string
    {
        return 'literal';
    }

    public function esValida(string $nota): bool
    {
        return array_key_exists($nota, self::VALORES);
    }

    public function aNumero(string $nota): float
    {
        return (float) self::VALORES[$nota];
    }

    public function desdeNumero(float $valor): string
    {
        return match (true) {
            $valor >= 3.5 => 'AD',
            $valor >= 2.5 => 'A',
            $valor >= 1.5 => 'B',
            default => 'C',
        };
    }
}
