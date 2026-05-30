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

    public function summaryPdf(Request $request)
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

        $lines = [
            'Nurtura Family - Ringkasan Export',
            'Dibuat: ' . now()->format('Y-m-d H:i:s'),
            'Kode ibu anonim: ' . ($request->filled('anonymous_id') ? strtoupper($request->input('anonymous_id')) : 'Semua'),
            'Periode: ' . ($request->input('date_from') ?: '-') . ' sampai ' . ($request->input('date_to') ?: '-'),
            'Filter hasil: ' . $this->resultFilterLabel($request->input('result', 'all')),
            '',
            'Total skrining: ' . $summary['total'],
            'Beresiko: ' . $summary['high'],
            'Tidak beresiko: ' . $summary['low'],
            'Tidak diketahui: ' . $summary['unknown'],
            '',
            'Analitik Tren Per Kuartal',
        ];

        foreach ($quarters as $quarter) {
            $lines[] = sprintf(
                '%s | Beresiko: %d | Tidak Beresiko: %d | Tidak Diketahui: %d | Total: %d',
                $quarter['label'],
                $quarter['high'],
                $quarter['low'],
                $quarter['unknown'],
                $quarter['total']
            );
        }

        $pdf = $this->simplePdf($lines);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="ringkasan-export-' . now()->format('Ymd-His') . '.pdf"',
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

    private function simplePdf(array $lines): string
    {
        $content = "BT\n/F1 11 Tf\n50 790 Td\n14 TL\n";

        foreach ($lines as $line) {
            $content .= '(' . $this->escapePdfText($line) . ") Tj\nT*\n";
        }

        $content .= "ET";
        $objects = [];
        $objects[] = "<< /Type /Catalog /Pages 2 0 R >>";
        $objects[] = "<< /Type /Pages /Kids [3 0 R] /Count 1 >>";
        $objects[] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>";
        $objects[] = "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>";
        $objects[] = "<< /Length " . strlen($content) . " >>\nstream\n" . $content . "\nendstream";

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $pdf .= ($index + 1) . " 0 obj\n" . $object . "\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= str_pad((string) $offsets[$i], 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }

        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n" . $xrefOffset . "\n%%EOF";

        return $pdf;
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
