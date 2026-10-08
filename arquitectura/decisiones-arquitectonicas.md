# Decisiones arquitectónicas (ADR)

> **Pregunta guía:** ¿Cómo responderemos a los drivers?

Un **ADR** (*Architecture Decision Record*, registro de decisión arquitectónica) documenta una decisión importante del diseño junto con su justificación. Cada decisión responde a uno o más drivers de [`06-driver-arquitectonicos.md`](../analisis-de-sistema/06-driver-arquitectonicos.md).

## Resumen de decisiones

| ID | Decisión arquitectónica | Driver relacionado | Justificación | Resultado |
|----|-------------------------|--------------------|---------------|-----------|
| ADR-001 | Monolito modular | DA05, DA06, DA09 | Organizar las funcionalidades en módulos independientes dentro de una sola aplicación desplegable. | Módulos de Autenticación, Gestión Académica, Matrícula, Asistencia, Evaluación y Asistente IA. |
| ADR-002 | Clean Architecture aplicada de forma gradual | DA07, DA09 | Separar las reglas del negocio de los detalles tecnológicos, sin sobrecargar los módulos simples. | Dominio, Aplicación, Infraestructura y Presentación; aplicación completa en tres módulos y simplificada en el resto. |
| ADR-003 | RBAC propio y configurable | DA01 | Los permisos se gestionan como datos y se verifican en un único punto. | Tablas Rol, Permiso y Rol_Permiso; middleware y policies. |
| ADR-004 | Asistente IA con contexto filtrado y adaptador | DA02 | Enviar a la IA solo los datos autorizados y desacoplar los casos de uso del proveedor. | Caso de uso que filtra por RBAC; contrato de asistente y adaptador para la API de Claude. |
| ADR-005 | Estructura académica como datos | DA04 | Cada colegio define su propia estructura sin tocar el código. | Entidades genéricas Nivel, Grado, Sección, Curso, Periodo y Escala. |
| ADR-006 | Caché y sesiones en Redis | DA03 | Reducir las consultas repetitivas a MySQL bajo alta concurrencia. | Contenedor Redis para sesiones, caché de permisos y de estructura académica. |
| ADR-007 | Boletas PDF en cola de trabajos | DA03 | Sacar el trabajo pesado de la solicitud del usuario. | Cola en Redis y worker `queue:work`. |
| ADR-008 | Nginx + PHP-FPM como servidor de aplicación | DA03, DA06 | El servidor de desarrollo no soporta la carga de la prueba. | Contenedor Nginx con HTTPS y PHP-FPM con OPcache. |
| ADR-009 | Docker Compose en un VPS de bajo costo | DA06 | Costo fijo y despliegue simple para un plazo de dos meses. | Cinco contenedores en un VPS de 2 vCPU / 4 GB. |
| ADR-010 | Auditoría transversal por eventos | DA08 | Registrar las acciones críticas sin repetir código en cada módulo. | Componente de Auditoría que escucha eventos y escribe una bitácora. |

## Detalle de cada decisión

Todas las decisiones están en estado **Aceptada**.

### ADR-001 — Monolito modular

- **Contexto:** un solo desarrollador, dos meses de plazo (RC10), un VPS de bajo costo (RC09) y Laravel con Blade/Livewire como restricción (RC03, RC04).
- **Decisión:** una sola aplicación Laravel desplegable, dividida en módulos por área de negocio.
- **Alternativas descartadas:**
  - *Microservicios:* exigen varios despliegues, comunicación por red y monitoreo; no caben en el plazo ni en el servidor.
  - *Monolito sin módulos:* más rápido al inicio, pero un cambio en un área afecta a las demás (contradice AC05).
- **Consecuencias:** despliegue simple y transacciones en una sola base de datos. A cambio, todo escala junto y los límites entre módulos dependen de la disciplina del código, no de la red.

### ADR-002 — Clean Architecture aplicada de forma gradual

- **Contexto:** la lógica de negocio debe probarse aislada (DA07) y los módulos deben poder cambiar sin afectar a otros (DA09). Sin embargo, buena parte del SGE es registro y consulta de catálogos.
- **Decisión:** adoptar Clean Architecture como enfoque interno, con dos niveles de aplicación:
  - **Completa** en Evaluación y Boletas, Matrícula y Asistente IA, donde hay reglas de negocio o servicios externos.
  - **Simplificada** en Autenticación y Roles, Gestión Académica, Asistencia, Reportes y Auditoría: los casos de uso usan los modelos Eloquent directamente.
- **Alternativas descartadas:**
  - *MVC con capa de servicios:* es lo habitual en Laravel y lo más rápido, pero las reglas quedan atadas a Eloquent y no se pueden probar sin base de datos.
  - *Clean Architecture completa en todos los módulos:* agrega entidades, interfaces y traductores a módulos que solo guardan y listan datos; es un costo que el plazo no justifica.
- **Consecuencias:** las reglas de notas, promedios, matrícula y privacidad de la IA se prueban sin base de datos ni red. A cambio, hay más archivos en los tres módulos completos y el equipo debe respetar la regla de dependencia. El detalle está en [`enfoque/enfoque-arquitectonico.md`](enfoque/enfoque-arquitectonico.md).

### ADR-003 — RBAC propio y configurable

- **Contexto:** cinco roles con accesos distintos, datos de menores de edad (RC15) y la restricción de usar un modelo de roles propio (RC06).
- **Decisión:** tablas Rol, Permiso y Rol_Permiso; la verificación se hace en middleware y policies antes de llegar a cualquier caso de uso.
- **Alternativas descartadas:**
  - *Roles fijos en el código* (`if rol == docente`): cambiar un acceso obligaría a modificar y volver a desplegar el sistema.
