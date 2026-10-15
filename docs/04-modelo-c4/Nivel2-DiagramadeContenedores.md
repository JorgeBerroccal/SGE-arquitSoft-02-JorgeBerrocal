# Nivel 2 · Diagrama de contenedores

> **Proyecto:** Sistema de Gestión Escolar (SGE)
> **Modelo:** C4 · Nivel 2 de 4

## 1. Objetivo

Abrir la caja «SGE» del nivel 1 y mostrar los **contenedores** que la forman: las aplicaciones y almacenes de datos que se ejecutan por separado, la tecnología de cada uno y cómo se comunican.

| Aspecto | Descripción |
|---|---|
| Pregunta que responde | ¿De qué piezas ejecutables se compone el sistema y con qué tecnología? |
| Audiencia | Arquitectura, desarrollo y despliegue |
| Qué es un contenedor | Algo que se ejecuta o almacena datos de forma independiente: una aplicación, una base de datos, un proceso en segundo plano. **No es lo mismo que un contenedor Docker**, aunque en el SGE cada uno se despliega en su propio contenedor Docker |
| Nivel anterior | [`Nivel1-DiagramadeContextodelSistema.md`](Nivel1-DiagramadeContextodelSistema.md) |
| Siguiente nivel | [`Nivel3-Diagrama-de-Componentes.md`](Nivel3-Diagrama-de-Componentes.md) |

## 2. Diagrama

![Diagrama de contenedores del SGE](../img/nivel2-contenedores.png)

> Fuente editable: [`img/fuentes/nivel2-contenedores.archify.json`](../img/fuentes/nivel2-contenedores.archify.json) · versión interactiva: [`nivel2-contenedores.html`](../img/fuentes/nivel2-contenedores.html)

## 3. Contenedores

| Contenedor | Tecnología | Responsabilidad | Datos que maneja | Origen |
|---|---|---|---|---|
| **Servidor web** | Nginx | Recibe las solicitudes HTTPS (Let's Encrypt), sirve archivos estáticos y entrega el resto a la aplicación | Ninguno | RC08, RC16 |
| **Aplicación web SGE** | Laravel 11 · PHP 8.3 · PHP-FPM con OPcache · Blade + Livewire | Genera las pantallas, aplica RBAC y las reglas de negocio de los seis módulos. Es un **monolito modular** | Ninguno propio; usa la base de datos y Redis | DA05, RC03, RC04 |
| **Worker de cola** | Laravel `queue:work` (misma imagen que la aplicación) | Genera las boletas PDF fuera de la solicitud del usuario | Archivos PDF generados | RF17, DA03 |
| **Base de datos** | MySQL 8 | Almacena de forma permanente la información académica | Usuarios, roles, permisos, estructura académica, matrículas, asistencias, calificaciones, bitácora | RC05 |
| **Caché y cola** | Redis | Guarda sesiones, caché de permisos y de estructura académica, y la cola de trabajos | Datos temporales | DA03 |

## 4. Relaciones

| Origen | Destino | Descripción | Protocolo |
|---|---|---|---|
| Usuarios | Servidor web | Usan el sistema desde el navegador o el celular | HTTPS |
| Servidor web | Aplicación web | Entrega las solicitudes a PHP-FPM | FastCGI |
| Aplicación web | Base de datos | Lee y escribe información | SQL sobre TCP (:3306) |
| Aplicación web | Caché y cola | Guarda sesiones y caché; encola las boletas | TCP (:6379) |
| Worker de cola | Caché y cola | Toma los trabajos pendientes | TCP (:6379) |
| Worker de cola | Base de datos | Lee las notas para armar la boleta | SQL sobre TCP |
| Aplicación web | API de Claude | Envía la pregunta con el contexto autorizado | HTTPS/REST |

## 5. Decisiones arquitectónicas reflejadas

| Decisión | Alternativa descartada | Motivo | ADR |
|---|---|---|---|
| Un único contenedor de aplicación (monolito modular) | Microservicios | Un desarrollador y dos meses de plazo; los módulos permiten separar servicios en el futuro | ADR-001 |
| Pantallas generadas en el servidor (Blade + Livewire) | SPA + API REST | Restricción RC04; evita duplicar el trabajo en dos proyectos | — |
| Nginx + PHP-FPM | Servidor de desarrollo de Sail | Soportar la prueba de 1000 usuarios | ADR-008 |
| Redis para sesiones, caché y cola | Sesiones en archivos y consultas siempre a MySQL | Reducir la carga sobre MySQL | ADR-006 |
| Worker de cola para las boletas | Generar el PDF en la solicitud | El PDF consume CPU y memoria y bloquearía a otros usuarios | ADR-007 |
| Solo la aplicación se comunica con la API de Claude | Llamar a la API desde el navegador | La clave de la API queda en el servidor y el contexto se filtra antes de enviarse | ADR-004 |
| Todo en un VPS de 2 vCPU / 4 GB con Docker Compose | Nube pública (AWS) | Costo fijo y despliegue simple | ADR-009 |
