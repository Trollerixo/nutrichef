<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
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

                $dataSeriesLabels = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Usuarios!$B$1', null, 1)];
                $xAxisTickValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Usuarios!$A$2:$A$' . (1 + count($usuariosPorMes)), null, count($usuariosPorMes))];
                $dataSeriesValues = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Usuarios!$B$2:$B$' . (1 + count($usuariosPorMes)), null, count($usuariosPorMes))];

                $chartUsers = new Chart(
                    'chartUsuarios',
                    new Title('Usuarios por mes'),
                    new Legend(Legend::POSITION_BOTTOM, null, false),
                    new PlotArea(null, [new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_CLUSTERED, [0], $dataSeriesLabels, $xAxisTickValues, $dataSeriesValues)]),
                );
                $chartUsers->setTopLeftPosition('D1');
                $chartUsers->setBottomRightPosition('J15');
                $sheet->addChart($chartUsers);

                $spreadsheet->createSheet();
                $sheet2 = $spreadsheet->getSheet(1);
                $sheet2->setTitle('Recetas');
                $sheet2->fromArray(['Título', 'Reseñas', 'Puntuación'], null, 'A1');
                $row = 2;
                foreach ($recetasMasVistas as $recipe) {
                    $sheet2->fromArray([$recipe->title, $recipe->rating_count, $recipe->rating_avg], null, "A{$row}");
                    $row++;
                }

                $dataSeriesLabels2 = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Recetas!$B$1', null, 1)];
                $xAxisTickValues2 = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Recetas!$A$2:$A$' . (1 + count($recetasMasVistas)), null, count($recetasMasVistas))];
                $dataSeriesValues2 = [new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Recetas!$B$2:$B$' . (1 + count($recetasMasVistas)), null, count($recetasMasVistas))];

                $chartRecetas = new Chart(
                    'chartRecetas',
                    new Title('Recetas más vistas'),
                    new Legend(Legend::POSITION_BOTTOM, null, false),
                    new PlotArea(null, [new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_CLUSTERED, [0], $dataSeriesLabels2, $xAxisTickValues2, $dataSeriesValues2)]),
                );
                $chartRecetas->setTopLeftPosition('D1');
                $chartRecetas->setBottomRightPosition('J15');
                $sheet2->addChart($chartRecetas);

                $writer = new Xlsx($spreadsheet);
                $writer->setIncludeCharts(true);

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
