# Principios de diseño (SOLID)

## Objetivo

Documentar cómo se aplican los principios SOLID en el SGE, organizado con Clean Architecture, para que cada módulo se programe de forma uniforme y las reglas de negocio no dependan de la tecnología.

## Contexto

- **Estilo:** monolito modular (Laravel 11).
- **Enfoque interno:** Clean Architecture (presentación, aplicación, dominio, infraestructura).
- **Módulos de referencia:** Evaluación y Boletas (reglas de escala y promedio) y Asistente IA (integración con un servicio externo).

## SOLID en Evaluación y Asistente IA

| Principio | Dónde se aplica (capa) | Cómo se cumple en el SGE | Archivos |
|---|---|---|---|
| **S** · Responsabilidad única | Todas las capas | Cada clase tiene una sola razón para cambiar: `RegistroNotas` recibe el formulario, `RegistrarNota` coordina el registro, `Calificacion` aplica las reglas de la nota y el repositorio guarda. | `RegistroNotas.php`, `RegistrarNota.php`, `Calificacion.php`, `RepositorioCalificacionesEloquent.php` |
| **O** · Abierto/cerrado | Dominio | Para aceptar una nueva escala de calificación se crea una clase que implementa `Escala`; `Calificacion` y el caso de uso no se modifican. | `Escala.php`, `EscalaLiteral.php`, `EscalaVigesimal.php` |
| **L** · Sustitución de Liskov | Dominio e Infraestructura | Cualquier `Escala` cumple el mismo contrato y el cálculo del promedio funciona igual. `AsistenteClaude` y `AsistenteSimulado` devuelven la misma respuesta, así que la prueba de carga usa el simulado sin cambiar nada más (RC18). | `EscalaLiteral.php`, `EscalaVigesimal.php`, `AsistenteClaude.php`, `AsistenteSimulado.php` |
| **I** · Segregación de interfaces | Aplicación (puertos) | Cada puerto tiene solo lo que su caso de uso necesita: `RepositorioCalificaciones` guarda y consulta notas, `AsignacionDocente` solo verifica asignaciones, `PublicadorEventos` solo publica. | `Puertos/*.php` |
| **D** · Inversión de dependencias | Aplicación | `RegistrarNota` recibe interfaces por el constructor y no conoce Eloquent, Redis ni Laravel. Las implementaciones se conectan en `EvaluacionServiceProvider`. | `RegistrarNota.php`, `EvaluacionServiceProvider.php` |

## Qué pasaría sin SOLID en el SGE

| Situación real | Sin SOLID | Con SOLID |
|---|---|---|
| Un colegio usa escala vigesimal y otro literal | `if` por tipo de escala repartidos en controladores y vistas | Una clase por escala; se inyecta la de la institución |
| La API de Claude falla o se cambia de proveedor | Hay que modificar el caso de uso del asistente | Solo se reemplaza el adaptador |
| La prueba de carga no debe gastar créditos de la API | Hay que comentar código o crear una rama especial | Se configura `AsistenteSimulado` en el contenedor |
| Se quiere probar el registro de notas | Se necesita MySQL con datos | Se prueba con implementaciones en memoria en milisegundos |
| Cambia la regla del promedio | Hay que buscarla en controladores, vistas y consultas | Está solo en `Calificacion` |

## Relación con Clean Architecture y los patrones

| Principio | Lo cumple en el proyecto |
|---|---|
| Responsabilidad única | La separación en capas de Clean Architecture |
| Abierto/cerrado y Liskov | Los patrones Strategy (escalas) y Adapter (IA, PDF) |
| Segregación de interfaces | Los puertos pequeños de `Aplicacion/Puertos` |
| Inversión de dependencias | El patrón Repository y la regla de dependencia: las capas externas dependen de las internas |

## Verificación

El ejemplo de [`tecnologia/ejemplo-clean-architecture/`](../../../tecnologia/ejemplo-clean-architecture/) incluye pruebas de Pest que comprueban estos principios: las dos escalas pasan las mismas pruebas (Liskov), el caso de uso se prueba con implementaciones en memoria (inversión de dependencias) y las pruebas de arquitectura verifican que el dominio no importa otras capas.
