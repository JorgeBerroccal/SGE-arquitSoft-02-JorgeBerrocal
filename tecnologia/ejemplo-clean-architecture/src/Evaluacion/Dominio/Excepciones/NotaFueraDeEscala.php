<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Dominio\Excepciones;

use DomainException;
use Sge\Evaluacion\Dominio\Escala;

final class NotaFueraDeEscala extends DomainException
{
    public static function para(string $nota, Escala $escala): self
    {
        return new self(sprintf('La nota "%s" no pertenece a la escala %s.', $nota, $escala->nombre()));
    }
}
