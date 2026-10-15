<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Aplicacion;

use Sge\Evaluacion\Aplicacion\Puertos\AsignacionDocente;
use Sge\Evaluacion\Aplicacion\Puertos\PublicadorEventos;
use Sge\Evaluacion\Aplicacion\Puertos\RepositorioCalificaciones;
use Sge\Evaluacion\Dominio\Calificacion;
use Sge\Evaluacion\Dominio\Escala;
use Sge\Evaluacion\Dominio\Excepciones\DocenteNoAsignado;
use Sge\Evaluacion\Dominio\NotaRegistrada;

/**
 * Caso de uso: un docente registra la nota de un estudiante (HU11, RF15, RF16).
 *
 * Recibe interfaces por el constructor (inversión de dependencias): no conoce
 * Laravel, Eloquent ni la base de datos. En Laravel, el ServiceProvider del
 * módulo conecta cada puerto con su implementación.
 */
final class RegistrarNota
{
    public function __construct(
        private readonly RepositorioCalificaciones $calificaciones,
        private readonly AsignacionDocente $asignaciones,
        private readonly PublicadorEventos $eventos,
        private readonly Escala $escala,
    ) {
    }

    public function ejecutar(RegistrarNotaDatos $datos): ResultadoRegistro
    {
        // 1. Solo el docente asignado puede registrar notas en ese curso y sección (AC01).
        if (! $this->asignaciones->estaAsignado($datos->docenteId, $datos->cursoId, $datos->seccionId)) {
            throw DocenteNoAsignado::para($datos->docenteId, $datos->cursoId, $datos->seccionId);
        }

        // 2. El dominio valida la nota con la escala de la institución.
        $calificacion = Calificacion::registrar(
            $datos->estudianteId,
            $datos->cursoId,
            $datos->periodoId,
            $datos->competencia,
            $datos->nota,
            $this->escala,
        );

        // 3. Se guarda y se recalcula el promedio del periodo.
        $this->calificaciones->guardar($calificacion);
        $promedio = Calificacion::promedio(
            $this->calificaciones->delPeriodo($datos->estudianteId, $datos->cursoId, $datos->periodoId),
            $this->escala,
        );

        // 4. Se avisa del cambio para la auditoría (DA08).
        $this->eventos->publicar(new NotaRegistrada(
            $datos->docenteId,
            $datos->estudianteId,
            $datos->cursoId,
            $datos->periodoId,
            $datos->competencia,
            $datos->nota,
        ));

        return new ResultadoRegistro($calificacion, $promedio);
    }
}
