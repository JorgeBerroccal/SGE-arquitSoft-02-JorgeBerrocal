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
├── analisis-de-sistema/
│   ├── 01-actores.md
│   ├── 02-historias-del-usuario.md
│   ├── 03-requisitos-funcionales.md
│   ├── 04-atributos-de-calidad.md
│   ├── 05-restricciones.md
│   └── 06-driver-arquitectonicos.md
├── arquitectura/
│   ├── arquitectura-inicial.md
│   └── archify/
│       ├── sge-arquitectura.archify.json
│       ├── sge-arquitectura.html
│       └── sge-arquitectura.png
├── .gitignore
└── README.md
```

## Etapas del trabajo (Guía 02)

| Etapa | Contenido | Ubicación |
|-------|-----------|-----------|
| Etapa 1: Análisis del sistema | Actores, historias de usuario, requisitos funcionales, atributos de calidad, restricciones y drivers arquitectónicos | [`analisis-de-sistema/`](analisis-de-sistema/) |
| Etapa 2: Diseño arquitectónico inicial | Capas, módulos, responsabilidades, dependencias, sistemas externos y diagramas (Mermaid y Archify) | [`arquitectura/arquitectura-inicial.md`](arquitectura/arquitectura-inicial.md) |

## Diagrama de arquitectura

![Arquitectura del SGE](arquitectura/archify/sge-arquitectura.png)

Generado con [Archify](https://github.com/tt-a1i/archify). Versión interactiva: [`arquitectura/archify/sge-arquitectura.html`](arquitectura/archify/sge-arquitectura.html).
