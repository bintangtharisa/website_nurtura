<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;
use MongoDB\BSON\UTCDateTime;
use MongoDB\Client;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminExportController extends Controller
{
    private function db()
    {
        $client = new Client(config('database.connections.mongodb.dsn'));
        return $client->selectDatabase(config('database.connections.mongodb.database'));
    }

    public function screeningHistory(Request $request): StreamedResponse
    {
        $this->validateExportRequest($request);

        $db = $this->db();
        $usersCollection = $db->selectCollection('users');
        $predictionCollection = $db->selectCollection('prediction_results');

        [$motherIds, $anonymousMap] = $this->resolveMothers($usersCollection, $request->input('anonymous_id'));
        $filter = $this->buildPredictionFilter($request, $motherIds);
        $filename = $this->screeningFilename($request);

        return response()->streamDownload(function () use ($predictionCollection, $filter, $anonymousMap) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Kode Ibu Anonim',
                'Tanggal Skrining',
                'Hasil Prediksi',
                'Kategori Risiko',
                'Cluster',
            ]);

            $cursor = $predictionCollection->find($filter, [
                'sort' => ['created_at' => -1],
            ]);

            foreach ($cursor as $item) {
                $result = isset($item['result']) ? (string) $item['result'] : '';
                $createdAt = $this->createdAt($item);
                $motherKey = isset($item['mother_id']) ? (string) $item['mother_id'] : '';

                fputcsv($handle, [
                    $anonymousMap[$motherKey] ?? $this->fallbackAnonymousId($item),
                    $createdAt ? $createdAt->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s') : '',
                    $result,
                    $this->riskCategory($result),
                    $item['cluster'] ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function predictionTrends(Request $request): StreamedResponse
    {
        $this->validateExportRequest($request);

        $db = $this->db();
        $usersCollection = $db->selectCollection('users');
        $predictionCollection = $db->selectCollection('prediction_results');

        [$motherIds] = $this->resolveMothers($usersCollection, $request->input('anonymous_id'));
        $filter = $this->buildPredictionFilter($request, $motherIds);
        $quarters = $this->emptyQuarters();

        $cursor = $predictionCollection->find($filter);
        foreach ($cursor as $item) {
            $createdAt = $this->createdAt($item);
            if (!$createdAt) {
                continue;
            }

            $quarter = (int) ceil(((int) $createdAt->format('n')) / 3);
            $result = isset($item['result']) ? (string) $item['result'] : '';
            $category = $this->riskKey($result);

            $quarters[$quarter][$category]++;
            $quarters[$quarter]['total']++;
        }

        return response()->streamDownload(function () use ($quarters) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'Kuartal',
                'Beresiko',
                'Tidak Beresiko',
                'Tidak Diketahui',
                'Total Skrining',
            ]);

            foreach ($quarters as $quarter) {
                fputcsv($handle, [
                    $quarter['label'],
                    $quarter['high'],
                    $quarter['low'],
                    $quarter['unknown'],
                    $quarter['total'],
                ]);
            }

            fclose($handle);
        }, 'analitik-tren-' . now()->format('Ymd-His') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function researchDataset(Request $request): StreamedResponse
    {
        $this->validateExportRequest($request);

        $db = $this->db();
        $usersCollection = $db->selectCollection('users');
        $predictionCollection = $db->selectCollection('prediction_results');
        $healthCollection = $db->selectCollection('health_records');

        [$motherIds, $anonymousMap] = $this->resolveMothers($usersCollection, $request->input('anonymous_id'));
        $filter = $this->buildPredictionFilter($request, $motherIds);
        $rows = [];
        $answerKeys = [];

        $cursor = $predictionCollection->find($filter, [
            'sort' => ['created_at' => -1],
        ]);

        foreach ($cursor as $item) {
            $healthRecord = $this->healthRecord($healthCollection, $item);
            $answers = $this->answerFields($healthRecord);

            foreach (array_keys($answers) as $key) {
                $answerKeys[$key] = true;
            }

            $rows[] = [$item, $answers];
        }

        $answerHeaders = array_keys($answerKeys);
        sort($answerHeaders);

        return response()->streamDownload(function () use ($rows, $answerHeaders, $anonymousMap) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, array_merge([
                'Kode Ibu Anonim',
                'Tanggal Skrining',
                'Hasil Prediksi',
                'Kategori Risiko',
                'Cluster',
            ], $answerHeaders));

            foreach ($rows as [$item, $answers]) {
                $result = isset($item['result']) ? (string) $item['result'] : '';
                $createdAt = $this->createdAt($item);
                $motherKey = isset($item['mother_id']) ? (string) $item['mother_id'] : '';
                $row = [
                    $anonymousMap[$motherKey] ?? $this->fallbackAnonymousId($item),
                    $createdAt ? $createdAt->setTimezone(new \DateTimeZone(config('app.timezone')))->format('Y-m-d H:i:s') : '',
                    $result,
                    $this->riskCategory($result),
                    $item['cluster'] ?? '',
                ];

                foreach ($answerHeaders as $key) {
                    $row[] = $answers[$key] ?? '';
                }

                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 'dataset-penelitian-' . now()->format('Ymd-His') . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function summaryPdf(Request $request): StreamedResponse
    {
        $this->validateExportRequest($request);

        $db = $this->db();
        $usersCollection = $db->selectCollection('users');
        $predictionCollection = $db->selectCollection('prediction_results');

        [$motherIds] = $this->resolveMothers($usersCollection, $request->input('anonymous_id'));
        $filter = $this->buildPredictionFilter($request, $motherIds);
        $quarters = $this->emptyQuarters();
        $summary = [
            'total' => 0,
            'high' => 0,
            'low' => 0,
            'unknown' => 0,
        ];

        $cursor = $predictionCollection->find($filter);
        foreach ($cursor as $item) {
            $createdAt = $this->createdAt($item);
            $result = isset($item['result']) ? (string) $item['result'] : '';
            $category = $this->riskKey($result);

            $summary[$category]++;
            $summary['total']++;

            if ($createdAt) {
                $quarter = (int) ceil(((int) $createdAt->format('n')) / 3);
                $quarters[$quarter][$category]++;
                $quarters[$quarter]['total']++;
            }
        }

        $pdf = $this->summaryReportPdf([
            'generated_at' => now()->format('Y-m-d H:i:s'),
            'anonymous_id' => $request->filled('anonymous_id') ? strtoupper($request->input('anonymous_id')) : 'Semua ibu anonim',
            'period' => ($request->input('date_from') ?: '-') . ' sampai ' . ($request->input('date_to') ?: '-'),
            'result_filter' => $this->resultFilterLabel($request->input('result', 'all')),
            'summary' => $summary,
            'quarters' => $quarters,
            'insights' => $this->summaryInsights($summary, $quarters),
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf;
        }, 'ringkasan-export-' . now()->format('Ymd-His') . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    private function validateExportRequest(Request $request): void
    {
        $request->validate([
            'anonymous_id' => ['nullable', 'string', 'max:80'],
            'result' => ['nullable', 'in:all,high,low,unknown'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);
    }

    private function resolveMothers($usersCollection, ?string $anonymousId): array
    {
        $filter = ['role' => 'mother'];

        if ($anonymousId !== null && trim($anonymousId) !== '') {
            $filter['anonymous_id'] = new Regex('^' . preg_quote(strtoupper(trim($anonymousId)), '/') . '$', 'i');
        }

        $cursor = $usersCollection->find($filter, [
            'projection' => ['_id' => 1, 'anonymous_id' => 1],
        ]);

        $motherIds = [];
        $anonymousMap = [];

        foreach ($cursor as $mother) {
            if (empty($mother['_id'])) {
                continue;
            }

            $motherIds[] = $mother['_id'];
            $anonymousMap[(string) $mother['_id']] = strtoupper((string) ($mother['anonymous_id'] ?? ''));
        }

        return [$motherIds, $anonymousMap];
    }

    private function buildPredictionFilter(Request $request, array $motherIds): array
    {
        $filter = ['mother_id' => ['$in' => $motherIds]];
        $createdAtFilter = [];

        if ($request->filled('date_from')) {
            $createdAtFilter['$gte'] = new UTCDateTime(
                Carbon::parse($request->input('date_from'))->startOfDay()->timestamp * 1000
            );
        }

        if ($request->filled('date_to')) {
            $createdAtFilter['$lte'] = new UTCDateTime(
                Carbon::parse($request->input('date_to'))->endOfDay()->timestamp * 1000
            );
        }

        if (!empty($createdAtFilter)) {
            $filter['created_at'] = $createdAtFilter;
        }

        $result = $request->input('result', 'all');
        if ($result === 'high') {
            $filter['$and'] = [
                ['result' => new Regex('(ya|beresiko|depresi)', 'i')],
                ['result' => ['$not' => new Regex('tidak', 'i')]],
            ];
        } elseif ($result === 'low') {
            $filter['result'] = new Regex('tidak', 'i');
        } elseif ($result === 'unknown') {
            $filter['$nor'] = [
                ['result' => new Regex('(ya|beresiko|depresi|tidak)', 'i')],
            ];
        }

        return $filter;
    }

    private function createdAt($item): ?\DateTimeInterface
    {
        if (isset($item['created_at']) && $item['created_at'] instanceof UTCDateTime) {
            return $item['created_at']->toDateTime();
        }

        if (isset($item['_id']) && method_exists($item['_id'], 'getTimestamp')) {
            $timestamp = $item['_id']->getTimestamp();

            return $timestamp instanceof \DateTimeInterface
                ? $timestamp
                : (new \DateTimeImmutable())->setTimestamp((int) $timestamp);
        }

        return null;
    }

    private function riskCategory(?string $result): string
    {
        return match ($this->riskKey($result)) {
            'low' => 'Tidak Beresiko',
            'high' => 'Beresiko',
            default => 'Tidak Diketahui',
        };
    }

    private function riskKey(?string $result): string
    {
        $normalized = strtolower((string) $result);

        if (str_contains($normalized, 'tidak')) {
            return 'low';
        }

        if (str_contains($normalized, 'ya') || str_contains($normalized, 'beresiko') || str_contains($normalized, 'depresi')) {
            return 'high';
        }

        return 'unknown';
    }

    private function emptyQuarters(): array
    {
        return [
            1 => ['label' => 'Q1', 'high' => 0, 'low' => 0, 'unknown' => 0, 'total' => 0],
            2 => ['label' => 'Q2', 'high' => 0, 'low' => 0, 'unknown' => 0, 'total' => 0],
            3 => ['label' => 'Q3', 'high' => 0, 'low' => 0, 'unknown' => 0, 'total' => 0],
            4 => ['label' => 'Q4', 'high' => 0, 'low' => 0, 'unknown' => 0, 'total' => 0],
        ];
    }

    private function healthRecord($healthCollection, $prediction)
    {
        if (empty($prediction['health_record_id'])) {
            return null;
        }

        try {
            $healthRecordId = $prediction['health_record_id'] instanceof ObjectId
                ? $prediction['health_record_id']
                : new ObjectId((string) $prediction['health_record_id']);

            return $healthCollection->findOne(['_id' => $healthRecordId]);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function answerFields($healthRecord): array
    {
        if (!$healthRecord) {
            return [];
        }

        $excluded = ['_id', 'mother_id', 'created_at', 'updated_at'];
        $answers = [];

        foreach ($healthRecord as $key => $value) {
            if (in_array((string) $key, $excluded, true)) {
                continue;
            }

            $answers[(string) $key] = is_scalar($value) || $value === null
                ? (string) $value
                : json_encode($value);
        }

        return $answers;
    }

    private function resultFilterLabel(string $result): string
    {
        return match ($result) {
            'high' => 'Beresiko Depresi',
            'low' => 'Tidak Beresiko Depresi',
            'unknown' => 'Tidak Diketahui',
            default => 'Semua hasil',
        };
    }

    private function summaryInsights(array $summary, array $quarters): array
    {
        $highestQuarter = array_reduce($quarters, function ($carry, $quarter) {
            if ($carry === null || $quarter['total'] > $carry['total']) {
                return $quarter;
            }

            return $carry;
        });

        $riskRate = $summary['total'] > 0
            ? round(($summary['high'] / $summary['total']) * 100, 1)
            : 0;

        return [
            'Persentase beresiko: ' . $riskRate . '% dari total skrining.',
            'Kuartal tertinggi: ' . (($highestQuarter['label'] ?? '-') . ' dengan ' . ($highestQuarter['total'] ?? 0) . ' skrining.'),
            'Laporan ini hanya menampilkan data anonim tanpa email, username, atau relasi keluarga.',
        ];
    }

    private function summaryReportPdf(array $report): string
    {
        $content = '';

        $content .= $this->pdfRect(0, 760, 595, 82, [0.64, 0.69, 0.54]);
        $content .= $this->pdfText('Nurtura Family', 42, 806, 18, [1, 1, 1], 'F2');
        $content .= $this->pdfText('Laporan Ringkasan Skrining', 42, 782, 22, [1, 1, 1], 'F2');
        $content .= $this->pdfText('Dibuat: ' . $report['generated_at'], 430, 808, 9, [1, 1, 1]);
        $content .= $this->pdfText('Data anonim untuk kebutuhan monitoring admin', 42, 765, 10, [0.96, 0.98, 0.94]);

        $content .= $this->pdfText('Filter Laporan', 42, 728, 13, [0.10, 0.10, 0.10], 'F2');
        $content .= $this->pdfText('Kode ibu anonim: ' . $report['anonymous_id'], 42, 710, 10, [0.32, 0.32, 0.32]);
        $content .= $this->pdfText('Periode: ' . $report['period'], 42, 694, 10, [0.32, 0.32, 0.32]);
        $content .= $this->pdfText('Hasil prediksi: ' . $report['result_filter'], 42, 678, 10, [0.32, 0.32, 0.32]);

        $cards = [
            ['Total Skrining', $report['summary']['total'], [0.64, 0.69, 0.54]],
            ['Beresiko', $report['summary']['high'], [0.78, 0.16, 0.16]],
            ['Tidak Beresiko', $report['summary']['low'], [0.18, 0.49, 0.20]],
            ['Tidak Diketahui', $report['summary']['unknown'], [0.63, 0.44, 0.00]],
        ];
        $cardPositions = [[42, 606], [306, 606], [42, 520], [306, 520]];

        foreach ($cards as $index => $card) {
            [$x, $y] = $cardPositions[$index];
            $content .= $this->pdfRect($x, $y, 247, 68, [0.98, 0.98, 0.97]);
            $content .= $this->pdfStrokeRect($x, $y, 247, 68, [0.90, 0.89, 0.86]);
            $content .= $this->pdfRect($x, $y, 6, 68, $card[2]);
            $content .= $this->pdfText($card[0], $x + 18, $y + 43, 10, [0.42, 0.45, 0.42], 'F2');
            $content .= $this->pdfText((string) $card[1], $x + 18, $y + 17, 22, [0.10, 0.10, 0.10], 'F2');
        }

        $content .= $this->pdfText('Insight Singkat', 42, 478, 13, [0.10, 0.10, 0.10], 'F2');
        $insightY = 459;
        foreach ($report['insights'] as $insight) {
            $content .= $this->pdfText('- ' . $insight, 42, $insightY, 10, [0.32, 0.32, 0.32]);
            $insightY -= 15;
        }

        $tableX = 42;
        $tableY = 354;
        $rowH = 28;
        $colWidths = [82, 92, 116, 116, 92];
        $headers = ['Kuartal', 'Beresiko', 'Tidak Beresiko', 'Tidak Diketahui', 'Total'];

        $content .= $this->pdfText('Analitik Tren Per Kuartal', 42, 386, 13, [0.10, 0.10, 0.10], 'F2');
        $content .= $this->pdfRect($tableX, $tableY, array_sum($colWidths), $rowH, [0.64, 0.69, 0.54]);
        $cursorX = $tableX;
        foreach ($headers as $index => $header) {
            $content .= $this->pdfText($header, $cursorX + 8, $tableY + 10, 9, [1, 1, 1], 'F2');
            $cursorX += $colWidths[$index];
        }

        $rowY = $tableY - $rowH;
        foreach ($report['quarters'] as $quarter) {
            $content .= $this->pdfRect($tableX, $rowY, array_sum($colWidths), $rowH, [1, 1, 1]);
            $content .= $this->pdfStrokeRect($tableX, $rowY, array_sum($colWidths), $rowH, [0.90, 0.89, 0.86]);

            $values = [$quarter['label'], $quarter['high'], $quarter['low'], $quarter['unknown'], $quarter['total']];
            $cursorX = $tableX;
            foreach ($values as $index => $value) {
                $content .= $this->pdfText((string) $value, $cursorX + 8, $rowY + 10, 9, [0.23, 0.23, 0.23]);
                $cursorX += $colWidths[$index];
            }

            $rowY -= $rowH;
        }

        $content .= $this->pdfLine(42, 70, 553, 70, [0.90, 0.89, 0.86]);
        $content .= $this->pdfText('Nurtura Family - Data bersifat anonim dan hanya untuk kebutuhan administratif.', 42, 50, 9, [0.45, 0.45, 0.45]);
        $content .= $this->pdfText('Halaman 1', 505, 50, 9, [0.45, 0.45, 0.45]);

        return $this->buildPdf($content);
    }

    private function buildPdf(string $content): string
    {
        $objects = [];
        $objects[] = "<< /Type /Catalog /Pages 2 0 R >>";
        $objects[] = "<< /Type /Pages /Kids [3 0 R] /Count 1 >>";
        $objects[] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /ProcSet [/PDF /Text] /Font << /F1 4 0 R /F2 5 0 R >> >> /Contents 6 0 R >>";
        $objects[] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";
        $objects[] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>";
        $objects[] = "<< /Length " . strlen($content) . " >>\r\nstream\r\n" . $content . "\r\nendstream";

        $pdf = "%PDF-1.4\r\n%\xE2\xE3\xCF\xD3\r\n";
        $offsets = [0];

        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1) . " 0 obj\r\n" . $object . "\r\nendobj\r\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\r\n0 " . (count($objects) + 1) . "\r\n";
        $pdf .= "0000000000 65535 f \r\n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT) . " 00000 n \r\n";
        }

        $pdf .= "trailer\r\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\r\n";
        $pdf .= "startxref\r\n" . $xrefOffset . "\r\n%%EOF\r\n";

        return $pdf;
    }

    private function pdfText(string $text, float $x, float $y, float $size, array $color = [0, 0, 0], string $font = 'F1'): string
    {
        return sprintf(
            "q %.3F %.3F %.3F rg BT /%s %.1F Tf %.1F %.1F Td (%s) Tj ET Q\n",
            $color[0],
            $color[1],
            $color[2],
            $font,
            $size,
            $x,
            $y,
            $this->escapePdfText($text)
        );
    }

    private function pdfRect(float $x, float $y, float $width, float $height, array $color): string
    {
        return sprintf(
            "q %.3F %.3F %.3F rg %.1F %.1F %.1F %.1F re f Q\n",
            $color[0],
            $color[1],
            $color[2],
            $x,
            $y,
            $width,
            $height
        );
    }

    private function pdfStrokeRect(float $x, float $y, float $width, float $height, array $color): string
    {
        return sprintf(
            "q %.3F %.3F %.3F RG %.1F %.1F %.1F %.1F re S Q\n",
            $color[0],
            $color[1],
            $color[2],
            $x,
            $y,
            $width,
            $height
        );
    }

    private function pdfLine(float $x1, float $y1, float $x2, float $y2, array $color): string
    {
        return sprintf(
            "q %.3F %.3F %.3F RG %.1F %.1F m %.1F %.1F l S Q\n",
            $color[0],
            $color[1],
            $color[2],
            $x1,
            $y1,
            $x2,
            $y2
        );
    }

    private function escapePdfText(string $text): string
    {
        $text = str_replace(['\\', '(', ')'], ['\\\\', '\(', '\)'], $text);
        return preg_replace('/[^\x20-\x7E]/', '?', $text);
    }

    private function fallbackAnonymousId($item): string
    {
        if (!empty($item['anonymous_id'])) {
            return strtoupper((string) $item['anonymous_id']);
        }

        if (!empty($item['mother_id'])) {
            return 'ANON-' . strtoupper(substr(md5((string) $item['mother_id']), 0, 8));
        }

        return !empty($item['_id'])
            ? 'ANON-' . strtoupper(substr(md5((string) $item['_id']), 0, 8))
            : 'ANON-UNKNOWN';
    }

    private function screeningFilename(Request $request): string
    {
        $parts = ['riwayat-skrining'];

        if ($request->filled('anonymous_id')) {
            $parts[] = strtolower(preg_replace('/[^a-zA-Z0-9-]+/', '-', trim($request->input('anonymous_id'))));
        }

        $parts[] = now()->format('Ymd-His');

        return implode('-', array_filter($parts)) . '.csv';
    }
}
