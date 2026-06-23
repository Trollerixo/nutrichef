<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\User;
use Carbon\Carbon;

class SistemaController extends Controller
{
    public function index()
    {
        // Métricas reales
        $usuariosActivos   = User::where('active', true)->count();
        $recetasPublicadas = Recipe::where('published', true)->count();

        // Uptime simulado (estático para el prototipo)
        $uptime = '99.58%';

        // Sesiones activas: se aproxima con usuarios con remember_token reciente
        $sesionesAhora = User::whereNotNull('remember_token')->count();

        // Log reciente simulado (en producción se leería de un servicio de logs o tabla)
        $logs = $this->logsRecientes();

        return view('admin.sistema.index', compact(
            'uptime',
            'usuariosActivos',
            'sesionesAhora',
            'recetasPublicadas',
            'logs',
        ));
    }

    private function logsRecientes(): array
    {
        $base = now();

        return [
            ['hora' => $base->copy()->subMinutes(0)->format('H:i:s'),  'nivel' => 'INFO', 'servicio' => 'API REST',             'mensaje' => 'GET /recetas respondió 200 en 45 ms'],
            ['hora' => $base->copy()->subMinutes(2)->format('H:i:s'),  'nivel' => 'INFO', 'servicio' => 'Base de datos',        'mensaje' => 'Conexión pool restaurada automáticamente'],
            ['hora' => $base->copy()->subMinutes(4)->format('H:i:s'),  'nivel' => 'WARN', 'servicio' => 'Servicio de imágenes', 'mensaje' => 'Latencia elevada en cdn-images (340 ms)'],
            ['hora' => $base->copy()->subMinutes(7)->format('H:i:s'),  'nivel' => 'INFO', 'servicio' => 'Notificaciones push',  'mensaje' => 'Lote 1.240 notificaciones enviadas'],
            ['hora' => $base->copy()->subMinutes(10)->format('H:i:s'), 'nivel' => 'INFO', 'servicio' => 'Mensajería',           'mensaje' => 'Nuevo mensaje en sala nutri-345'],
            ['hora' => $base->copy()->subMinutes(14)->format('H:i:s'), 'nivel' => 'INFO', 'servicio' => 'API REST',             'mensaje' => 'POST /login respondió 200 en 120 ms'],
            ['hora' => $base->copy()->subMinutes(17)->format('H:i:s'), 'nivel' => 'WARN', 'servicio' => 'Servicio de imágenes', 'mensaje' => 'Cache miss ratio 18% en región eu-west'],
            ['hora' => $base->copy()->subMinutes(22)->format('H:i:s'), 'nivel' => 'INFO', 'servicio' => 'Base de datos',        'mensaje' => 'Backup automático completado (2.3 GB)'],
            ['hora' => $base->copy()->subMinutes(27)->format('H:i:s'), 'nivel' => 'INFO', 'servicio' => 'API REST',             'mensaje' => 'GET /admin/sistema respondió 200 en 28 ms'],
            ['hora' => $base->copy()->subMinutes(32)->format('H:i:s'), 'nivel' => 'INFO', 'servicio' => 'Notificaciones push',  'mensaje' => 'Cola de envío vacía'],
        ];
    }
}
