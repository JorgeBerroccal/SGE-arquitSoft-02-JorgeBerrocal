<?php

declare(strict_types=1);

use Sge\Evaluacion\Aplicacion\RegistrarNota;
use Sge\Evaluacion\Aplicacion\RegistrarNotaDatos;
use Sge\Evaluacion\Dominio\EscalaLiteral;
use Sge\Evaluacion\Dominio\EscalaVigesimal;
use Sge\Evaluacion\Dominio\Excepciones\DocenteNoAsignado;
use Sge\Evaluacion\Dominio\Excepciones\NotaFueraDeEscala;
use Sge\Evaluacion\Dominio\NotaRegistrada;
use Sge\Evaluacion\Infraestructura\AsignacionDocenteEnMemoria;
use Sge\Evaluacion\Infraestructura\PublicadorEventosEnMemoria;
use Sge\Evaluacion\Infraestructura\RepositorioCalificacionesEnMemoria;

// El caso de uso se prueba sin base de datos ni Laravel: los puertos
// se cumplen con implementaciones en memoria (inversión de dependencias, DA07).
beforeEach(function () {
    $this->repositorio = new RepositorioCalificacionesEnMemoria();
    $this->eventos = new PublicadorEventosEnMemoria();
    $this->asignaciones = new AsignacionDocenteEnMemoria([['D1', 'MAT', '3B']]);
});

function casoDeUso($test, $escala = null): RegistrarNota
{
    return new RegistrarNota($test->repositorio, $test->asignaciones, $test->eventos, $escala ?? new EscalaVigesimal());
}

function datos(string $nota, string $competencia = 'C1', string $docente = 'D1'): RegistrarNotaDatos
{
    return new RegistrarNotaDatos($docente, 'E1', 'MAT', '3B', '2026-B3', $competencia, $nota);
}

it('registra la nota y devuelve el promedio actualizado del periodo', function () {
    $registrar = casoDeUso($this);

    $registrar->ejecutar(datos('14', 'C1'));
    $resultado = $registrar->ejecutar(datos('17', 'C2'));

    expect($resultado->calificacion->nota)->toBe('17')
        ->and($resultado->promedio)->toBe('16')
        ->and($this->repositorio->total())->toBe(2);
});

it('reemplaza la nota si se vuelve a registrar la misma competencia', function () {
    $registrar = casoDeUso($this);

    $registrar->ejecutar(datos('10', 'C1'));
    $resultado = $registrar->ejecutar(datos('18', 'C1'));

    expect($resultado->promedio)->toBe('18')
        ->and($this->repositorio->total())->toBe(1);
});

it('rechaza a un docente que no tiene asignado el curso y no guarda nada', function () {
    casoDeUso($this)->ejecutar(datos('15', docente: 'D2'));
})->throws(DocenteNoAsignado::class);

it('no guarda ni publica eventos cuando la nota está fuera de la escala', function () {
    try {
        casoDeUso($this)->ejecutar(datos('25'));
    } catch (NotaFueraDeEscala) {
    }

    expect($this->repositorio->total())->toBe(0)
        ->and($this->eventos->publicados)->toBeEmpty();
});

it('publica el evento NotaRegistrada para la auditoría', function () {
    casoDeUso($this)->ejecutar(datos('15'));

    expect($this->eventos->publicados)->toHaveCount(1)
        ->and($this->eventos->publicados[0])->toBeInstanceOf(NotaRegistrada::class)
        ->and($this->eventos->publicados[0]->docenteId)->toBe('D1')
        ->and($this->eventos->publicados[0]->nota)->toBe('15');
});

it('funciona igual con la escala literal de otra institución', function () {
    $registrar = casoDeUso($this, new EscalaLiteral());

    $registrar->ejecutar(datos('AD', 'C1'));
    $resultado = $registrar->ejecutar(datos('B', 'C2'));

    expect($resultado->promedio)->toBe('A');
});
