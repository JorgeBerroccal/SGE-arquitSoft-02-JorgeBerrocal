<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Aplicacion;

use Sge\Evaluacion\Dominio\Calificacion;

/** Datos de salida: la nota guardada y el promedio actualizado del periodo. */
final class ResultadoRegistro
{
    public function __construct(
        public readonly Calificacion $calificacion,
        public readonly string $promedio,
    ) {
    }
}
