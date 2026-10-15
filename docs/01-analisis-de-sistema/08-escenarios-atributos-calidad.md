# 08 - Escenarios de atributos de calidad

> Un escenario de atributo de calidad describe de forma **concreta y medible** cómo debe responder el sistema ante un estímulo. Convierte cada atributo de [`04-atributos-de-calidad.md`](04-atributos-de-calidad.md) en algo que se puede comprobar.

Cada escenario tiene seis partes:

| Parte | Qué responde |
|-------|--------------|
| **Fuente del estímulo** | ¿Quién o qué lo origina? |
| **Estímulo** | ¿Qué ocurre? |
| **Artefacto** | ¿Sobre qué parte del sistema? |
| **Entorno** | ¿En qué condiciones? |
| **Respuesta** | ¿Qué debe hacer el sistema? |
| **Medida de la respuesta** | ¿Con qué valor se comprueba? |

Se agregan **Verificación** (cómo se prueba) y **Estado**. Las medidas marcadas como *propuesta por validar* son metas iniciales que se confirmarán con la docente o al medir el sistema.

## EQ-01. Seguridad: acceso a información protegida

| Elemento | Descripción |
|---|---|
| **Atributo de calidad** | Seguridad (AC01) |
| **Fuente** | Usuario sin sesión, o usuario autenticado sin el permiso necesario. |
| **Estímulo** | Un padre intenta ver las notas de un estudiante que no es su hijo, o un docente intenta registrar notas en un curso que no tiene asignado. |
| **Artefacto** | Middleware de autenticación y RBAC, y los casos de uso de Evaluación y Asistencia. |
| **Entorno** | Operación normal, con roles y permisos configurados. |
| **Respuesta** | El sistema rechaza la operación, no ejecuta ningún cambio y no muestra datos ajenos. |
| **Medida** | Se rechaza el **100 %** de los intentos no autorizados incluidos en las pruebas; ninguna respuesta contiene datos de otro estudiante; ninguna contraseña se guarda sin hash. |
| **Verificación** | Pruebas de integración con Pest que recorren la matriz rol × funcionalidad, con casos permitidos y no permitidos. |
| **Estado** | Medida definida. Falta completar la matriz de permisos por rol al implementar el módulo de Autenticación. |

## EQ-02. Seguridad: privacidad en el asistente virtual

| Elemento | Descripción |
|---|---|
| **Atributo de calidad** | Seguridad, privacidad en IA (AC02) |
| **Fuente** | Padre de familia o alumno que usa el asistente virtual. |
| **Estímulo** | Pregunta por datos de otro estudiante, por ejemplo: «¿qué notas tiene Juan Pérez de 3.° B?». |
| **Artefacto** | Caso de uso `ConsultarAsistente` y el contexto que se envía a la API de Claude. |
| **Entorno** | Operación normal, con la API de Claude disponible. |
| **Respuesta** | El contexto enviado a la IA contiene solo los datos del usuario o de sus hijos; el asistente responde que no tiene esa información. |
| **Medida** | **0** datos de otros estudiantes en el contexto enviado, en el 100 % de los casos de prueba. |
| **Verificación** | Pruebas unitarias del caso de uso con `AsistenteSimulado`, que guarda el contexto recibido para revisarlo sin llamar a la API real. |
| **Estado** | Medida definida. |

## EQ-03. Rendimiento: cierre de bimestre

| Elemento | Descripción |
|---|---|
| **Atributo de calidad** | Rendimiento (AC03) |
| **Fuente** | Docentes, padres y alumnos al cierre del bimestre, simulados por la prueba de carga de la docente. |
| **Estímulo** | 1000 usuarios simultáneos inician sesión y consultan notas, asistencia y boletas. |
| **Artefacto** | Aplicación Laravel (Nginx + PHP-FPM), MySQL y Redis. |
| **Entorno** | VPS de 2 vCPU / 4 GB; usuarios que ingresan de forma gradual (ramp-up) y hacen pausas de 2 a 5 s entre acciones; asistente virtual excluido o simulado (RC18). |
| **Respuesta** | El sistema atiende las solicitudes sin rechazarlas ni caerse. |
| **Medida** | *Propuesta por validar:* percentil 95 del tiempo de respuesta **menor a 2 s** y **menos de 1 %** de solicitudes con error. |
| **Verificación** | Script [`k6-1000-usuarios.js`](../../tecnologia/pruebas-carga/k6-1000-usuarios.js) ejecutado en escalones 100 → 300 → 600 → 1000 usuarios, registrando tiempos, errores y uso de CPU y memoria. |
| **Estado** | Pendiente de confirmar con la docente el ramp-up, las pausas y las pantallas que se probarán. |

