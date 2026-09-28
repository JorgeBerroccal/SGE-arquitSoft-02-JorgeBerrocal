# 02 - Historias de usuario

> **Formato:** Como [actor], quiero [acción], para [beneficio].

## Historias de usuario por módulo

| ID | Módulo | Historia de usuario |
|----|--------|---------------------|
| HU01 | M1 Autenticación y Roles | Como usuario del sistema, quiero iniciar sesión con mi usuario y contraseña, para acceder solo a las funciones que corresponden a mi rol. |
| HU02 | M1 Autenticación y Roles | Como administrador, quiero crear, editar y desactivar cuentas de usuario asignándoles un rol, para controlar quién accede al sistema. |
| HU03 | M1 Autenticación y Roles | Como administrador, quiero asignar permisos a cada rol de forma configurable, para modificar los accesos sin tener que cambiar el código fuente. |
| HU04 | M2 Gestión Académica Base | Como administrador, quiero configurar los datos generales de la institución (nombre, niveles, año escolar, periodos y escala de calificación), para adaptar el sistema a mi colegio. |
| HU05 | M2 Gestión Académica Base | Como administrador, quiero registrar grados, secciones y cursos por nivel educativo, para reflejar la estructura académica real de la institución. |
| HU06 | M2 Gestión Académica Base | Como administrador, quiero asignar docentes a cursos dentro de una sección, para definir quién dicta cada curso. |
| HU07 | M3 Matrícula | Como administrador, quiero registrar a un estudiante con los datos de su apoderado y matricularlo en un grado y sección del año vigente, para formalizar su inscripción. |
| HU08 | M3 Matrícula | Como administrador, quiero trasladar a un estudiante de una sección a otra, para reubicarlo sin perder su historial académico. |
| HU09 | M4 Asistencia | Como docente, quiero registrar la asistencia diaria de mis estudiantes (asistió, tardanza, falta justificada o injustificada), para llevar el control de asistencia de mis cursos. |
| HU10 | M4 Asistencia | Como director, quiero consultar reportes de asistencia por estudiante, sección o grado, para detectar problemas de inasistencia a tiempo. |
| HU11 | M5 Evaluación y Boletas | Como docente, quiero registrar las calificaciones de mis estudiantes por competencia, para evaluar su progreso según la escala de la institución. |
| HU12 | M5 Evaluación y Boletas | Como docente, quiero que el sistema calcule automáticamente el promedio por curso y periodo, para evitar errores de cálculo manual. |
| HU13 | M5 Evaluación y Boletas | Como padre de familia, quiero descargar la boleta de calificaciones de mi hijo en PDF, para conocer su rendimiento académico. |
| HU14 | M4 / M5 Consulta | Como alumno, quiero consultar mis notas y mi asistencia, para hacer seguimiento a mi propio desempeño. |
| HU15 | M6 Asistente Virtual | Como padre de familia, quiero preguntarle al asistente virtual por las notas, faltas u horario de mi hijo, para obtener la información rápidamente sin navegar por varias pantallas. |
| HU16 | M6 Asistente Virtual | Como padre de familia, quiero que el asistente virtual solo me muestre información de mis propios hijos, para que los datos de los estudiantes estén protegidos. |
| HU17 | Transversal | Como director, quiero consultar reportes consolidados de matrícula y rendimiento académico, para tomar decisiones de gestión. |
| HU18 | Transversal | Como administrador, quiero que el sistema registre las acciones críticas (inicios de sesión, cambios de notas, gestión de usuarios), para poder auditar quién hizo cada cambio. |

## Resumen por actor

| Actor | Historias |
|-------|-----------|
| Administrador | HU02, HU03, HU04, HU05, HU06, HU07, HU08, HU18 |
| Director | HU10, HU17 |
| Docente | HU09, HU11, HU12 |
| Padre de Familia | HU13, HU15, HU16 |
| Alumno | HU14 |
| Todos los usuarios | HU01 |
