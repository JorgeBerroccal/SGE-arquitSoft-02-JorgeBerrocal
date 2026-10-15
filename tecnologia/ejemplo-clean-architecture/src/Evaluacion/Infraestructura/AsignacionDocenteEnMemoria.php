<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Infraestructura;

use Sge\Evaluacion\Aplicacion\Puertos\AsignacionDocente;

/** Asignaciones docente-curso-sección en memoria, para pruebas. */
final class AsignacionDocenteEnMemoria implements AsignacionDocente
{
    /** @param list<array{0: string, 1: string, 2: string}> $asignaciones [docente, curso, sección] */
    public function __construct(private readonly array $asignaciones)
    {
    }

    public function estaAsignado(string $docenteId, string $cursoId, string $seccionId): bool
    {
        return in_array([$docenteId, $cursoId, $seccionId], $this->asignaciones, true);
    }
}
