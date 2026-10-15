<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Infraestructura;

use Sge\Evaluacion\Aplicacion\Puertos\PublicadorEventos;

/**
 * Guarda los eventos publicados para revisarlos en las pruebas.
 *
 * En el SGE real se usa event() de Laravel y Auditoría los escucha.
 */
final class PublicadorEventosEnMemoria implements PublicadorEventos
{
    /** @var list<object> */
    public array $publicados = [];

    public function publicar(object $evento): void
    {
        $this->publicados[] = $evento;
    }
}
