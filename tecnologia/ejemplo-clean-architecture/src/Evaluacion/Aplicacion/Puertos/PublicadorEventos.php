<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Aplicacion\Puertos;

/** Publica eventos de dominio sin conocer a quién los escucha (patrón Observer). */
interface PublicadorEventos
{
    public function publicar(object $evento): void;
}
