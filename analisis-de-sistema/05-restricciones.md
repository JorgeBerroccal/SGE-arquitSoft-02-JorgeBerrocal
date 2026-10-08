# 05 - Restricciones

> Condiciones, reglas o limitaciones que deben respetarse durante el desarrollo del sistema. Pueden ser tecnológicas, organizacionales, legales o del proyecto.

## Restricciones identificadas

| ID | Tipo | Restricción | Descripción |
|----|------|-------------|-------------|
| RC01 | Tecnológica | Aplicación web responsive | El sistema debe ser accesible desde un navegador web, adaptable a celulares y tablets. |
| RC02 | Organizacional | Control de versiones | El código y la documentación deben gestionarse con Git y mantenerse en un repositorio de GitHub. |
| RC03 | Tecnológica | Framework backend | El backend debe desarrollarse con Laravel 11 (PHP 8.3 o superior). |
| RC04 | Tecnológica | Interfaz sin frontend separado | La capa de presentación debe construirse con Blade y Livewire dentro del mismo proyecto Laravel (no se desarrollará una SPA separada). |
| RC05 | Tecnológica | Base de datos | La información debe almacenarse en MySQL 8, accedida mediante el ORM Eloquent y migraciones versionadas. |
| RC06 | Tecnológica | Autenticación | La autenticación debe implementarse con Laravel Breeze/Sanctum y un modelo RBAC propio (tablas Rol, Permiso y Rol_Permiso). |
| RC07 | Tecnológica | Servicio de IA externo | El asistente virtual debe consumir la API de Claude (Anthropic), preferentemente un modelo económico como Claude Haiku. |
| RC08 | Tecnológica | Contenedores | La aplicación debe contenerizarse con Docker y Docker Compose. Laravel Sail se usa solo en desarrollo; en producción la aplicación debe servirse con Nginx + PHP-FPM (o Laravel Octane), no con el servidor de desarrollo de Sail. |
| RC09 | Económica | Infraestructura de bajo costo | El sistema debe funcionar en un VPS de bajo costo o en una PaaS económica (Railway/Render). Para la prueba de carga de 1000 usuarios se requiere un VPS de 2 vCPU / 4 GB de RAM como mínimo; los 2 GB de la propuesta inicial quedan como referencia para la operación normal de un colegio. |
| RC10 | Del proyecto | Plazo | El desarrollo tiene un plazo de dos meses, por lo que se limita a un MVP de 6 módulos. |
| RC11 | Del proyecto | Metodología | El desarrollo debe seguir Spec-Driven Development: especificación → plan → tareas → implementación asistida con Claude Code → verificación. |
| RC12 | Del proyecto | Pruebas | Deben incluirse pruebas unitarias y de integración con Pest/PHPUnit y pruebas de carga con k6. |
| RC13 | Del proyecto | Una institución por instalación | El sistema es configurable, pero cada instalación atiende a una sola institución (no es multi-tenant). |
| RC14 | Normativa | Evaluación por competencias | La evaluación debe ajustarse a la estructura del Currículo Nacional (competencias por curso, escala literal AD/A/B/C o vigesimal). |
| RC15 | Legal | Protección de datos personales | El tratamiento de datos de estudiantes (menores de edad) y apoderados debe respetar la Ley N.° 29733, Ley de Protección de Datos Personales del Perú. |
| RC16 | Seguridad | HTTPS | En producción, la comunicación debe cifrarse con HTTPS (certificado Let's Encrypt mediante Caddy o Nginx + Certbot). |
| RC17 | Alcance | Fuera de alcance | No se implementarán gestión financiera, inscripciones online, mensajería interna ni certificados oficiales en esta versión. |
| RC18 | Del proyecto | Asistente virtual fuera de la prueba de carga | En la prueba de carga de 1000 usuarios, el asistente virtual se excluye o responde con una respuesta simulada (sin llamar al servicio externo), debido a los límites de solicitudes y al costo de la API de Claude. |
