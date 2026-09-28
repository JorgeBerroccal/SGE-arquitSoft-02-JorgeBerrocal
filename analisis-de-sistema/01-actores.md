# 01 - Actores del sistema

> **Pregunta guía:** ¿Quién o qué interactúa con el sistema desde fuera para realizar una acción o intercambiar información?

## Contexto

```
                    SISTEMA DE GESTIÓN ESCOLAR (SGE)
                                  │
     ┌─────────────┬──────────────┼──────────────┬──────────────┐
     ↓             ↓              ↓              ↓              ↓
Administrador   Director       Docente     Padre de Familia   Alumno
```

```
                    SISTEMA DE GESTIÓN ESCOLAR (SGE)
                                  │
                                  ↓
                    API de IA (Claude - Anthropic)
```

## Actores humanos

| Actor | ¿Qué necesita realizar? |
|-------|-------------------------|
| **Administrador del Sistema** | Configurar la institución (nombre, logo, niveles, año escolar, periodos y escala de calificación), registrar grados, secciones y cursos, asignar docentes, gestionar usuarios, roles y permisos, y matricular estudiantes. |
| **Director** | Supervisar la gestión académica: consultar reportes consolidados de matrícula, asistencia y rendimiento; apoyar el proceso de matrícula. |
| **Docente** | Registrar la asistencia diaria y las calificaciones por competencia de los cursos y secciones que tiene asignados, y consultar reportes de sus estudiantes. |
| **Padre de Familia (Apoderado)** | Consultar la asistencia y las notas de su(s) hijo(s), descargar la boleta de calificaciones en PDF y hacer consultas al asistente virtual. |
| **Alumno** | Consultar sus propias notas y asistencia, descargar su boleta y hacer consultas al asistente virtual. |

## Sistemas externos

| Actor | ¿Qué necesita realizar? |
|-------|-------------------------|
| **API de IA (Claude - Anthropic)** | Recibir la pregunta del usuario junto con el contexto de datos autorizado y devolver una respuesta en lenguaje natural para el asistente virtual (Módulo 6). |

> **Nota:** a diferencia de un marketplace, el MVP del SGE **no** integra pasarela de pago, servicio de envío ni facturación, porque la gestión financiera (pensiones, caja) está **fuera del alcance** de esta versión. Estas integraciones podrían aparecer en una ampliación futura.

## Resumen

| Tipo | Actores |
|------|---------|
| Humanos | Administrador, Director, Docente, Padre de Familia, Alumno |
| Sistemas externos | API de IA (Claude) |
