<?php

declare(strict_types=1);

use Sge\Evaluacion\Dominio\Calificacion;
use Sge\Evaluacion\Dominio\EscalaLiteral;
use Sge\Evaluacion\Dominio\EscalaVigesimal;
use Sge\Evaluacion\Dominio\Excepciones\NotaFueraDeEscala;

function calificacion(string $nota, $escala, string $competencia = 'C1'): Calificacion
{
    return Calificacion::registrar('E1', 'MAT', '2026-B3', $competencia, $nota, $escala);
}

it('rechaza una nota fuera de la escala vigesimal', function () {
    calificacion('21', new EscalaVigesimal());
})->throws(NotaFueraDeEscala::class, 'La nota "21" no pertenece a la escala vigesimal.');

it('rechaza una nota fuera de la escala literal', function () {
    calificacion('D', new EscalaLiteral());
})->throws(NotaFueraDeEscala::class);

it('promedia notas vigesimales', function () {
    $escala = new EscalaVigesimal();

    $promedio = Calificacion::promedio([
        calificacion('14', $escala, 'C1'),
        calificacion('16', $escala, 'C2'),
        calificacion('15', $escala, 'C3'),
    ], $escala);

    expect($promedio)->toBe('15');
});

it('promedia notas literales y devuelve el resultado en la misma escala', function () {
    $escala = new EscalaLiteral();

    $promedio = Calificacion::promedio([
        calificacion('AD', $escala, 'C1'),
        calificacion('B', $escala, 'C2'),
    ], $escala);

    expect($promedio)->toBe('A');
});

it('no promedia una lista vacía', function () {
    Calificacion::promedio([], new EscalaVigesimal());
})->throws(InvalidArgumentException::class);
