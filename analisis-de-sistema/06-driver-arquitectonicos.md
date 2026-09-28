# 06 - Drivers arquitectónicos

> **Pregunta guía:** ¿Qué requisito o condición puede cambiar la forma en que diseñamos la arquitectura?

## Evaluación de candidatos

| Fuente | Elemento | ¿Requiere una decisión de arquitectura? | ¿Es driver? |
|--------|----------|------------------------------------------|:-----------:|
| Requisito funcional | RF02/RF04 Control de acceso RBAC configurable | Sí: define un componente transversal que protege todos los módulos y un modelo de datos de roles y permisos. | **Sí** |
| Requisito funcional | RF19/RF20 Asistente virtual con IA | Sí: obliga a integrar un servicio externo y a filtrar los datos antes de enviarlos. | **Sí** |
| Requisito funcional | RF05–RF07 Estructura académica configurable | Sí: la estructura académica se modela como datos, no como código. | **Sí** |
| Requisito funcional | RF17 Boleta en PDF | Parcialmente: se resuelve con una librería dentro del módulo de Evaluación. | No |
| Requisito funcional | RF13 Tipos de asistencia | No: es una regla de negocio interna del módulo de Asistencia. | No |
| Atributo de calidad | AC01/AC02 Seguridad y privacidad | Sí: afecta autenticación, autorización y la integración con IA. | **Sí** |
| Atributo de calidad | AC03 Rendimiento en cierre de bimestre | Sí: afecta el acceso a datos (índices, consultas) y el dimensionamiento del servidor. | **Sí** |
| Atributo de calidad | AC05 Mantenibilidad | Sí: define la organización en capas y módulos. | **Sí** |
| Atributo de calidad | AC07 Testeabilidad | Sí: exige separar la lógica de negocio en servicios que puedan probarse de forma aislada. | **Sí** |
| Atributo de calidad | AC08 Usabilidad responsive | No: se resuelve en el diseño de la interfaz. | No |
| Restricción | RC03/RC04 Laravel + Blade/Livewire | Sí: determina un monolito modular sin frontend separado. | **Sí** |
| Restricción | RC08/RC09 Docker en VPS de 2 GB | Sí: limita la arquitectura a pocos contenedores y descarta microservicios. | **Sí** |
| Restricción | RC15 Ley de Protección de Datos Personales | Sí: refuerza la privacidad, la auditoría y el filtrado de datos hacia la IA. | **Sí** |
| Restricción | RC02 Uso de Git y GitHub | No: afecta el proceso, no la estructura del sistema. | No |

## Drivers arquitectónicos

| ID | Driver arquitectónico | Origen | ¿Por qué influye en la arquitectura? |
|----|-----------------------|--------|--------------------------------------|
| **DA01** | El sistema debe controlar el acceso a cada funcionalidad mediante roles y permisos configurables (RBAC), sin permisos codificados de forma fija. | RF02, RF04, AC01 | Obliga a tener un componente transversal de autorización (middleware/policies) que usan todos los módulos, y un modelo de datos Rol–Permiso–Rol_Permiso. |
| **DA02** | El asistente virtual debe responder solo con información autorizada para el usuario y enviar a la IA únicamente ese contexto. | RF19, RF20, AC02, RC07, RC15 | Define un servicio de orquestación que primero consulta los datos permitidos (vía RBAC) y luego llama a la API externa; condiciona la integración y el manejo de errores/tiempos de espera del servicio externo. |
| **DA03** | El sistema debe mantener tiempos de respuesta adecuados en el cierre de bimestre (registro de notas y descarga de boletas) sobre un servidor de 2 GB de RAM. | AC03, AC04, RC09 | Influye en el diseño del acceso a datos (índices, consultas eficientes, carga diferida), en la generación de PDF y en la validación con pruebas de carga (k6). |
| **DA04** | La estructura académica (niveles, grados, secciones, cursos, periodos y escala) debe ser configurable por institución sin cambiar el código. | RF05–RF07, AC06, RC14 | Determina un modelo de datos genérico y un módulo de Gestión Académica del que dependen Matrícula, Asistencia y Evaluación. |
| **DA05** | El sistema debe construirse como un monolito modular en Laravel con Blade/Livewire y MySQL. | RC03, RC04, RC05, RC10 | Fija el estilo arquitectónico en tres capas dentro de una sola aplicación, con separación por módulos en lugar de servicios distribuidos. |
| **DA06** | El sistema debe desplegarse con Docker en infraestructura de bajo costo. | RC08, RC09, AC09 | Limita el número de componentes desplegables (aplicación, base de datos y proxy HTTPS) y descarta arquitecturas con muchos servicios. |
| **DA07** | La lógica de negocio debe poder probarse de forma automatizada (unitarias, integración y carga) antes de cada despliegue. | AC07, RC11, RC12 | Exige separar la lógica en clases de servicio independientes de controladores y vistas, para poder probarla de forma aislada. |
| **DA08** | Las acciones críticas deben quedar auditadas. | RF22, AC10, RC15 | Añade un componente transversal de auditoría que registra eventos de los módulos de Autenticación, Evaluación y gestión de usuarios. |

## Ejemplo de construcción de un driver

- **Requisito funcional:** el sistema debe ofrecer un asistente virtual que responda consultas sobre notas y asistencia.
- **Atributo de calidad:** el asistente no debe exponer datos de otros estudiantes.
- **Restricción:** se debe usar la API externa de Claude (Anthropic).
- **DRIVER ARQUITECTÓNICO (DA02):** seguridad e integración controlada del asistente virtual con IA.
