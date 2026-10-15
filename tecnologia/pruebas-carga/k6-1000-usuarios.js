// Prueba de carga del SGE: 1000 usuarios virtuales simultáneos (AC03, DA03).
//
// Meta: p95 del tiempo de respuesta < 2 s y menos de 1 % de solicitudes con error.
// Las cifras son metas; el resultado real se obtiene al ejecutar esta prueba.
//
// Uso:
//   k6 run -e BASE_URL=https://tu-dominio -e CLAVE=<clave-de-prueba> tecnologia/pruebas-carga/k6-1000-usuarios.js
//
// Variables de entorno:
//   BASE_URL  URL del sistema desplegado (por defecto http://localhost).
//   CLAVE     Contraseña común de los usuarios de prueba. Si no se indica, se omite
//             el inicio de sesión y solo se consultan las rutas.
//   PREFIJO   Prefijo del correo de los usuarios de prueba (por defecto "alumno").
//   DOMINIO   Dominio del correo de los usuarios de prueba (por defecto "sge.test").
//
// Requisitos antes de ejecutar:
//   - Usuarios de prueba creados con un seeder: alumno1@sge.test … alumno1000@sge.test.
//   - Boletas ya generadas (la generación del PDF va por la cola, no en la solicitud).
//   - Subir el límite de solicitudes (rate limiting) para la IP de k6: todas las
//     solicitudes salen de una sola IP y los errores 429 no serían caídas reales.
//   - El asistente virtual queda fuera de esta prueba (RC18).
//
// IMPORTANTE: las rutas de RUTAS y del inicio de sesión son provisionales; todavía no
// existe la aplicación Laravel. Ajustarlas cuando se implementen los módulos.

import http from 'k6/http';
import { check, group, sleep } from 'k6';

const BASE_URL = __ENV.BASE_URL || 'http://localhost';
const CLAVE = __ENV.CLAVE;
const PREFIJO = __ENV.PREFIJO || 'alumno';
const DOMINIO = __ENV.DOMINIO || 'sge.test';

// Pantallas que consulta un padre o alumno al cierre de bimestre.
const RUTAS = {
  notas: '/notas',
  asistencia: '/asistencia',
  boleta: '/boletas/actual',
};

export const options = {
  // Subida gradual (ramp-up): evita 1000 inicios de sesión en el mismo segundo,
  // porque el hash de contraseñas (bcrypt) es costoso para la CPU.
  stages: [
    { duration: '2m', target: 300 },  // escalón 1: de 0 a 300 usuarios
    { duration: '3m', target: 1000 }, // escalón 2: de 300 a 1000 usuarios
    { duration: '5m', target: 1000 }, // carga sostenida con 1000 usuarios
    { duration: '1m', target: 0 },    // bajada
  ],
  // Umbrales: si alguno no se cumple, k6 termina con error.
  thresholds: {
    http_req_duration: ['p(95)<2000'], // p95 menor a 2000 ms
    http_req_failed: ['rate<0.01'],    // menos de 1 % de solicitudes con error
  },
};

// Cada usuario virtual tiene su propia copia de esta variable y su propio
// tarro de cookies, así que inicia sesión una sola vez.
let sesionIniciada = false;

// Pausa aleatoria de 2 a 5 segundos, como un usuario real entre un clic y otro.
function pausa() {
  sleep(2 + Math.random() * 3);
}

// Inicio de sesión de Laravel Breeze: primero se pide el formulario para obtener
// el token CSRF y luego se envían las credenciales.
function iniciarSesion() {
  const formulario = http.get(`${BASE_URL}/login`);
  const token = formulario.html().find('input[name=_token]').attr('value');

  const respuesta = http.post(`${BASE_URL}/login`, {
    _token: token,
    email: `${PREFIJO}${__VU}@${DOMINIO}`,
    password: CLAVE,
  });

  sesionIniciada = check(respuesta, {
    'inicio de sesión correcto': (r) => r.status === 200 && !r.url.endsWith('/login'),
  });
}

function consultar(nombre, ruta) {
  const respuesta = http.get(`${BASE_URL}${ruta}`, { tags: { pantalla: nombre } });
  check(respuesta, { [`${nombre}: estado 200`]: (r) => r.status === 200 });
}

export default function () {
  if (CLAVE && !sesionIniciada) {
    group('inicio de sesión', iniciarSesion);
    pausa();
  }

  group('consulta de notas', () => consultar('notas', RUTAS.notas));
  pausa();

  group('consulta de asistencia', () => consultar('asistencia', RUTAS.asistencia));
  pausa();

  group('descarga de boleta', () => consultar('boleta', RUTAS.boleta));
  pausa();
}
