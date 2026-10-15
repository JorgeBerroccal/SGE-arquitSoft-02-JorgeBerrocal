<?php

declare(strict_types=1);

namespace Sge\Evaluacion\Dominio;

/** Evento de dominio: Auditoría lo escucha para registrar el cambio (RF22, DA08). */
final class NotaRegistrada
{
    public function __construct(
        public readonly string $docenteId,
        public readonly string $estudianteId,
        public readonly string $cursoId,
        public readonly string $periodoId,
        public readonly string $competencia,
        public readonly string $nota,
    ) {
    }
}
