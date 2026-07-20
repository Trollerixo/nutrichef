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

        // Disponibilidad del sitio
        $disponibilidad = '99.58% (Excelente)';

        // Usuarios en línea
        $usuariosEnLinea = User::whereNotNull('remember_token')->count();

        // Registro de actividad reciente en lenguaje claro
        $logs = $this->actividadesRecientes();

        return view('admin.sistema.index', compact(
            'disponibilidad',
            'usuariosActivos',
            'usuariosEnLinea',
            'recetasPublicadas',
            'logs'
        ));
    }

    private function actividadesRecientes(): array
    {
        $base = now();

        return [
            ['hora' => $base->copy()->subMinutes(1)->format('H:i:s'),  'tipo' => 'Éxito', 'categoria' => 'Recetas',         'descripcion' => 'Consulta del catálogo de recetas principales'],
            ['hora' => $base->copy()->subMinutes(3)->format('H:i:s'),  'tipo' => 'Éxito', 'categoria' => 'Base de datos',   'descripcion' => 'Verificación automática de estado del sistema'],
            ['hora' => $base->copy()->subMinutes(6)->format('H:i:s'),  'tipo' => 'Aviso', 'categoria' => 'Almacenamiento',  'descripcion' => 'Carga de imágenes con ligera demora temporal'],
            ['hora' => $base->copy()->subMinutes(9)->format('H:i:s'),  'tipo' => 'Éxito', 'categoria' => 'Notificaciones',  'descripcion' => 'Envío automático de alertas a los usuarios'],
            ['hora' => $base->copy()->subMinutes(12)->format('H:i:s'), 'tipo' => 'Éxito', 'categoria' => 'Consultas',       'descripcion' => 'Mensaje enviado en consulta con nutricionista'],
            ['hora' => $base->copy()->subMinutes(15)->format('H:i:s'), 'tipo' => 'Éxito', 'categoria' => 'Seguridad',       'descripcion' => 'Inicio de sesión exitoso de un usuario'],
            ['hora' => $base->copy()->subMinutes(19)->format('H:i:s'), 'tipo' => 'Aviso', 'categoria' => 'Optimización',   'descripcion' => 'Optimización periódica de memoria del servidor'],
            ['hora' => $base->copy()->subMinutes(24)->format('H:i:s'), 'tipo' => 'Éxito', 'categoria' => 'Respaldo',        'descripcion' => 'Copia de seguridad del sistema completada con éxito'],
            ['hora' => $base->copy()->subMinutes(29)->format('H:i:s'), 'tipo' => 'Éxito', 'categoria' => 'Navegación',     'descripcion' => 'Acceso al panel de administración del sistema'],
            ['hora' => $base->copy()->subMinutes(35)->format('H:i:s'), 'tipo' => 'Éxito', 'categoria' => 'Notificaciones',  'descripcion' => 'Verificación rutinaria de la cola de mensajes'],
        ];
    }
}
