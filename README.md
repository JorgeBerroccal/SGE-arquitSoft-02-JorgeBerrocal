# Sistema de Gestión Escolar (SGE)

## Nombre

Berrocal Yucra, Jorge Luis

## Descripción

Sistema web genérico, ligero y adaptable que permite a instituciones educativas públicas y privadas de primaria y secundaria del Perú digitalizar sus procesos académicos esenciales: **matrícula, asistencia y evaluación**, con control de acceso basado en roles (RBAC) y un **asistente virtual con IA** para padres de familia y alumnos.

El proyecto se plantea como un **Producto Mínimo Viable (MVP)** de 6 módulos:

| N.° | Módulo |
|-----|--------|
| 1 | Autenticación y Roles (RBAC) |
| 2 | Gestión Académica Base |
| 3 | Matrícula |
| 4 | Asistencia |
| 5 | Evaluación y Boletas |
| 6 | Asistente Virtual (Chatbot IA) |

## Caso de estudio

Sistema de Gestión Escolar (SGE) para instituciones educativas de primaria y secundaria, con estructura académica configurable (niveles, grados, secciones y evaluación por competencias según el Currículo Nacional).

## Curso

Arquitectura de Software (IS-488) — Docente: Ing. Lizbeth Jaico Quispe — Semestre 2026-II

## Estructura del repositorio

```
SGE-arquitSoft-02-JorgeBerrocal/
├── docs/                                   Documentación del proyecto
│   ├── 01-analisis-de-sistema/
│   │   ├── 00-necesidad-del-negocio.md
│   │   ├── 01-actores.md
│   │   ├── 02-historias-del-usuario.md
│   │   ├── 03-requisitos-funcionales.md
│   │   ├── 04-atributos-de-calidad.md
│   │   ├── 05-restricciones.md
│   │   ├── 06-driver-arquitectonicos.md
│   │   ├── 07-decisiones-arquitectonicas.md
│   │   └── 08-escenarios-atributos-calidad.md
│   ├── 02-arquitectura-software/
│   │   ├── arquitectura-inicial.md
│   │   ├── estilo-arquitectonico.md
│   │   └── enfoque-arquitectonico.md
│   └── img/                                Imágenes (PNG) y sus fuentes editables
│       └── fuentes/                        Especificaciones Archify (.archify.json) y versiones interactivas (.html)
├── tecnologia/                             Recursos tecnológicos
│   └── pruebas-carga/
│       └── k6-1000-usuarios.js
├── .gitignore
└── README.md
```

## Documentación

| N.° | Entregable | Ubicación |
|-----|------------|-----------|
| 1 | Necesidad del negocio | [`00-necesidad-del-negocio.md`](docs/01-analisis-de-sistema/00-necesidad-del-negocio.md) |
| 2 | Actores e historias de usuario | [`01-actores.md`](docs/01-analisis-de-sistema/01-actores.md) y [`02-historias-del-usuario.md`](docs/01-analisis-de-sistema/02-historias-del-usuario.md) |
| 3 | Requisitos y restricciones | [`03-requisitos-funcionales.md`](docs/01-analisis-de-sistema/03-requisitos-funcionales.md) y [`05-restricciones.md`](docs/01-analisis-de-sistema/05-restricciones.md) |
| 4 | Atributos de calidad y escenarios medibles | [`04-atributos-de-calidad.md`](docs/01-analisis-de-sistema/04-atributos-de-calidad.md) y [`08-escenarios-atributos-calidad.md`](docs/01-analisis-de-sistema/08-escenarios-atributos-calidad.md) |
| 5 | Drivers arquitectónicos | [`06-driver-arquitectonicos.md`](docs/01-analisis-de-sistema/06-driver-arquitectonicos.md) |
| 6 | Decisiones arquitectónicas (ADR) | [`07-decisiones-arquitectonicas.md`](docs/01-analisis-de-sistema/07-decisiones-arquitectonicas.md) |
| 7 | Arquitectura inicial | [`arquitectura-inicial.md`](docs/02-arquitectura-software/arquitectura-inicial.md) |
| 8 | Estilo arquitectónico | [`estilo-arquitectonico.md`](docs/02-arquitectura-software/estilo-arquitectonico.md) |
| 9 | Enfoque arquitectónico (Clean Architecture) | [`enfoque-arquitectonico.md`](docs/02-arquitectura-software/enfoque-arquitectonico.md) |

## Diagrama de arquitectura

![Arquitectura del SGE](docs/img/sge-arquitectura-inicial.png)

Generado con [Archify](https://github.com/tt-a1i/archify). Versión interactiva: [`docs/img/fuentes/sge-arquitectura-inicial.html`](docs/img/fuentes/sge-arquitectura-inicial.html).
