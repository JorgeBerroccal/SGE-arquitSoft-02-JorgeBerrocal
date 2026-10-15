# 03 - Requisitos funcionales

> **Historia de usuario:** expresa la necesidad desde el punto de vista del usuario.
> **Requisito funcional:** expresa lo que el sistema debe hacer para satisfacer esa necesidad.

## Requisitos funcionales

| ID | Módulo | Requisito funcional |
|----|--------|---------------------|
| RF01 | M1 | El sistema debe permitir el inicio y cierre de sesión mediante usuario y contraseña. |
| RF02 | M1 | El sistema debe restringir el acceso a cada funcionalidad según los permisos del rol del usuario (RBAC). |
| RF03 | M1 | El sistema debe permitir crear, editar y desactivar cuentas de usuario y asignarles un rol. |
| RF04 | M1 | El sistema debe permitir crear roles y asignar o quitar permisos a cada rol sin modificar el código. |
| RF05 | M2 | El sistema debe permitir configurar los datos de la institución: nombre, logo, niveles que atiende, año escolar vigente, periodos de evaluación y escala de calificación (literal o vigesimal). |
| RF06 | M2 | El sistema debe permitir registrar, editar y desactivar grados y secciones por nivel educativo. |
| RF07 | M2 | El sistema debe permitir registrar cursos y asociarlos a un grado y nivel. |
| RF08 | M2 | El sistema debe permitir asignar un docente a un curso dentro de una sección. |
| RF09 | M3 | El sistema debe permitir registrar estudiantes y matricularlos en un grado y sección del año escolar vigente. |
| RF10 | M3 | El sistema debe permitir registrar los datos del apoderado y vincularlo con uno o más estudiantes. |
| RF11 | M3 | El sistema debe permitir trasladar a un estudiante entre secciones conservando su historial académico. |
| RF12 | M4 | El sistema debe permitir al docente registrar la asistencia diaria por sección y curso asignado. |
| RF13 | M4 | El sistema debe clasificar la asistencia en: asistió, tardanza, falta justificada y falta injustificada. |
| RF14 | M4 | El sistema debe generar reportes de asistencia por estudiante, sección o grado. |
| RF15 | M5 | El sistema debe permitir al docente registrar calificaciones por competencia, validando que estén dentro de la escala configurada. |
| RF16 | M5 | El sistema debe calcular automáticamente el promedio del estudiante por curso y periodo. |
| RF17 | M5 | El sistema debe generar la boleta de calificaciones en formato PDF, descargable por el padre de familia y el alumno. |
| RF18 | M4/M5 | El sistema debe permitir al padre de familia y al alumno consultar notas y asistencia, limitado a su propia información o la de sus hijos. |
| RF19 | M6 | El sistema debe ofrecer un asistente virtual que responda en lenguaje natural consultas sobre notas, asistencia y horarios. |
| RF20 | M6 | El sistema debe enviar al servicio de IA únicamente los datos que el usuario autenticado tiene permiso de ver según su rol. |
| RF21 | Transversal | El sistema debe generar reportes consolidados de matrícula, asistencia y rendimiento para el director. |
| RF22 | Transversal | El sistema debe registrar en una bitácora de auditoría los inicios de sesión, los registros/modificaciones de notas y la gestión de usuarios. |

## Relación entre historias de usuario y requisitos funcionales

| Historia de usuario | Requisitos funcionales relacionados |
|---------------------|-------------------------------------|
| HU01 Iniciar sesión | RF01, RF02 |
| HU02 Gestionar usuarios | RF03 |
| HU03 Configurar permisos por rol | RF04 |
| HU04 Configurar institución | RF05 |
| HU05 Registrar grados, secciones y cursos | RF06, RF07 |
| HU06 Asignar docentes | RF08 |
| HU07 Matricular estudiante | RF09, RF10 |
| HU08 Trasladar estudiante | RF11 |
| HU09 Registrar asistencia | RF12, RF13 |
| HU10 Reportes de asistencia | RF14 |
| HU11 Registrar calificaciones | RF15 |
| HU12 Calcular promedios | RF16 |
| HU13 Descargar boleta PDF | RF17 |
| HU14 Consultar notas y asistencia (alumno) | RF18 |
| HU15 Consultar al asistente virtual | RF19 |
| HU16 Privacidad en el asistente virtual | RF02, RF20 |
| HU17 Reportes consolidados | RF21 |
| HU18 Auditoría | RF22 |
