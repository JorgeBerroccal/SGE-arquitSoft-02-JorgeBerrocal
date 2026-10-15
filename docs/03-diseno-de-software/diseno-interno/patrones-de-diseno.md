# Patrones de diseño

## Objetivo

Documentar los patrones de diseño aplicados en el SGE, indicando dónde se usan, qué problema resuelven y con qué se implementan, para que todos los módulos se programen de forma uniforme.

## Contexto

- **Estilo:** monolito modular (Laravel 11).
- **Enfoque interno:** Clean Architecture aplicada de forma gradual (ADR-002).
- **Base:** [componentes arquitectónicos](../../02-arquitectura-software/componentes-arquitectonicos.md) y [nivel 3 del modelo C4](../../04-modelo-c4/Nivel3-Diagrama-de-Componentes.md).

## Patrones aplicados

| Patrón | Dónde se usa | Problema que resuelve | Se implementa con | Archivos |
|---|---|---|---|---|
| **Strategy** | Evaluación → escala de calificación | Cada colegio usa una escala distinta (literal o vigesimal) y la validación y el promedio cambian según ella (DA04, RC14) | Interfaz `Escala` con una clase por escala; la institución elige cuál se inyecta | `Escala.php`, `EscalaLiteral.php`, `EscalaVigesimal.php` |
| **Repository** | Evaluación y Matrícula → base de datos | El negocio no debe conocer SQL ni Eloquent; las reglas deben probarse sin base de datos (DA07) | Interfaz en `Aplicacion/Puertos` e implementación con Eloquent | `RepositorioCalificaciones.php`, `RepositorioCalificacionesEloquent.php` |
| **Adapter** | Asistente IA → API de Claude | La API de Claude tiene su propio formato; debe poder reemplazarse por una respuesta simulada en la prueba de carga (DA02, RC18) | Interfaz `ServicioAsistente`; adaptadores `AsistenteClaude` (HTTP de Laravel) y `AsistenteSimulado` | `ServicioAsistente.php`, `AsistenteClaude.php`, `AsistenteSimulado.php` |
| **Adapter** | Evaluación → generación de PDF | La librería de PDF y la cola son detalles técnicos que no deben entrar al caso de uso | Interfaz `GeneradorBoleta`; adaptador que encola un trabajo de Laravel | `GeneradorBoleta.php`, `GeneradorBoletaPdf.php`, `GenerarBoletaJob.php` |
| **Observer** | Autenticación y Evaluación → Auditoría | Registrar las acciones críticas sin que cada módulo conozca a Auditoría (DA08, ADR-010) | Eventos y *listeners* de Laravel | `NotaRegistrada.php`, `RegistrarEnBitacora.php` |
| **Decorator** | Autenticación y Roles → caché | Consultar permisos en cada solicitud sin ir siempre a MySQL (DA03) | Un repositorio que envuelve al de Eloquent y guarda el resultado en Redis | `RepositorioPermisosEnCache.php` |
| **Facade** | Asistente IA → Asistencia y Evaluación | El asistente necesita datos de varios módulos, pero no debe conocer sus clases internas ni sus tablas (DA02) | Una clase de consultas por módulo como única puerta de entrada | `ConsultasEvaluacion.php`, `ConsultasAsistencia.php` |

## Cómo funciona cada patrón

| Patrón | En pocas palabras |
|---|---|
| Strategy | `Calificacion` pregunta a la escala si la nota es válida; no sabe si es literal o vigesimal. |
| Repository | El caso de uso dice `guardar($calificacion)`; el repositorio escribe en MySQL. |
| Adapter | El sistema dice `responder($pregunta, $contexto)`; el adaptador lo traduce a la llamada HTTP de Claude. |
| Observer | Evaluación anuncia «se registró una nota»; Auditoría escucha y lo anota. |
| Decorator | Busca los permisos en Redis; si no están, los pide a MySQL y los guarda para la próxima vez. |
| Facade | El asistente pide `notasDelEstudiante($id)`; no ve repositorios ni tablas. |

## Beneficios

- Una nueva escala de calificación se agrega con una clase nueva, sin tocar `Calificacion`.
- Cambiar de proveedor de IA o de librería de PDF solo afecta a su adaptador.
- La caché con Redis se activa o se retira sin tocar las reglas de negocio.
- Las reglas se prueban con implementaciones en memoria (ver [`tecnologia/`](../../../tecnologia/ejemplo-clean-architecture/)).

## Patrones previstos para siguientes iteraciones

| Patrón | Dónde se aplicaría | Cuándo |
|---|---|---|
| Circuit Breaker | Asistente IA → API de Claude | Si la API falla o tarda: después de varios errores, el asistente responde un mensaje de «no disponible» sin seguir llamando a la API |
| Factory | Evaluación → boleta | Si primaria y secundaria necesitan formatos de boleta distintos |
| Chain of Responsibility | Matrícula → validaciones | Si las validaciones de matrícula (vacantes, documentos, deudas) crecen y deben poder activarse por institución |
