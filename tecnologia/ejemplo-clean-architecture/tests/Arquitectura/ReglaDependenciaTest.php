<?php

declare(strict_types=1);

// Pruebas de arquitectura: verifican la regla de dependencia de Clean Architecture
// (EQ-05 Mantenibilidad, DA09). Si alguien importa una clase de una capa externa
// desde una interna, estas pruebas fallan.

arch('el dominio no depende de la aplicación, la infraestructura ni de frameworks')
    ->expect('Sge\Evaluacion\Dominio')
    ->not->toUse([
        'Sge\Evaluacion\Aplicacion',
        'Sge\Evaluacion\Infraestructura',
        'Illuminate',
    ]);

arch('la aplicación no depende de la infraestructura ni de frameworks')
    ->expect('Sge\Evaluacion\Aplicacion')
    ->not->toUse([
        'Sge\Evaluacion\Infraestructura',
        'Illuminate',
    ]);

arch('los puertos son interfaces')
    ->expect('Sge\Evaluacion\Aplicacion\Puertos')
    ->toBeInterfaces();

arch('las excepciones del dominio son excepciones de dominio')
    ->expect('Sge\Evaluacion\Dominio\Excepciones')
    ->toExtend(DomainException::class);

arch('todas las clases declaran tipos estrictos')
    ->expect('Sge')
    ->toUseStrictTypes();
