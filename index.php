<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_error.log');

// Intenta forzar permisos por si acaso
if (!is_dir(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0777, true);
}
require_once __DIR__ . '/vendor/autoload.php';

use App\Bootstrap\App;
use App\Services\Logger;

App::init();
if ($_ENV['APP_DEBUG'] ?? false) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];

// Definimos rutas por método
$routes = [
    'POST' => [
        'api/auth/login' => 'controllers/auth/login.php',
        'api/usuarios'   => 'controllers/usuarios/crear.php',
        'api/servicio-auditar' => 'controllers/servicioAuditar/crear.php',
        'api/sedes' => 'controllers/sedes/crear.php',
        'api/eps' => 'controllers/eps/crear.php',
        'api/profesional' => 'controllers/profesional/crear.php',
        'api/dimenciones' => 'controllers/dimenciones/crear.php',
        'api/criterios' => 'controllers/criterios/crear.php',
        'api/cie10' => 'controllers/cie10/crear.php',
        'api/auditoria' => 'controllers/auditoria/crear.php',
        'api/respuesta' => 'controllers/respuesta/crear.php',
        'api/pacientes' => 'controllers/pacientes/crear.php',
        'api/auditoriaCies' => 'controllers/auditoriaCies/crear.php',
        'api/formularioAuditoria' => 'controllers/formularioAuditoria/crearNuevoFormulario.php',
    ],
    'GET' => [
        'api/usuarios'         => 'controllers/usuarios/listar.php',
        'api/usuarios/(\d+)'   => 'controllers/usuarios/obtener.php',
        'api/servicio-auditar' => 'controllers/servicioAuditar/listar.php',
        'api/servicio-auditar/(\d+)' => 'controllers/servicioAuditar/obtener.php',
        'api/servicio-auditar/nombre/([^/]+)' => 'controllers/servicioAuditar/obtenerPorNombre.php',
        'api/sedes' => 'controllers/sedes/listar.php',
        'api/sedes/(\d+)' => 'controllers/sedes/obtener.php',
        'api/sedes/nombre/([^/]+)' => 'controllers/sedes/obtenerPorNombre.php',
        'api/eps' => 'controllers/eps/listar.php',
        'api/eps/(\d+)' => 'controllers/eps/obtener.php',
        'api/profesional' => 'controllers/profesional/listar.php',
        'api/profesional/(\d+)' => 'controllers/profesional/obtener.php',
        'api/profesional/filtro/([^/]+)' => 'controllers/profesional/obtenerPorNombre.php',
        'api/dimenciones' => 'controllers/dimenciones/listar.php',
        'api/dimenciones/(\d+)' => 'controllers/dimenciones/obtener.php',
        'api/dimencionesDto/obtenerTodosCriterios' => 'controllers/dimenciones/obtenerTodosCriterios.php',
        'api/criterios' => 'controllers/criterios/listar.php',
        'api/criterios/(\d+)' => 'controllers/criterios/obtener.php',
        'api/cie10' => 'controllers/cie10/listar.php',
        'api/cie10/(\d+)' => 'controllers/cie10/obtener.php',
        'api/cie10/filtro/([^/]+)' => 'controllers/cie10/obtenerPorNombre.php',
        'api/auditoria/(\d+)' => 'controllers/auditoria/obtener.php',
        'api/auditoria/informe' => 'controllers/auditoria/InformeAuditoria.php',
        'api/auditoria/recientes' => 'controllers/auditoria/auditoriaRecientes.php',
        'api/auditoria/resumenHoy/(\d{4}-\d{2}-\d{2})' => 'controllers/auditoria/resumenHoy.php',
        'api/auditoria/metricas' => 'controllers/auditoria/metricasAuditoria.php',
        'api/auditoria/listarAuditorias' => 'controllers/auditoria/listarAuditorias.php',
        'api/auditoria/listarAuditoriasFiltro' => 'controllers/auditoria/listarAuditoriasFiltro.php',
        'api/auditoria/detalle/(\d+)' => 'controllers/auditoria/detalleAuditoria.php',
        'api/auditoria/DetalleEvaluacion/(\d+)' => 'controllers/auditoria/detalleAuditoriaEvaluacion.php',
        'api/auditoria/resumenMensual/(\d+)' => 'controllers/auditoria/obtenerResumenAuditorias.php',
        'api/pacientes/listar' => 'controllers/pacientes/listar.php',
        'api/pacientes/(\d+)' => 'controllers/pacientes/obtener.php',
        'api/pacientes/filtro/([^/]+)' => 'controllers/pacientes/obtenerPorNombre.php',
        'api/roles' => 'controllers/roles/listar.php',
        'api/formularioAuditoria' => 'controllers/formularioAuditoria/listar.php',
        'api/obtenerFormularioDimenciones/(\d+)' => 'controllers/formularioAuditoria/obtenerFormularioCompleto.php',
        'api/formularioDimensiones/obtenerDimencionesPorFormulario/(\d+)' => 'controllers/formularioDimensiones/obtenerDimencionesPorFormulario.php',
        'api/reportes/auditoriasCompleto' => 'controllers/auditoria/obtenerReportesCompleto.php',
    ],
    'PUT' => [
        'api/usuarios/(\d+)'   => 'controllers/usuarios/actualizar.php',
        'api/servicio-auditar/(\d+)' => 'controllers/servicioAuditar/actualizar.php',
        'api/sedes/(\d+)' => 'controllers/sedes/actualizar.php',
        'api/eps/(\d+)' => 'controllers/eps/actualizar.php',
        'api/profesional/(\d+)' => 'controllers/profesional/actualizar.php',
        'api/dimenciones/(\d+)' => 'controllers/dimenciones/actualizar.php',
        'api/criterios/(\d+)' => 'controllers/criterios/actualizar.php',
        'api/cie10/(\d+)' => 'controllers/cie10/actualizar.php',
        'api/auditoria/(\d+)' => 'controllers/auditoria/actualizar.php',
        'api/pacientes/(\d+)' => 'controllers/pacientes/actualizar.php',
        'api/formularioAuditoria' => 'controllers/formularioAuditoria/actualizarFormulario.php',
    ],
    'DELETE' => [
        'api/usuarios/(\d+)'   => 'controllers/usuarios/eliminar.php',
        'api/servicio-auditar/(\d+)' => 'controllers/servicioAuditar/eliminar.php',
        'api/sedes/(\d+)' => 'controllers/sedes/eliminar.php',
        'api/eps/(\d+)' => 'controllers/eps/eliminar.php',
        'api/profesional/(\d+)' => 'controllers/profesional/eliminar.php',
        'api/dimenciones/(\d+)' => 'controllers/dimenciones/eliminar.php',
        'api/criterios/(\d+)' => 'controllers/criterios/eliminar.php',
        'api/cie10/(\d+)' => 'controllers/cie10/eliminar.php',
        'api/auditoria/(\d+)' => 'controllers/auditoria/eliminar.php',
        'api/pacientes/(\d+)' => 'controllers/pacientes/eliminar.php',
    ]
];

// ---------------------------------------------------------

$found = false;

if (isset($routes[$method])) {
    foreach ($routes[$method] as $pattern => $file) {
        if (preg_match("#^$pattern$#", $uri, $matches)) {
            $found = true;
            $params = array_slice($matches, 1);

            Logger::info("[$method] $uri");
            require __DIR__ . '/' . $file;
            break;
        }
    }
}

if (!$found) {
    Logger::warning("Ruta no encontrada o método inválido: [$method] $uri");
    http_response_code(404);
    echo json_encode(['error' => 'Ruta no encontrada', 'uri' => $uri]);
}
