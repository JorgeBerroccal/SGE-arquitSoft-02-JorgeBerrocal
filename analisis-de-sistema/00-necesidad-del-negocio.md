# 00 - Necesidad del negocio

> **Pregunta guía:** ¿Qué problema queremos resolver?

## Problema

Los colegios de primaria y secundaria del Perú, públicos y privados, comparten los mismos procesos académicos: matrícula, asistencia y evaluación por competencias según el Currículo Nacional. Cuando esos procesos se llevan en papel o en hojas de cálculo separadas, aparecen estos problemas:

| Problema | A quién afecta | Consecuencia |
|----------|----------------|--------------|
| La información académica está dispersa en registros físicos y archivos sueltos. | Director, Administrador | No hay una vista consolidada de matrícula, asistencia y rendimiento. |
| El registro de notas y el cálculo de promedios se hacen a mano. | Docente | Toma tiempo y se cometen errores, sobre todo al cierre de bimestre. |
| Las boletas se elaboran y entregan de forma manual. | Docente, Padre de Familia | Las familias reciben la información tarde. |
| Padres y alumnos deben ir al colegio o esperar al docente para saber notas y faltas. | Padre de Familia, Alumno | Poco seguimiento del avance del estudiante. |
| No queda registro de quién modificó una nota. | Director | No se pueden aclarar reclamos ni detectar cambios indebidos. |
| Los sistemas existentes suelen estar hechos para un colegio en particular. | Institución | Adaptarlos a otra estructura de grados, secciones o escala exige cambiar el código. |

## Visión

*"Proveer un sistema de gestión escolar genérico, ligero y adaptable, que permita a instituciones educativas públicas y privadas de primaria y secundaria digitalizar sus procesos académicos esenciales (matrícula, asistencia y evaluación) con una arquitectura simple de desplegar, correctamente probada y con un asistente virtual basado en IA que facilite el acceso a la información."*

## Objetivos del negocio

| ID | Objetivo del negocio | Cómo lo atiende el SGE | Requisitos relacionados |
|----|----------------------|------------------------|-------------------------|
| ON01 | Centralizar la información académica del colegio en un solo sistema. | Módulos de Gestión Académica, Matrícula, Asistencia y Evaluación sobre una misma base de datos. | RF05–RF18, RF21 |
| ON02 | Reducir el tiempo y los errores en el registro de notas y la emisión de boletas. | Cálculo automático de promedios y boleta en PDF. | RF15–RF17 |
| ON03 | Dar a padres y alumnos acceso oportuno a notas y asistencia. | Consulta web desde el celular y asistente virtual con IA. | RF17, RF18, RF19 |
| ON04 | Proteger los datos personales de los estudiantes, que son menores de edad. | Control de acceso por roles y permisos, y auditoría de acciones críticas. | RF01–RF04, RF20, RF22 |
| ON05 | Servir a cualquier colegio sin modificar el código. | Estructura académica y escala de calificación configurables. | RF05–RF08 |
| ON06 | Operar con un costo que un colegio pequeño pueda asumir. | Despliegue con contenedores en un servidor de bajo costo. | — (RC08, RC09) |

## Alcance que responde a la necesidad

El proyecto se limita a un **Producto Mínimo Viable de 6 módulos** (plazo de dos meses, RC10): Autenticación y Roles, Gestión Académica, Matrícula, Asistencia, Evaluación y Boletas, y Asistente Virtual con IA.

Quedan fuera de esta versión la gestión financiera, las inscripciones en línea, la mensajería interna y los certificados oficiales (RC17).

## Resultado de esta etapa

Los objetivos ON01–ON06 son la base para los actores ([01](01-actores.md)), las historias de usuario ([02](02-historias-del-usuario.md)), los requisitos funcionales ([03](03-requisitos-funcionales.md)) y los atributos de calidad ([04](04-atributos-de-calidad.md)).
