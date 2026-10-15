# Diseño interno de módulos

> **Proyecto:** SGE · **Backend:** Laravel 11 (PHP 8.3) · **Módulo de referencia:** Evaluación y Boletas
> **Enfoque arquitectónico:** Clean Architecture dentro de cada módulo del monolito modular (aplicación gradual, ADR-002)
> **Nivel C4:** 4 – Código

## 1. Propósito

Este documento describe **cómo se organiza el código dentro de un módulo**. Tiene tres objetivos:

- que todos los módulos se construyan con la misma estructura;
- que las reglas de negocio queden aisladas de Laravel, Eloquent y los servicios externos;
- que se sepa dónde va cada archivo y de qué puede depender.

Se toma **Evaluación y Boletas** como referencia porque concentra las reglas más importantes (escala, promedio) y una tarea pesada (boleta PDF). Un ejemplo ejecutable de este módulo está en [`tecnologia/ejemplo-clean-architecture/`](../../../tecnologia/ejemplo-clean-architecture/).

## 2. Estructura de carpetas

```text
app/Modulos/
└── Evaluacion/
    ├── Dominio/
    │   ├── Calificacion.php                 Entidad: valida la nota y calcula el promedio
    │   ├── Escala.php                       Interfaz (Strategy) de la escala de calificación
    │   ├── EscalaLiteral.php                Escala AD · A · B · C
    │   ├── EscalaVigesimal.php              Escala de 0 a 20
    │   ├── NotaRegistrada.php               Evento de dominio
    │   └── Excepciones/
    │       ├── NotaFueraDeEscala.php
    │       └── DocenteNoAsignado.php
    ├── Aplicacion/
    │   ├── RegistrarNota.php                Caso de uso
    │   ├── RegistrarNotaDatos.php           Datos de entrada del caso de uso
    │   ├── ResultadoRegistro.php            Datos de salida (nota y promedio)
    │   ├── GenerarBoleta.php                Caso de uso que encola la boleta
    │   └── Puertos/
    │       ├── RepositorioCalificaciones.php
    │       ├── AsignacionDocente.php
    │       ├── PublicadorEventos.php
    │       └── GeneradorBoleta.php
    ├── Infraestructura/
    │   ├── RepositorioCalificacionesEloquent.php
    │   ├── AsignacionDocenteEloquent.php
    │   ├── PublicadorEventosLaravel.php     Usa event() de Laravel
    │   ├── GeneradorBoletaPdf.php
    │   ├── GenerarBoletaJob.php             Trabajo de la cola (queue:work)
    │   └── Modelos/                         Modelos Eloquent (CalificacionModelo, …)
    ├── Presentacion/
    │   ├── RegistroNotas.php                Componente Livewire
    │   ├── BoletaController.php             Descarga de la boleta ya generada
    │   └── vistas/                          Vistas Blade del módulo
    └── EvaluacionServiceProvider.php        Raíz de composición: une puertos e implementaciones
```

| Archivo | Capa | Responsabilidad | Importa de |
|---|---|---|---|
| `Calificacion.php` | Dominio | Crear calificaciones válidas y calcular el promedio | Solo Dominio |
| `Escala.php` | Dominio | Declarar qué hace una escala: validar, convertir a número y volver a la escala | Nada |
| `EscalaLiteral.php`, `EscalaVigesimal.php` | Dominio | Implementar cada escala | `Escala.php` |
| `NotaRegistrada.php` | Dominio | Evento con los datos del cambio, para la auditoría | Nada |
| `RegistrarNota.php` | Aplicación | Coordinar: verificar asignación, crear la calificación, guardar, calcular el promedio y publicar el evento | Dominio y sus puertos |
| `Puertos/*.php` | Aplicación | Declarar **qué** necesita el módulo del exterior | Dominio |
| `RepositorioCalificacionesEloquent.php` | Infraestructura | Implementar el puerto con Eloquent y MySQL | Aplicación, Dominio, Laravel |
| `GeneradorBoletaPdf.php`, `GenerarBoletaJob.php` | Infraestructura | Generar el PDF en la cola, fuera de la solicitud (DA03) | Aplicación, Dominio, Laravel |
| `RegistroNotas.php` | Presentación | Recibir el formulario, invocar el caso de uso y mostrar el resultado | Aplicación |
| `EvaluacionServiceProvider.php` | Composición | Registrar en el contenedor de Laravel qué implementación corresponde a cada puerto | Todas |

## 3. Reglas de dependencia del módulo

| Capa | Puede importar | No puede importar |
|---|---|---|
| Dominio | Solo Dominio | Laravel, Eloquent, Aplicación, Infraestructura, otros módulos |
| Aplicación | Dominio | Infraestructura, Presentación, Eloquent |
| Infraestructura | Aplicación, Dominio, Laravel | Presentación |
| Presentación | Aplicación (casos de uso y datos de entrada/salida) | Infraestructura, consultas directas a la base de datos |

Estas reglas se verifican con **pruebas de arquitectura de Pest** (`arch()`), como en el ejemplo de `tecnologia/`.

## 4. Flujo: registrar una nota

1. `RegistroNotas` (Livewire) recibe el formulario. El middleware ya verificó el permiso `registrar_nota`.
2. Arma `RegistrarNotaDatos` y llama a `RegistrarNota::ejecutar()`.
3. El caso de uso consulta `AsignacionDocente`: si el docente no tiene ese curso y sección, lanza `DocenteNoAsignado`.
4. Crea `Calificacion` con la `Escala` de la institución; si la nota no pertenece a la escala, lanza `NotaFueraDeEscala`.
5. Guarda con `RepositorioCalificaciones` y calcula el promedio del periodo.
6. Publica `NotaRegistrada` con `PublicadorEventos`; Auditoría lo registra (DA08).
7. Devuelve `ResultadoRegistro` con la nota y el promedio, que la vista muestra.

El diagrama de secuencia de este flujo está en el [nivel 4 del modelo C4](../../04-modelo-c4/Nivel4-DiagramadeCodigo.md).

## 5. Flujo: generar la boleta

1. `GenerarBoleta` recibe el estudiante y el periodo, y llama al puerto `GeneradorBoleta`.
2. `GeneradorBoletaPdf` encola `GenerarBoletaJob` en Redis y responde de inmediato.
3. El worker (`queue:work`) ejecuta el trabajo: lee las notas, genera el PDF y lo guarda en el almacenamiento.
4. `BoletaController` entrega el archivo ya generado cuando el padre o el alumno lo descarga.

## 6. Módulos con diseño simplificado

En Autenticación y Roles, Gestión Académica, Asistencia, Reportes y Auditoría (ADR-002):

```text
app/Modulos/GestionAcademica/
├── Aplicacion/        Casos de uso que usan los modelos Eloquent directamente
├── Modelos/           Modelos Eloquent
└── Presentacion/      Componentes Livewire, controladores y vistas
```

Se mantienen las reglas 1 a 4 de la sección [Reglas que se cumplen en todos los módulos](../../02-arquitectura-software/enfoque-arquitectonico.md#reglas-que-se-cumplen-en-todos-los-módulos) del enfoque arquitectónico.