## EQ-04. Disponibilidad: periodos críticos y reinicios

| Elemento | Descripción |
|---|---|
| **Atributo de calidad** | Disponibilidad (AC04) |
| **Fuente** | Muchos usuarios generando boletas a la vez, o un reinicio del servidor. |
| **Estímulo** | Se piden cientos de boletas PDF durante el cierre de bimestre, o el VPS se reinicia. |
| **Artefacto** | Worker de cola, contenedores de Docker Compose. |
| **Entorno** | Periodo de publicación de boletas. |
| **Respuesta** | La generación de boletas no bloquea las demás pantallas; tras un reinicio, los contenedores se levantan solos. |
| **Medida** | *Propuesta por validar:* mientras se procesan las boletas, las demás pantallas siguen cumpliendo EQ-03; tras un reinicio, el sistema vuelve a responder en **2 minutos o menos** sin intervención manual. |
| **Verificación** | Encolar boletas durante la prueba de carga y comparar los tiempos; reiniciar el VPS y medir el tiempo hasta la primera respuesta correcta. |
| **Estado** | Pendiente de medir en el VPS. |

## EQ-05. Mantenibilidad: cambios sin afectar otros módulos

| Elemento | Descripción |
|---|---|
| **Atributo de calidad** | Mantenibilidad (AC05) |
| **Fuente** | Desarrollador. |
| **Estímulo** | Se cambia una regla de evaluación, se agrega una nueva escala de calificación o se reemplaza el proveedor de IA. |
| **Artefacto** | Módulo responsable del cambio (Evaluación o Asistente IA). |
| **Entorno** | Desarrollo, con el sistema organizado en módulos y con pruebas automatizadas. |
| **Respuesta** | El cambio se concentra en el módulo responsable, sin modificar módulos no relacionados. |
| **Medida** | Las pruebas del módulo modificado y las de regresión pasan; las pruebas de arquitectura confirman que ninguna clase de `Dominio` importa Laravel, Eloquent ni otro módulo. |
| **Verificación** | Revisión del cambio, ejecución de Pest y de sus pruebas de arquitectura (`arch()`). |
| **Estado** | Reglas de dependencia definidas en [`enfoque-arquitectonico.md`](../02-arquitectura-software/enfoque-arquitectonico.md). |

## EQ-06. Adaptabilidad: configurar una nueva institución

| Elemento | Descripción |
|---|---|
| **Atributo de calidad** | Adaptabilidad / configurabilidad (AC06) |
| **Fuente** | Administrador de un colegio nuevo. |
| **Estímulo** | Configura su institución: solo primaria, tres secciones por grado, periodos por trimestre y escala vigesimal. |
| **Artefacto** | Módulo de Gestión Académica. |
| **Entorno** | Instalación nueva del SGE. |
| **Respuesta** | El sistema queda listo para matricular y evaluar con esa estructura. |
| **Medida** | **0** cambios en el código fuente. *Propuesta por validar:* configuración completa en 1 hora o menos. |
| **Verificación** | Configurar dos instituciones distintas (escala literal y vigesimal) y ejecutar el mismo flujo de matrícula y evaluación. |
| **Estado** | Medida de código definida; tiempo por validar. |

## EQ-07. Testeabilidad: pruebas antes de cada despliegue

| Elemento | Descripción |
|---|---|
| **Atributo de calidad** | Testeabilidad (AC07) |
| **Fuente** | Desarrollador. |
| **Estímulo** | Ejecuta la suite de pruebas antes de desplegar. |
| **Artefacto** | Casos de uso y reglas de dominio de Evaluación, Matrícula y Asistente IA. |
| **Entorno** | Máquina de desarrollo o integración continua, sin base de datos ni acceso a internet para las pruebas unitarias. |
| **Respuesta** | Las reglas de negocio se prueban de forma aislada, con repositorios en memoria y servicios simulados. |
| **Medida** | El 100 % de las reglas críticas (escala, promedio, matrícula, traslado, filtro de la IA) tiene al menos una prueba. *Propuesta por validar:* la suite unitaria termina en menos de 1 minuto. |
| **Verificación** | Ejecutar `pest` y revisar la lista de reglas críticas contra las pruebas existentes. El ejemplo de [`tecnologia/`](../../tecnologia/) muestra el enfoque. |
| **Estado** | Medida definida. |

