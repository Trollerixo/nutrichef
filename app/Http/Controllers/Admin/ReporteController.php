<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReporteController extends Controller
{
    public function index()
    {
        // Usuarios registrados por mes (últimos 6 meses)
        $usuariosPorMes = collect(range(5, 0))->map(function ($mesesAtras) {
            $fecha = now()->subMonths($mesesAtras);

            return [
                "mes" => $fecha->locale("es")->isoFormat("MMM"),
                "total" => User::whereYear("created_at", $fecha->year)
                    ->whereMonth("created_at", $fecha->month)
                    ->count(),
            ];
        });

        // Recetas más vistas (por rating_count como aproximación)
        $recetasMasVistas = Recipe::where("published", true)
            ->orderByDesc("rating_count")
            ->limit(5)
            ->get(["id", "title", "rating_count", "rating_avg"]);

        return view(
            "admin.reportes.index",
            compact("usuariosPorMes", "recetasMasVistas"),
        );
    }

    public function export(string $format)
    {
        $usuariosPorMes = collect(range(5, 0))->map(function ($mesesAtras) {
            $fecha = now()->subMonths($mesesAtras);

            return [
                'mes' => $fecha->locale('es')->isoFormat('MMM'),
                'total' => User::whereYear('created_at', $fecha->year)
                    ->whereMonth('created_at', $fecha->month)
                    ->count(),
            ];
        });

        $recetasMasVistas = Recipe::where('published', true)
            ->orderByDesc('rating_count')
            ->limit(5)
            ->get(['id', 'title', 'rating_count', 'rating_avg']);

        $filename = 'reportes_' . now()->format('Ymd_His');

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}.csv\"",
            ];

            $rows = [];
            $rows[] = ['Usuarios por mes'];
            $rows[] = ['Mes', 'Total'];
            foreach ($usuariosPorMes as $item) {
                $rows[] = [$item['mes'], $item['total']];
            }

            $rows[] = [];
            $rows[] = ['Recetas más vistas'];
            $rows[] = ['ID', 'Título', 'Vistas aproximadas', 'Puntuación promedio'];
            foreach ($recetasMasVistas as $recipe) {
                $rows[] = [$recipe->id, $recipe->title, $recipe->rating_count, $recipe->rating_avg];
            }

            return response()->streamDownload(function () use ($rows) {
                $output = fopen('php://output', 'w');
                fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
                foreach ($rows as $row) {
                    fputcsv($output, $row);
                }
                fclose($output);
            }, "{$filename}.csv", $headers);
        }

            if ($format === 'excel') {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setTitle('Usuarios');
                $sheet->fromArray(['Mes', 'Total'], null, 'A1');
                $row = 2;
                foreach ($usuariosPorMes as $item) {
                    $sheet->fromArray([$item['mes'], $item['total']], null, "A{$row}");
                    $row++;
                }

                $spreadsheet->createSheet();
                $sheet2 = $spreadsheet->getSheet(1);
                $sheet2->setTitle('Recetas');
                $sheet2->fromArray(['ID', 'Título', 'Vistas aproximadas', 'Puntuación promedio'], null, 'A1');
                $row = 2;
                foreach ($recetasMasVistas as $recipe) {
                    $sheet2->fromArray([$recipe->id, $recipe->title, $recipe->rating_count, $recipe->rating_avg], null, "A{$row}");
                    $row++;
                }

                $writer = new Xlsx($spreadsheet);

                return response()->streamDownload(function () use ($writer) {
                    $writer->save('php://output');
                }, "{$filename}.xlsx", [
                    'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'Content-Disposition' => "attachment; filename=\"{$filename}.xlsx\"",
                ]);
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.reportes.pdf', compact('usuariosPorMes', 'recetasMasVistas'));

            return $pdf->download("{$filename}.pdf");
        }

        return back()->with('error', 'Formato de exportación no permitido. Usa CSV, Excel o PDF.');
    }
}
