<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Aplicacion\Puertos;

/** Consulta si un docente dicta un curso en una sección (RF08). */
interface AsignacionDocente
{
    public function estaAsignado(string $docenteId, string $cursoId, string $seccionId): bool;
}
