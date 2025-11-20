<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_error.log');

// Forzar permisos por si acaso
if (!is_dir(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0777, true);
}

require_once __DIR__ . '/vendor/autoload.php';

use App\Bootstrap\App;
use App\Utils\Router;


App::init();

if ($_ENV['APP_DEBUG'] ?? false) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

/**
 * RUTAS POST
 */

Router::post('api/auth/login', 'controllers/auth/login.php');
Router::post('api/usuarios', 'controllers/usuarios/crear.php');
Router::post('api/usuarios/validarUsuario', 'controllers/usuarios/validarUsuario.php');
Router::post('api/servicio-auditar', 'controllers/servicioAuditar/crear.php');
Router::post('api/sedes', 'controllers/sedes/crear.php');
Router::post('api/eps', 'controllers/eps/crear.php');
Router::post('api/profesional', 'controllers/profesional/crear.php');
Router::post('api/dimenciones', 'controllers/dimenciones/crear.php');
Router::post('api/criterios', 'controllers/criterios/crear.php');
Router::post('api/cie10', 'controllers/cie10/crear.php');
Router::post('api/auditoria', 'controllers/auditoria/crear.php');
Router::post('api/respuesta', 'controllers/respuesta/crear.php');
Router::post('api/pacientes', 'controllers/pacientes/crear.php');
Router::post('api/auditoriaCies', 'controllers/auditoriaCies/crear.php');
Router::post('api/formularioAuditoria', 'controllers/formularioAuditoria/crearNuevoFormulario.php');
Router::post('api/codigoVerificacion/validarCodigo', 'controllers/codigoVerificacion/validarCodigo.php');

/**
 * RUTAS PATCH
 */
Router::patch('api/usuarios/nuevaContrasena', 'controllers/usuarios/cambiarContrasena.php');

/**
 * RUTAS GET
 */

Router::get('api/usuarios', 'controllers/usuarios/listar.php');
Router::get('api/usuarios/(\d+)', 'controllers/usuarios/obtener.php');

Router::get('api/servicio-auditar', 'controllers/servicioAuditar/listar.php');
Router::get('api/servicio-auditar/(\d+)', 'controllers/servicioAuditar/obtener.php');
Router::get('api/servicio-auditar/nombre/([^/]+)', 'controllers/servicioAuditar/obtenerPorNombre.php');

Router::get('api/sedes', 'controllers/sedes/listar.php');
Router::get('api/sedes/(\d+)', 'controllers/sedes/obtener.php');
Router::get('api/sedes/nombre/([^/]+)', 'controllers/sedes/obtenerPorNombre.php');

Router::get('api/eps', 'controllers/eps/listar.php');
Router::get('api/eps/(\d+)', 'controllers/eps/obtener.php');

Router::get('api/profesional', 'controllers/profesional/listar.php');
Router::get('api/profesional/(\d+)', 'controllers/profesional/obtener.php');
Router::get('api/profesional/filtro/([^/]+)', 'controllers/profesional/obtenerPorNombre.php');

Router::get('api/dimenciones', 'controllers/dimenciones/listar.php');
Router::get('api/dimenciones/(\d+)', 'controllers/dimenciones/obtener.php');
Router::get('api/dimencionesDto/obtenerTodosCriterios', 'controllers/dimenciones/obtenerTodosCriterios.php');

Router::get('api/criterios', 'controllers/criterios/listar.php');
Router::get('api/criterios/(\d+)', 'controllers/criterios/obtener.php');

Router::get('api/cie10', 'controllers/cie10/listar.php');
Router::get('api/cie10/(\d+)', 'controllers/cie10/obtener.php');
Router::get('api/cie10/filtro/([^/]+)', 'controllers/cie10/obtenerPorNombre.php');

