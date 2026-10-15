# Ejemplo de Clean Architecture: módulo Evaluación

Ejemplo ejecutable del flujo **Registrar nota** del SGE, organizado con Clean Architecture. Muestra en código lo que describen el [enfoque arquitectónico](../../docs/02-arquitectura-software/enfoque-arquitectonico.md), el [diseño interno de módulos](../../docs/03-diseno-de-software/diseno-interno/diseno-interno-de-modulos.md) y el [nivel 4 del modelo C4](../../docs/04-modelo-c4/Nivel4-DiagramadeCodigo.md).

Es **PHP 8.3 sin Laravel**: el dominio y los casos de uso no dependen del framework, así que se pueden ejecutar y probar solos. En el SGE real, estas mismas clases viven en `app/Modulos/Evaluacion/` y la infraestructura en memoria se reemplaza por Eloquent.

## Estructura

```text
src/Evaluacion/
├── Dominio/                 Reglas de negocio puras
│   ├── Calificacion.php         Entidad: valida la nota y calcula el promedio
│   ├── Escala.php               Interfaz (Strategy)
│   ├── EscalaLiteral.php        AD · A · B · C
│   ├── EscalaVigesimal.php      0 a 20
│   ├── NotaRegistrada.php       Evento para la auditoría
│   └── Excepciones/
├── Aplicacion/              Casos de uso y puertos
│   ├── RegistrarNota.php        Caso de uso
│   ├── RegistrarNotaDatos.php   Datos de entrada
│   ├── ResultadoRegistro.php    Datos de salida
│   └── Puertos/                 Interfaces que implementa la infraestructura
└── Infraestructura/         Implementaciones en memoria (en el SGE: Eloquent y eventos de Laravel)
tests/
├── Unit/                    Reglas y caso de uso, sin base de datos
└── Arquitectura/            Regla de dependencia con arch() de Pest
```

## Qué demuestra

| Concepto | Dónde verlo |
|---|---|
| Regla de dependencia | `tests/Arquitectura/ReglaDependenciaTest.php`: el dominio no importa otras capas ni Laravel |
| Inversión de dependencias (DIP) | `RegistrarNota` recibe interfaces por el constructor |
| Strategy y sustitución de Liskov | `EscalaLiteral` y `EscalaVigesimal` pasan las mismas pruebas en `EscalaTest.php` |
| Repository | `RepositorioCalificaciones` y su implementación en memoria |
| Observer | El caso de uso publica `NotaRegistrada` sin conocer a Auditoría |
| Testeabilidad (DA07, EQ-07) | Las pruebas no necesitan base de datos ni red |

## Ejecutar las pruebas

Con PHP 8.3 y Composer instalados:

```bash
composer install
composer test
```

Sin PHP instalado, con Docker (desde esta carpeta):

```bash
docker run --rm -v "$PWD:/app" -w /app composer:2 composer install
docker run --rm -v "$PWD:/app" -w /app php:8.3-cli vendor/bin/pest
```

En Git Bash de Windows, anteponer `MSYS_NO_PATHCONV=1` y usar `"$(pwd -W):/app"` como volumen.
