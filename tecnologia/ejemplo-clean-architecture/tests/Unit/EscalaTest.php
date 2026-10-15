<?php

declare(strict_types=1);

use Sge\Evaluacion\Dominio\EscalaLiteral;
use Sge\Evaluacion\Dominio\EscalaVigesimal;

// Las dos escalas cumplen el mismo contrato (sustitución de Liskov):
// las mismas pruebas pasan con cualquiera de ellas.
dataset('escalas', [
    'literal' => [new EscalaLiteral(), 'A', 'D'],
    'vigesimal' => [new EscalaVigesimal(), '14', '21'],
]);

it('acepta una nota válida y rechaza una que no pertenece a la escala', function ($escala, string $valida, string $invalida) {
    expect($escala->esValida($valida))->toBeTrue()
        ->and($escala->esValida($invalida))->toBeFalse();
})->with('escalas');

it('convierte una nota a número y de vuelta sin cambiarla', function ($escala, string $valida) {
    expect($escala->desdeNumero($escala->aNumero($valida)))->toBe($valida);
})->with('escalas');

it('rechaza notas vigesimales con decimales, negativas o vacías', function (string $nota) {
    expect((new EscalaVigesimal())->esValida($nota))->toBeFalse();
})->with(['14.5', '-1', '', 'quince']);

it('redondea el promedio vigesimal al entero más cercano', function () {
    expect((new EscalaVigesimal())->desdeNumero(14.5))->toBe('15')
        ->and((new EscalaVigesimal())->desdeNumero(14.4))->toBe('14');
});
