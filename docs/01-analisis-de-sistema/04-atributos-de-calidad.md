# 04 - Atributos de calidad

> Los atributos de calidad indican **cómo** debe funcionar el sistema, más allá de **qué** debe hacer.

## Escenario de análisis

Al **cierre de un bimestre**, todos los docentes registran notas de forma simultánea y, apenas se publican, muchos padres de familia y alumnos entran a consultar notas y asistencia, descargar las boletas y preguntar al asistente virtual. Este pico se validará con una **prueba de carga de 1000 usuarios simultáneos** aplicada por la docente del curso: 1000 usuarios virtuales en k6 que ingresan de forma gradual (ramp-up) y hacen pausas de 3 a 5 segundos entre acciones, lo que se estima en unas 200–300 solicitudes por segundo. Todo esto ocurre en un **servidor de bajo costo (VPS de 2 vCPU / 4 GB de RAM para la prueba)** y con datos personales de **menores de edad**.

> Las cifras de carga y de tiempos de respuesta de este documento son **metas y estimaciones**; los valores reales se obtendrán midiendo con k6 en escalones (100 → 300 → 600 → 1000 usuarios).

¿Qué atributos de calidad resultan importantes? Seguridad, rendimiento, disponibilidad, mantenibilidad, adaptabilidad, testeabilidad, usabilidad y portabilidad.

## Atributos de calidad

| ID | Atributo de calidad | Escenario de calidad |
|----|---------------------|----------------------|
| AC01 | **Seguridad** | Cada usuario solo puede ver y modificar la información que le permite su rol: un padre solo ve a sus hijos y un docente solo registra notas en los cursos y secciones que tiene asignados. Las contraseñas se almacenan cifradas (hash). |
| AC02 | **Seguridad (privacidad en IA)** | El asistente virtual nunca debe revelar datos de otros estudiantes: al servicio de IA solo se envía el contexto autorizado para el usuario autenticado. |
| AC03 | **Rendimiento** | Durante el cierre de bimestre, el inicio de sesión, el registro de notas y la descarga de boletas deben responder en tiempos aceptables con **1000 usuarios virtuales simultáneos**, que ingresan con subida gradual (ramp-up) y hacen pausas entre acciones. Meta: percentil 95 del tiempo de respuesta menor a 2 segundos y menos de 1 % de solicitudes con error, validado con pruebas de carga en k6. |
| AC04 | **Disponibilidad** | El sistema debe permanecer accesible en los periodos críticos (registro de notas y publicación de boletas) y no debe caerse bajo la carga de la prueba de 1000 usuarios: las tareas pesadas, como la generación de boletas PDF, no deben bloquear las solicitudes interactivas. Ante un reinicio del servidor, los contenedores deben levantarse automáticamente. |
| AC05 | **Mantenibilidad** | El sistema debe estar organizado en módulos independientes (monolito modular), de manera que se pueda modificar o agregar un módulo (p. ej. gestión financiera) sin afectar a los demás. |
| AC06 | **Adaptabilidad / Configurabilidad** | Una nueva institución debe poder configurar sus niveles, grados, secciones, cursos, periodos y escala de calificación sin cambios en el código fuente. |
| AC07 | **Testeabilidad** | La lógica de negocio (cálculo de promedios, validación de matrícula, reglas RBAC) debe poder probarse de forma aislada con pruebas unitarias y de integración automatizadas antes de cada despliegue. |
| AC08 | **Usabilidad** | La interfaz debe ser responsive y fácil de usar desde celulares y tablets, ya que muchos padres y docentes accederán desde el móvil. |
| AC09 | **Portabilidad / Desplegabilidad** | El sistema debe poder desplegarse en cualquier servidor con Docker, con un entorno equivalente entre desarrollo y producción: los mismos servicios se definen con Docker Compose, y en producción la aplicación se sirve con Nginx + PHP-FPM (o Laravel Octane) junto con Redis, en lugar del servidor de desarrollo. |
| AC10 | **Trazabilidad (auditoría)** | Toda acción crítica debe quedar registrada con usuario, fecha y hora, para poder identificar quién modificó una nota o una cuenta. |

## Ejemplo funcional vs. calidad

- **Funcional:** el sistema debe permitir registrar notas.
- **Calidad:** el registro de notas debe ser seguro (solo el docente asignado), quedar auditado y responder rápidamente incluso al cierre del bimestre.

## Escenarios medibles

Cada atributo se convierte en un escenario con fuente, estímulo, artefacto, entorno, respuesta y medida en [`08-escenarios-atributos-calidad.md`](08-escenarios-atributos-calidad.md) (EQ-01 a EQ-10).