- **Consecuencias:** se pueden crear roles y ajustar permisos desde la administración. A cambio, cada solicitud consulta permisos, por lo que se guardan en caché (ADR-006).

### ADR-004 — Asistente IA con contexto filtrado y adaptador

- **Contexto:** el asistente no debe revelar datos de otros estudiantes (AC02), usa un servicio externo de pago (RC07) y debe poder simularse en la prueba de carga (RC18).
- **Decisión:** el caso de uso del asistente pide a Asistencia y Evaluación solo los datos permitidos por RBAC, arma el contexto y llama a un contrato (`ServicioAsistente`). La API de Claude se usa mediante un adaptador que implementa ese contrato.
- **Alternativas descartadas:**
  - *Que la IA consulte la base de datos por su cuenta:* no hay forma de garantizar que respete los permisos.
  - *Llamar a la API de Claude directamente desde el caso de uso:* ata la lógica al proveedor e impide simular la respuesta.
- **Consecuencias:** un único punto decide qué datos salen del sistema, y el proveedor de IA se puede cambiar o simular. A cambio, el asistente solo responde sobre lo que el caso de uso sabe recuperar.

### ADR-005 — Estructura académica como datos

- **Contexto:** el sistema debe servir a colegios con distinta cantidad de grados, secciones, periodos y escala de calificación (AC06, RC14).
- **Decisión:** modelar la estructura académica con entidades genéricas que cada institución configura.
- **Alternativas descartadas:**
  - *Estructura fija en el código:* más simple, pero solo serviría para un colegio.
- **Consecuencias:** Matrícula, Asistencia y Evaluación dependen de Gestión Académica. A cambio, hay que validar que la configuración esté completa antes de operar.

### ADR-006 — Caché y sesiones en Redis

- **Contexto:** meta de 1000 usuarios simultáneos con p95 < 2 s (AC03).
- **Decisión:** guardar en Redis las sesiones, los permisos por rol y la estructura académica.
- **Alternativas descartadas:**
  - *Sesiones en archivos o en MySQL:* cada solicitud suma lecturas de disco o consultas a la base de datos.
- **Consecuencias:** menos carga sobre MySQL. A cambio, hay un contenedor más y se debe invalidar la caché cuando cambian permisos o estructura.

### ADR-007 — Boletas PDF en cola de trabajos

- **Contexto:** generar un PDF consume CPU y memoria (estimado: 0,5–2 s y 50–100 MB cada uno) y al cierre de bimestre se piden muchos a la vez.
- **Decisión:** Evaluación encola la generación; un worker la procesa y guarda el archivo. La descarga entrega el PDF ya generado.
- **Alternativas descartadas:**
  - *Generar el PDF en la misma solicitud:* bloquea procesos PHP y degrada al resto de usuarios.
- **Consecuencias:** las solicitudes interactivas no esperan al PDF (AC04). A cambio, la boleta puede tardar unos segundos en estar disponible y hay que mostrar ese estado al usuario.

### ADR-008 — Nginx + PHP-FPM como servidor de aplicación

- **Contexto:** la propuesta inicial se basaba en Laravel Sail, que es un entorno de desarrollo (RC08).
- **Decisión:** en producción, Nginx termina HTTPS y entrega las solicitudes a PHP-FPM con OPcache. Laravel Octane queda como opción si las mediciones lo piden.
- **Alternativas descartadas:**
  - *Servidor de desarrollo de Sail:* no está pensado para atender muchas solicitudes en paralelo.
- **Consecuencias:** se aprovechan los dos núcleos del servidor. A cambio, hay que ajustar el número de procesos PHP a la memoria disponible.

### ADR-009 — Docker Compose en un VPS de bajo costo

- **Contexto:** infraestructura de bajo costo (RC09) y entorno equivalente entre desarrollo y producción (AC09).
- **Decisión:** cinco contenedores (Nginx, aplicación, worker, MySQL y Redis) en un VPS de 2 vCPU / 4 GB, levantados con `docker compose up -d`.
- **Alternativas descartadas:**
  - *AWS u otra nube pública:* se paga por uso y por servicio, el costo es menos predecible y la configuración inicial consume tiempo del plazo.
  - *PaaS (Railway, Render):* más simple, pero con menos control sobre Redis, el worker y los recursos durante la prueba de carga.
- **Consecuencias:** costo fijo y despliegue en un solo comando. A cambio, no hay escalamiento automático ni alta disponibilidad: si el servidor cae, el sistema cae. Como todo va en contenedores, migrar a la nube no exige cambiar la aplicación.

### ADR-010 — Auditoría transversal por eventos

- **Contexto:** deben quedar registrados los inicios de sesión, los cambios de notas y la gestión de usuarios (RF22, AC10).
- **Decisión:** los casos de uso emiten eventos y un componente de Auditoría los escucha y escribe la bitácora.
- **Alternativas descartadas:**
  - *Registrar la auditoría dentro de cada caso de uso:* repite código y es fácil olvidarlo en un módulo nuevo.
- **Consecuencias:** los módulos no conocen a Auditoría. A cambio, hay que definir y mantener la lista de eventos críticos.

## Trazabilidad driver → decisión

| Driver | Decisiones |
|--------|------------|
| DA01 RBAC configurable | ADR-003 |
| DA02 IA con privacidad | ADR-004 |
| DA03 Rendimiento con 1000 usuarios | ADR-006, ADR-007, ADR-008 |
| DA04 Estructura configurable | ADR-005 |
| DA05 Laravel + Blade/Livewire | ADR-001 |
| DA06 Docker de bajo costo | ADR-001, ADR-008, ADR-009 |
| DA07 Testeabilidad | ADR-002 |
| DA08 Auditoría | ADR-010 |
| DA09 Mantenibilidad | ADR-001, ADR-002 |
