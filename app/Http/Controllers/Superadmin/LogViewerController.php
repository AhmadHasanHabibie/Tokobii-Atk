<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LogViewerController extends Controller
{
    /**
     * Display the Laravel Log Viewer with filtering & search.
     */
    public function index(Request $request): View
    {
        $logPath = storage_path('logs/laravel.log');
        $logExists = File::exists($logPath);
        $fileSize = $logExists ? File::size($logPath) : 0;
        $fileSizeFormatted = $this->formatBytes($fileSize);

        $levelFilter = strtoupper((string) $request->input('level', 'ALL'));
        $searchQuery = (string) $request->input('q', '');

        $logs = [];

        if ($logExists && $fileSize > 0) {
            $logs = $this->parseLogFile($logPath, $levelFilter, $searchQuery);
        }

        $levelsCount = [
            'ALL' => count($logs),
            'ERROR' => 0,
            'CRITICAL' => 0,
            'WARNING' => 0,
            'INFO' => 0,
            'DEBUG' => 0,
        ];

        foreach ($logs as $item) {
            $lvl = $item['level'];
            if (isset($levelsCount[$lvl])) {
                $levelsCount[$lvl]++;
            }
        }

        return view('superadmin.logs.laravel', compact(
            'logs',
            'logExists',
            'fileSizeFormatted',
            'levelFilter',
            'searchQuery',
            'levelsCount'
        ));
    }

    /**
     * Clear / Truncate laravel.log file.
     */
    public function clear(Request $request): RedirectResponse
    {
        $logPath = storage_path('logs/laravel.log');

        if (File::exists($logPath)) {
            File::put($logPath, '');
        }

        SecurityLog::record(
            eventType: 'laravel_log_cleared',
            severity: 'high',
            endpoint: $request->path(),
            method: 'POST',
            payload: ['action' => 'cleared_storage_logs_laravel_log'],
            responseStatus: 200
        );

        return redirect()->route('superadmin.logs.laravel')->with('success', 'File storage/logs/laravel.log berhasil dibersihkan.');
    }

    /**
     * Download the raw log file.
     */
    public function download(): BinaryFileResponse|RedirectResponse
    {
        $logPath = storage_path('logs/laravel.log');

        if (!File::exists($logPath)) {
            return redirect()->back()->with('error', 'File log tidak ditemukan.');
        }

        return response()->download($logPath, 'tokobii-laravel-' . date('Y-m-d_His') . '.log');
    }

    /**
     * Parse and structure Laravel log entries from file.
     */
    protected function parseLogFile(string $filePath, string $levelFilter = 'ALL', string $searchQuery = ''): array
    {
        // Read the last 2MB of file to prevent memory exhaustion on giant log files
        $maxBytes = 2 * 1024 * 1024;
        $fileSize = filesize($filePath);
        $handle = fopen($filePath, 'r');

        if (!$handle) {
            return [];
        }

        if ($fileSize > $maxBytes) {
            fseek($handle, $fileSize - $maxBytes);
            // Read until first newline to align
            fgets($handle);
        }

        $content = fread($handle, $maxBytes);
        fclose($handle);

        $pattern = '/\[(\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:[+-]\d{2}:\d{2})?)\]\s+([a-zA-Z0-9_]+)\.([a-zA-Z]+):\s+(.*?)(?=(?:\[\d{4}-\d{2}-\d{2}[T ]\d{2}:\d{2}:\d{2})|\z)/s';

        preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);

        $entries = [];

        foreach ($matches as $match) {
            $timestamp = $match[1];
            $environment = $match[2];
            $level = strtoupper($match[3]);
            $body = trim($match[4]);

            // Split message and stack trace
            $bodyLines = explode("\n", $body);
            $message = $bodyLines[0] ?? '';
            $stackTrace = count($bodyLines) > 1 ? implode("\n", array_slice($bodyLines, 1)) : '';

            // Apply Level Filter
            if ($levelFilter !== 'ALL' && $level !== $levelFilter) {
                continue;
            }

            // Apply Search Query
            if (!empty($searchQuery)) {
                if (!stripos($message, $searchQuery) && !stripos($stackTrace, $searchQuery)) {
                    continue;
                }
            }

            $entries[] = [
                'timestamp' => $timestamp,
                'environment' => $environment,
                'level' => $level,
                'message' => $message,
                'stack_trace' => $stackTrace,
            ];
        }

        // Return latest logs first
        return array_reverse($entries);
    }

    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
