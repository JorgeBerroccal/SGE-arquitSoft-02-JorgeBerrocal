<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Dominio;

use InvalidArgumentException;
use Sge\Evaluacion\Dominio\Excepciones\NotaFueraDeEscala;

/**
 * Nota de un estudiante en una competencia de un curso y periodo (RF15).
 *
 * Solo se puede crear con una nota que pertenezca a la escala de la institución.
 */
final class Calificacion
{
    private function __construct(
        public readonly string $estudianteId,
        public readonly string $cursoId,
        public readonly string $periodoId,
        public readonly string $competencia,
        public readonly string $nota,
    ) {
    }

    public static function registrar(
        string $estudianteId,
        string $cursoId,
        string $periodoId,
        string $competencia,
        string $nota,
        Escala $escala,
    ): self {
        if (! $escala->esValida($nota)) {
            throw NotaFueraDeEscala::para($nota, $escala);
        }

        return new self($estudianteId, $cursoId, $periodoId, $competencia, $nota);
    }

    /**
     * Promedio de varias calificaciones expresado en la misma escala (RF16).
     *
     * @param list<Calificacion> $calificaciones
     */
    public static function promedio(array $calificaciones, Escala $escala): string
    {
        if ($calificaciones === []) {
            throw new InvalidArgumentException('No hay calificaciones para promediar.');
        }

        $suma = array_sum(array_map(
            fn (Calificacion $calificacion): float => $escala->aNumero($calificacion->nota),
            $calificaciones,
        ));

        return $escala->desdeNumero($suma / count($calificaciones));
    }
}
