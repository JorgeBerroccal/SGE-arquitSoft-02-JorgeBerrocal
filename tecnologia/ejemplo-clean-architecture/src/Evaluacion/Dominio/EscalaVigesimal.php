<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Dominio;

/** Escala numérica de 0 a 20, con notas enteras. */
final class EscalaVigesimal implements Escala
{
    public function nombre(): string
    {
        return 'vigesimal';
    }

    public function esValida(string $nota): bool
    {
        return ctype_digit($nota) && (int) $nota >= 0 && (int) $nota <= 20;
    }

    public function aNumero(string $nota): float
    {
        return (float) $nota;
    }

    public function desdeNumero(float $valor): string
    {
        // El promedio se redondea al entero más cercano (14,5 → 15).
        return (string) (int) round($valor);
    }
}
