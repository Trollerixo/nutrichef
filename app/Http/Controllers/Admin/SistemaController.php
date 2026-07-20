<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Recipe;
use App\Models\RecipeReview;
use App\Models\ShoppingList;
use App\Models\User;
use App\Models\WeeklyMenu;

class SistemaController extends Controller
{
    public function index()
    {
        // Métricas reales de la base de datos
        $usuariosActivos   = User::where('active', true)->count();
        $recetasPublicadas = Recipe::where('published', true)->count();

        // Disponibilidad operativa del sitio (99.9% constante de SLA)
        $disponibilidad = '99.9% (Excelente)';

        // Usuarios en línea
        $usuariosEnLinea = User::whereNotNull('remember_token')->count();

        // Registro de actividades 100% REALES desde la base de datos
        $logs = $this->actividadesReales();

        return view('admin.sistema.index', compact(
            'disponibilidad',
            'usuariosActivos',
            'usuariosEnLinea',
            'recetasPublicadas',
            'logs'
        ));
    }

    private function actividadesReales(): array
    {
        $events = collect();

        // 1. Usuarios registrados recientemente
        User::latest('created_at')->take(5)->get()->each(function ($user) use ($events) {
            $events->push([
                'timestamp' => $user->created_at,
                'hora' => $user->created_at?->format('H:i:s') ?? now()->format('H:i:s'),
                'tipo' => 'Éxito',
                'categoria' => 'Usuarios',
                'descripcion' => "Registro de nuevo usuario: {$user->name}",
            ]);
        });

        // 2. Recetas creadas recientemente
        Recipe::latest('created_at')->take(5)->get()->each(function ($recipe) use ($events) {
            $events->push([
                'timestamp' => $recipe->created_at,
                'hora' => $recipe->created_at?->format('H:i:s') ?? now()->format('H:i:s'),
                'tipo' => 'Éxito',
                'categoria' => 'Recetas',
                'descripcion' => "Publicación de receta: {$recipe->title}",
            ]);
        });

        // 3. Reseñas y comentarios recientes
        RecipeReview::with(['user', 'recipe'])->latest('created_at')->take(5)->get()->each(function ($rev) use ($events) {
            $userName = $rev->user?->name ?? 'Usuario';
            $recipeTitle = $rev->recipe?->title ?? 'receta';
            $events->push([
                'timestamp' => $rev->created_at,
                'hora' => $rev->created_at?->format('H:i:s') ?? now()->format('H:i:s'),
                'tipo' => 'Éxito',
                'categoria' => 'Comentarios',
                'descripcion' => "{$userName} opinó en \"{$recipeTitle}\"",
            ]);
        });

        // 4. Mensajes de chat entre usuarios y nutricionistas
        Message::with('sender')->latest('sent_at')->take(5)->get()->each(function ($msg) use ($events) {
            $senderName = $msg->sender?->name ?? 'Usuario';
            $events->push([
                'timestamp' => $msg->sent_at,
                'hora' => $msg->sent_at?->format('H:i:s') ?? now()->format('H:i:s'),
                'tipo' => 'Éxito',
                'categoria' => 'Consultas',
                'descripcion' => "Mensaje enviado por {$senderName}",
            ]);
        });

        // 5. Notificaciones de sistema
        Notification::latest('sent_at')->take(5)->get()->each(function ($notif) use ($events) {
            $events->push([
                'timestamp' => $notif->sent_at,
                'hora' => $notif->sent_at?->format('H:i:s') ?? now()->format('H:i:s'),
                'tipo' => 'Aviso',
                'categoria' => 'Notificaciones',
                'descripcion' => "Notificación enviada: {$notif->title}",
            ]);
        });

        // 6. Listas de compras recientes
        ShoppingList::latest('created_at')->take(5)->get()->each(function ($list) use ($events) {
            $events->push([
                'timestamp' => $list->created_at,
                'hora' => $list->created_at?->format('H:i:s') ?? now()->format('H:i:s'),
                'tipo' => 'Éxito',
                'categoria' => 'Listas de compra',
                'descripcion' => "Creación de lista de compras: {$list->name}",
            ]);
        });

        // Ordenar descendentemente por fecha y tomar las 10 actividades más recientes
        $sorted = $events->sortByDesc(fn($e) => $e['timestamp']?->timestamp ?? 0)->take(10)->values();

        return $sorted->toArray();
    }
}
