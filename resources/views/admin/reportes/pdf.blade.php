<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte NutriChef</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1, h2 { margin: 0 0 0.5rem; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; text-align: left; }
        th { background: #f8f9fa; }
        .section { margin-bottom: 1.5rem; }
    </style>
</head>
<body>
    <h1>Reporte NutriChef</h1>
    <p>Generado el {{ now()->format('d/m/Y H:i') }}</p>

    <div class="section">
        <h2>Usuarios por mes</h2>
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

    <div class="section">
        <h2>Recetas más vistas</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Vistas aproximadas</th>
                    <th>Puntuación promedio</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recetasMasVistas as $recipe)
                    <tr>
                        <td>{{ $recipe->id }}</td>
                        <td>{{ $recipe->title }}</td>
                        <td>{{ $recipe->rating_count }}</td>
                        <td>{{ $recipe->rating_avg }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
