<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Dominio;

/**
 * Escala de calificación de la institución (patrón Strategy).
 *
 * Cada colegio configura su escala (RC14). Calificacion trabaja con esta
 * interfaz y no sabe si la escala es literal o vigesimal.
 */
interface Escala
{
    public function nombre(): string;

    /** Indica si la nota pertenece a la escala. */
    public function esValida(string $nota): bool;

    /** Convierte una nota válida en un número para promediar. */
    public function aNumero(string $nota): float;

    /** Convierte un promedio numérico de vuelta a la escala. */
    public function desdeNumero(float $valor): string;
}
