<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Aplicacion\Puertos;

use Sge\Evaluacion\Dominio\Calificacion;

/** Lo que el caso de uso necesita para persistir calificaciones (patrón Repository). */
interface RepositorioCalificaciones
{
    /** Guarda la calificación o reemplaza la de la misma competencia. */
    public function guardar(Calificacion $calificacion): void;

    /** @return list<Calificacion> */
    public function delPeriodo(string $estudianteId, string $cursoId, string $periodoId): array;
}
