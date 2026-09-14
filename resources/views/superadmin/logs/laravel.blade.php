@extends('layouts.superadmin.app')

@section('title', 'Laravel Log Viewer - Tokobii')

@section('content')
<div class="container-fluid px-0">

    {{-- Page Header Card --}}
    <div class="tokobii-header-card mb-4 animate-fade-in-up">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="tokobii-badge tokobii-badge-info">System Framework Logs</span>
                    <span class="text-slate-400 small">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
                <h1 class="h3 fw-bold text-slate-900 mb-1">Laravel Log Stream Viewer</h1>
                <p class="text-slate-500 mb-0 small">
                    Inspeksi langsung berkas <code>storage/logs/laravel.log</code> dengan filter otomatis level error & critical.
                    @if($logExists)
                        <span class="text-blue-600 fw-semibold ms-1 font-monospace">(Ukuran: {{ $fileSizeFormatted }})</span>
                    @endif
                </p>
            </div>

            <div class="d-flex align-items-center gap-2">
                @if($logExists)
                    <a href="{{ route('superadmin.logs.laravel.download') }}" class="btn btn-tokobii-secondary btn-tokobii-sm d-flex align-items-center gap-1.5">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download Log</span>
                    </a>
                    <form action="{{ route('superadmin.logs.laravel.clear') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mengosongkan isi file storage/logs/laravel.log?');" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1.5 fw-semibold" style="border-radius: 8px;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            <span>Clear Log</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Filter Level Tabs & Search --}}
    <div class="tokobii-card p-4 mb-4 animate-fade-in-up">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            {{-- Level Filters --}}
            <div class="d-flex flex-wrap gap-1.5">
                <a href="{{ route('superadmin.logs.laravel', ['level' => 'ALL', 'q' => $searchQuery]) }}"
                   class="btn btn-sm fw-semibold {{ $levelFilter === 'ALL' ? 'btn-primary' : 'btn-outline-secondary' }}" style="border-radius: 8px; font-size: 0.75rem;">
                    ALL ({{ $levelsCount['ALL'] }})
                </a>
                <a href="{{ route('superadmin.logs.laravel', ['level' => 'CRITICAL', 'q' => $searchQuery]) }}"
                   class="btn btn-sm fw-semibold {{ $levelFilter === 'CRITICAL' ? 'btn-danger' : 'btn-outline-danger' }}" style="border-radius: 8px; font-size: 0.75rem;">
                    CRITICAL ({{ $levelsCount['CRITICAL'] }})
                </a>
                <a href="{{ route('superadmin.logs.laravel', ['level' => 'ERROR', 'q' => $searchQuery]) }}"
                   class="btn btn-sm fw-semibold {{ $levelFilter === 'ERROR' ? 'btn-danger' : 'btn-outline-danger' }}" style="border-radius: 8px; font-size: 0.75rem;">
                    ERROR ({{ $levelsCount['ERROR'] }})
                </a>
                <a href="{{ route('superadmin.logs.laravel', ['level' => 'WARNING', 'q' => $searchQuery]) }}"
                   class="btn btn-sm fw-semibold {{ $levelFilter === 'WARNING' ? 'btn-warning text-white' : 'btn-outline-warning' }}" style="border-radius: 8px; font-size: 0.75rem;">
                    WARNING ({{ $levelsCount['WARNING'] }})
                </a>
                <a href="{{ route('superadmin.logs.laravel', ['level' => 'INFO', 'q' => $searchQuery]) }}"
                   class="btn btn-sm fw-semibold {{ $levelFilter === 'INFO' ? 'btn-info text-white' : 'btn-outline-info' }}" style="border-radius: 8px; font-size: 0.75rem;">
                    INFO ({{ $levelsCount['INFO'] }})
                </a>
            </div>

            {{-- Search Input --}}
            <form action="{{ route('superadmin.logs.laravel') }}" method="GET" class="d-flex gap-2" style="max-width: 320px;">
                <input type="hidden" name="level" value="{{ $levelFilter }}">
                <input type="text" name="q" value="{{ $searchQuery }}" class="form-control form-control-sm" placeholder="Cari baris pesan/error...">
                <button type="submit" class="btn btn-tokobii-primary btn-tokobii-sm">
                    Cari
                </button>
            </form>
        </div>
    </div>

    {{-- Log Entries Stream --}}
    <div class="d-flex flex-column gap-3 animate-fade-in-up">
        @forelse($logs as $index => $item)
            <div class="tokobii-card p-4 position-relative overflow-hidden"
                 style="border-left: 4px solid {{ $item['level'] === 'CRITICAL' || $item['level'] === 'ERROR' ? '#ef4444' : ($item['level'] === 'WARNING' ? '#f59e0b' : '#3b82f6') }} !important;">
                
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        @if($item['level'] === 'CRITICAL')
                            <span class="tokobii-badge tokobii-badge-danger">CRITICAL</span>
                        @elseif($item['level'] === 'ERROR')
                            <span class="tokobii-badge tokobii-badge-danger">ERROR</span>
                        @elseif($item['level'] === 'WARNING')
                            <span class="tokobii-badge tokobii-badge-warning">WARNING</span>
                        @else
                            <span class="tokobii-badge tokobii-badge-info">{{ $item['level'] }}</span>
                        @endif

                        <span class="font-monospace small fw-bold text-slate-400">[{{ $item['environment'] }}]</span>
                        <span class="font-monospace small text-slate-500">{{ $item['timestamp'] }}</span>
                    </div>

                    @if(!empty($item['stack_trace']))
                        <button class="btn btn-sm btn-outline-secondary font-monospace" type="button" data-bs-toggle="collapse" data-bs-target="#stackTrace{{ $index }}" style="font-size: 0.72rem; padding: 0.2rem 0.6rem; border-radius: 6px;">
                            Stack Trace &darr;
                        </button>
                    @endif
                </div>

                <div class="font-monospace small text-slate-800 fw-medium py-1" style="word-break: break-word;">
                    {{ $item['message'] }}
                </div>

                @if(!empty($item['stack_trace']))
                    <div class="collapse mt-3" id="stackTrace{{ $index }}">
                        <pre class="p-3 rounded-3 font-monospace small overflow-auto text-slate-700" style="background: #f8fafc; border: 1px solid #e2e8f0; max-height: 350px;">{{ $item['stack_trace'] }}</pre>
                    </div>
                @endif
            </div>
        @empty
            <div class="tokobii-card p-5 text-center text-slate-400 small">
                Tidak ada entri log yang ditemukan sesuai filter yang dipilih.
            </div>
        @endforelse
    </div>

</div>
@endsection
