<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Infraestructura;

use Sge\Evaluacion\Aplicacion\Puertos\RepositorioCalificaciones;
use Sge\Evaluacion\Dominio\Calificacion;

/**
 * Implementación en memoria del repositorio, para pruebas.
 *
 * En el SGE real se reemplaza por RepositorioCalificacionesEloquent (MySQL)
 * sin cambiar el caso de uso.
 */
final class RepositorioCalificacionesEnMemoria implements RepositorioCalificaciones
{
    /** @var array<string, Calificacion> */
    private array $calificaciones = [];

    public function guardar(Calificacion $calificacion): void
    {
        $clave = implode('|', [
            $calificacion->estudianteId,
            $calificacion->cursoId,
            $calificacion->periodoId,
            $calificacion->competencia,
        ]);

        $this->calificaciones[$clave] = $calificacion;
    }

    public function delPeriodo(string $estudianteId, string $cursoId, string $periodoId): array
    {
        return array_values(array_filter(
            $this->calificaciones,
            fn (Calificacion $c): bool => $c->estudianteId === $estudianteId
                && $c->cursoId === $cursoId
                && $c->periodoId === $periodoId,
        ));
    }

    public function total(): int
    {
        return count($this->calificaciones);
    }
}
