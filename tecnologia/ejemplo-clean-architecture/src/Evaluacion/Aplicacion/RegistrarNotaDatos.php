<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Aplicacion;

/** Datos de entrada del caso de uso RegistrarNota. */
final class RegistrarNotaDatos
{
    public function __construct(
        public readonly string $docenteId,
        public readonly string $estudianteId,
        public readonly string $cursoId,
        public readonly string $seccionId,
        public readonly string $periodoId,
        public readonly string $competencia,
        public readonly string $nota,
    ) {
    }
}