Router::get('api/auditoria/(\d+)', 'controllers/auditoria/obtener.php');
Router::get('api/auditoria/informe', 'controllers/auditoria/InformeAuditoria.php');
Router::get('api/auditoria/recientes', 'controllers/auditoria/auditoriaRecientes.php');
Router::get('api/auditoria/resumenHoy/(\d{4}-\d{2}-\d{2})', 'controllers/auditoria/resumenHoy.php');
Router::get('api/auditoria/metricas', 'controllers/auditoria/metricasAuditoria.php');
Router::get('api/auditoria/listarAuditorias', 'controllers/auditoria/listarAuditorias.php');
Router::get('api/auditoria/listarAuditoriasFiltro', 'controllers/auditoria/listarAuditoriasFiltro.php');
Router::get('api/auditoria/detalle/(\d+)', 'controllers/auditoria/detalleAuditoria.php');
Router::get('api/auditoria/DetalleEvaluacion/(\d+)', 'controllers/auditoria/detalleAuditoriaEvaluacion.php');
Router::get('api/auditoria/resumenMensual/(\d+)', 'controllers/auditoria/obtenerResumenAuditorias.php');

Router::get('api/pacientes/listar', 'controllers/pacientes/listar.php');
Router::get('api/pacientes/(\d+)', 'controllers/pacientes/obtener.php');
Router::get('api/pacientes/filtro/([^/]+)', 'controllers/pacientes/obtenerPorNombre.php');

Router::get('api/roles', 'controllers/roles/listar.php');
Router::get('api/formularioAuditoria', 'controllers/formularioAuditoria/listar.php');
Router::get('api/obtenerFormularioDimenciones/(\d+)', 'controllers/formularioAuditoria/obtenerFormularioCompleto.php');
Router::get('api/formularioDimensiones/obtenerDimencionesPorFormulario/(\d+)', 'controllers/formularioDimensiones/obtenerDimencionesPorFormulario.php');
Router::get('api/reportes/auditoriasCompleto', 'controllers/auditoria/obtenerReportesCompleto.php');

Router::get('api/userSetting', 'controllers/userSetting/obtener.php');
Router::get('api/userSetting/notificacion', 'controllers/userSetting/obtenerNotificacion.php');

/**
 * RUTAS PUT
 */

Router::put('api/usuarios/(\d+)', 'controllers/usuarios/actualizar.php');
Router::put('api/servicio-auditar/(\d+)', 'controllers/servicioAuditar/actualizar.php');
Router::put('api/sedes/(\d+)', 'controllers/sedes/actualizar.php');
Router::put('api/eps/(\d+)', 'controllers/eps/actualizar.php');
Router::put('api/profesional/(\d+)', 'controllers/profesional/actualizar.php');
Router::put('api/dimenciones/(\d+)', 'controllers/dimenciones/actualizar.php');
Router::put('api/criterios/(\d+)', 'controllers/criterios/actualizar.php');
Router::put('api/cie10/(\d+)', 'controllers/cie10/actualizar.php');
Router::put('api/auditoria/(\d+)', 'controllers/auditoria/actualizar.php');
Router::put('api/pacientes/(\d+)', 'controllers/pacientes/actualizar.php');
Router::put('api/formularioAuditoria', 'controllers/formularioAuditoria/actualizarFormulario.php');
Router::put('api/userSetting/tema', 'controllers/userSetting/cambiarTema.php');
Router::put('api/userSetting/notificaciones', 'controllers/userSetting/actualizarNotificaciones.php');

/**
 * RUTAS DELETE
 */

Router::delete('api/usuarios/(\d+)', 'controllers/usuarios/eliminar.php');
Router::delete('api/servicio-auditar/(\d+)', 'controllers/servicioAuditar/eliminar.php');
Router::delete('api/sedes/(\d+)', 'controllers/sedes/eliminar.php');
Router::delete('api/eps/(\d+)', 'controllers/eps/eliminar.php');
Router::delete('api/profesional/(\d+)', 'controllers/profesional/eliminar.php');
Router::delete('api/dimenciones/(\d+)', 'controllers/dimenciones/eliminar.php');
Router::delete('api/criterios/(\d+)', 'controllers/criterios/eliminar.php');
Router::delete('api/cie10/(\d+)', 'controllers/cie10/eliminar.php');
Router::delete('api/auditoria/(\d+)', 'controllers/auditoria/eliminar.php');
Router::delete('api/pacientes/(\d+)', 'controllers/pacientes/eliminar.php');

// ------------------------
// DESPACHAR RUTAS
// ------------------------
Router::dispatch();
