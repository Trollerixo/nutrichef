<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte NutriChef</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h1 { margin: 0 0 0.2rem; font-size: 18px; }
        h2 { margin: 0 0 0.6rem; font-size: 14px; }
        .subtitle { color: #666; font-size: 10px; margin-bottom: 1rem; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.4rem; text-align: left; font-size: 10px; }
        th { background: #f8f9fa; }
        .section { margin-bottom: 1.5rem; page-break-inside: avoid; }
        .chart { margin-bottom: 1rem; }
        .bar-row { display: flex; align-items: center; margin-bottom: 0.3rem; }
        .bar-label { width: 80px; font-size: 9px; color: #555; flex-shrink: 0; }
        .bar-track { flex: 1; height: 18px; background: #f0f0f0; border-radius: 3px; overflow: hidden; }
        .bar-fill { height: 100%; border-radius: 3px; }
        .bar-value { width: 40px; text-align: right; font-size: 10px; font-weight: bold; color: #333; flex-shrink: 0; margin-left: 6px; }
    </style>
</head>
<body>
    <h1>Reporte NutriChef</h1>
    <p class="subtitle">Generado el {{ now()->format('d/m/Y H:i') }}</p>

    {{-- Usuarios por mes --}}
    <div class="section">
        <h2>Usuarios por mes</h2>

        @php
            $maxUsers = max(array_column($usuariosPorMes->toArray(), 'total'));
            $maxUsers = $maxUsers > 0 ? $maxUsers : 1;
        @endphp

        <div class="chart">
            @foreach($usuariosPorMes as $item)
                @php
                    $pct = ($item['total'] / $maxUsers) * 100;
                @endphp
                <div class="bar-row">
                    <div class="bar-label">{{ $item['mes'] }}</div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width: {{ $pct }}%; background: #6c757d;"></div>
                    </div>
                    <div class="bar-value">{{ $item['total'] }}</div>
                </div>
            @endforeach
        </div>

        <table>
            <thead>
                <tr>
                    <th>Mes</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuariosPorMes as $item)
                    <tr>
                        <td>{{ $item['mes'] }}</td>
                        <td>{{ $item['total'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Recetas más vistas --}}
    <div class="section">
        <h2>Recetas más vistas</h2>

        @php
            $maxRating = $recetasMasVistas->max('rating_count');
            $maxRating = $maxRating > 0 ? $maxRating : 1;
        @endphp

        <div class="chart">
            @foreach($recetasMasVistas as $recipe)
                @php
                    $pct = ($recipe->rating_count / $maxRating) * 100;
                @endphp
                <div class="bar-row">
                    <div class="bar-label" style="width: 120px;">{{ Str::limit($recipe->title, 20) }}</div>
                    <div class="bar-track">
                        <div class="bar-fill" style="width: {{ $pct }}%; background: #4a90d9;"></div>
                    </div>
                    <div class="bar-value">{{ $recipe->rating_count }}</div>
                </div>
            @endforeach
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Reseñas</th>
                    <th>Puntuación</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recetasMasVistas as $recipe)
                    <tr>
                        <td>{{ $recipe->id }}</td>
                        <td>{{ $recipe->title }}</td>
                        <td>{{ $recipe->rating_count }}</td>
                        <td>{{ number_format($recipe->rating_avg, 1) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
