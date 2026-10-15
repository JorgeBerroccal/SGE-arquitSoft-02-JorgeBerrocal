<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Dominio\Excepciones;

use DomainException;

final class DocenteNoAsignado extends DomainException
{
    public static function para(string $docenteId, string $cursoId, string $seccionId): self
    {
        return new self(sprintf(
            'El docente %s no está asignado al curso %s en la sección %s.',
            $docenteId,
            $cursoId,
            $seccionId,
        ));
    }
}