## EQ-08. Usabilidad: consulta desde el celular

| Elemento | Descripción |
|---|---|
| **Atributo de calidad** | Usabilidad (AC08) |
| **Fuente** | Padre de familia. |
| **Estímulo** | Quiere ver las notas y la boleta de su hijo desde el celular. |
| **Artefacto** | Vistas Blade + Livewire. |
| **Entorno** | Navegador móvil con pantalla de 360 px de ancho. |
| **Respuesta** | Encuentra y descarga la boleta sin ayuda. |
| **Medida** | *Propuesta por validar:* llegar a la boleta en **3 interacciones o menos** después de iniciar sesión, sin desplazamiento horizontal a 360 px. |
| **Verificación** | Prueba con usuarios (padres o compañeros) y revisión en el modo dispositivo del navegador. |
| **Estado** | Pendiente de validar con usuarios. |

## EQ-09. Portabilidad: despliegue en un servidor nuevo

| Elemento | Descripción |
|---|---|
| **Atributo de calidad** | Portabilidad / desplegabilidad (AC09) |
| **Fuente** | Responsable del despliegue. |
| **Estímulo** | Necesita instalar el SGE en un VPS nuevo. |
| **Artefacto** | Archivos de Docker Compose y guía de despliegue. |
| **Entorno** | Servidor Linux con Docker instalado. |
| **Respuesta** | El sistema queda funcionando con los mismos servicios que en desarrollo. |
| **Medida** | *Propuesta por validar:* desplegar con `docker compose up -d` y las migraciones en **30 minutos o menos**, siguiendo solo la guía. |
| **Verificación** | Desplegar en un VPS limpio y cronometrar el proceso. |
| **Estado** | Pendiente: los archivos de Docker se crearán en la implementación. |

## EQ-10. Trazabilidad: auditoría de acciones críticas

| Elemento | Descripción |
|---|---|
| **Atributo de calidad** | Trazabilidad / auditoría (AC10) |
| **Fuente** | Docente o administrador. |
| **Estímulo** | Un docente modifica una nota, o un administrador desactiva una cuenta. |
| **Artefacto** | Componente de Auditoría y los eventos de los módulos. |
| **Entorno** | Operación normal. |
| **Respuesta** | Queda un registro de quién hizo el cambio, cuándo y qué cambió. |
| **Medida** | El **100 %** de las acciones críticas definidas (inicio de sesión, registro y cambio de notas, gestión de usuarios) genera un registro con usuario, fecha y hora, acción, valor anterior y valor nuevo. |
| **Verificación** | Pruebas de integración que ejecutan cada acción crítica y comprueban el registro en la bitácora. |
| **Estado** | Medida definida. |

## Resumen de escenarios

| Código | Atributo | Qué se busca comprobar | Atributo de origen | Driver |
|--------|----------|------------------------|:------------------:|:------:|
| EQ-01 | Seguridad | Que nadie acceda a información o acciones que su rol no permite. | AC01 | DA01 |
| EQ-02 | Privacidad en IA | Que el asistente nunca reciba datos de otros estudiantes. | AC02 | DA02 |
| EQ-03 | Rendimiento | Que el sistema soporte 1000 usuarios con p95 < 2 s. | AC03 | DA03 |
| EQ-04 | Disponibilidad | Que las boletas no bloqueen el sistema y que se recupere solo. | AC04 | DA03 |
| EQ-05 | Mantenibilidad | Que un cambio quede dentro de su módulo. | AC05 | DA09 |
| EQ-06 | Adaptabilidad | Que un colegio nuevo se configure sin tocar el código. | AC06 | DA04 |
| EQ-07 | Testeabilidad | Que las reglas de negocio se prueben sin base de datos. | AC07 | DA07 |
| EQ-08 | Usabilidad | Que un padre use el sistema desde el celular. | AC08 | — |
| EQ-09 | Portabilidad | Que el sistema se despliegue en cualquier servidor con Docker. | AC09 | DA06 |
| EQ-10 | Trazabilidad | Que toda acción crítica quede registrada. | AC10 | DA08 |
