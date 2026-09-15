<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $periodTitle }} - Tokobii</title>
    <style>
        @page {
            margin: 25px 30px 35px 30px;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.4;
        }

        .header-table {
            width: 100%;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header-logo-title {
            font-size: 20pt;
            font-weight: bold;
            color: #1e40af;
            letter-spacing: -0.5px;
            line-height: 1.1;
        }

        .header-subtitle {
            font-size: 9pt;
            color: #64748b;
            margin-top: 3px;
        }

        .report-title-box {
            text-align: right;
        }

        .report-title {
            font-size: 13pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .report-badge {
            display: inline-block;
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 8.5pt;
            font-weight: bold;
            margin-top: 4px;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 18px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 9pt;
        }

        .meta-label {
            color: #64748b;
            width: 18%;
        }

        .meta-val {
            color: #0f172a;
            font-weight: 600;
            width: 32%;
        }

        /* KPI Summary Grid Table */
        .kpi-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 8px 0;
        }

        .kpi-card {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px 12px;
            text-align: center;
        }

        .kpi-title {
            font-size: 7.5pt;
            color: #64748b;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .kpi-value {
            font-size: 13pt;
            font-weight: bold;
            color: #1e3a8a;
            margin-top: 3px;
            font-family: 'Courier New', Courier, monospace;
        }

        .kpi-sub {
            font-size: 7.5pt;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* Section Title */
        .section-header {
            font-size: 10.5pt;
            font-weight: bold;
            color: #0f172a;
            border-bottom: 1.5px solid #e2e8f0;
            padding-bottom: 5px;
            margin-top: 15px;
            margin-bottom: 10px;
        }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 18px;
        }

        .data-table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: bold;
            text-align: left;
            padding: 7px 8px;
            border: 1px solid #cbd5e1;
        }

        .data-table td {
            padding: 6px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .data-table tr:nth-child(even) {
            background-color: #f8fafc;
        }

        .text-center { text-align: center !important; }
        .text-right { text-align: right !important; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
        .fw-bold { font-weight: bold; }

        .badge-qris {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7.5pt;
            font-weight: bold;
        }

        .badge-cash {
            background-color: #dcfce7;
            color: #166534;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7.5pt;
            font-weight: bold;
        }

        /* Grand Total Box */
        .total-box-table {
            width: 100%;
            margin-bottom: 25px;
        }

        .total-box {
            width: 45%;
            margin-left: auto;
            border: 1.5px solid #2563eb;
            background-color: #eff6ff;
            border-radius: 6px;
            padding: 10px 14px;
        }

        /* Signatures */
        .sig-table {
            width: 100%;
            margin-top: 25px;
            page-break-inside: avoid;
        }

        .sig-box {
            width: 40%;
            text-align: center;
        }

        .footer-note {
            margin-top: 30px;
            border-top: 1px dashed #cbd5e1;
            padding-top: 8px;
            font-size: 7.5pt;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- Official Header --}}
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 55%; vertical-align: middle;">
                <div class="header-logo-title">TOKOBII</div>
                <div class="header-subtitle">
                    Penyedia Alat Tulis Kantor, Sekolah & Inventaris Terlengkap<br>
                    Sistem Resmi Tokobii Store — Panel Eksekutif Pemilik
                </div>
            </td>
            <td class="report-title-box" style="width: 45%; vertical-align: middle;">
                <div class="report-title">{{ $periodTitle }}</div>
                <div class="report-badge">{{ $periodLabel }}</div>
            </td>
        </tr>
    </table>

    {{-- Metadata Box --}}
    <table class="meta-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="meta-label">Jenis Laporan:</td>
            <td class="meta-val">Penjualan {{ ucfirst($period) }}</td>
            <td class="meta-label">Dicetak Pada:</td>
            <td class="meta-val">{{ now()->translatedFormat('d F Y, H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="meta-label">Rentang Tanggal:</td>
            <td class="meta-val">{{ $startDate->translatedFormat('d M Y') }} - {{ $endDate->translatedFormat('d M Y') }}</td>
            <td class="meta-label">Otoritas Cetak:</td>
            <td class="meta-val">{{ auth()->user()->name ?? 'Pemilik (Owner)' }} [Owner]</td>
        </tr>
    </table>

    {{-- Executive KPI Cards --}}
    <table class="kpi-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="kpi-card" style="width: 25%; border-top: 3px solid #2563eb;">
                <div class="kpi-title">Total Omset Penjualan</div>
                <div class="kpi-value" style="color: #2563eb;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                <div class="kpi-sub">Penerimaan Selesai</div>
            </td>
            <td class="kpi-card" style="width: 25%; border-top: 3px solid #16a34a;">
                <div class="kpi-title">Total Transaksi</div>
                <div class="kpi-value" style="color: #16a34a;">{{ number_format($totalOrders) }}</div>
                <div class="kpi-sub">Pesanan Lunas</div>
            </td>
            <td class="kpi-card" style="width: 25%; border-top: 3px solid #9333ea;">
                <div class="kpi-title">Unit Terjual</div>
                <div class="kpi-value" style="color: #9333ea;">{{ number_format($totalItemsSold) }}</div>
                <div class="kpi-sub">Total Produk ATK</div>
            </td>
            <td class="kpi-card" style="width: 25%; border-top: 3px solid #d97706;">
                <div class="kpi-title">Rata-Rata Belanja</div>
                <div class="kpi-value" style="color: #d97706;">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</div>
                <div class="kpi-sub">Per Pesanan (AOV)</div>
            </td>
        </tr>
    </table>

    {{-- Ringkasan Penerimaan Metode Pembayaran --}}
    <div class="section-header">1. Ringkasan Penerimaan Metode Pembayaran</div>
    <table class="data-table" cellpadding="0" cellspacing="0" style="margin-bottom: 14px;">
        <thead>
            <tr>
                <th>Metode Pembayaran</th>
                <th class="text-center" style="width: 20%;">Jumlah Transaksi</th>
                <th class="text-right" style="width: 30%;">Total Nominal</th>
                <th class="text-right" style="width: 20%;">Persentase</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>QRIS Tokobii (Transfer E-Wallet / M-Banking)</strong></td>
                <td class="text-center font-mono">{{ $qrisCount }} Transaksi</td>
                <td class="text-right font-mono fw-bold">Rp {{ number_format($qrisRevenue, 0, ',', '.') }}</td>
                <td class="text-right font-mono">{{ $totalRevenue > 0 ? number_format(($qrisRevenue / $totalRevenue) * 100, 1) : 0 }}%</td>
            </tr>
            <tr>
                <td><strong>Tunai di Kasir (Pembayaran Tunai Fisik)</strong></td>
                <td class="text-center font-mono">{{ $cashCount }} Transaksi</td>
                <td class="text-right font-mono fw-bold">Rp {{ number_format($cashRevenue, 0, ',', '.') }}</td>
                <td class="text-right font-mono">{{ $totalRevenue > 0 ? number_format(($cashRevenue / $totalRevenue) * 100, 1) : 0 }}%</td>
            </tr>
        </tbody>
    </table>

    {{-- Top Selling Products --}}
    @if($topProducts->count() > 0)
        <div class="section-header">2. Produk Terlaris Periode Ini</div>
        <table class="data-table" cellpadding="0" cellspacing="0" style="margin-bottom: 14px;">
            <thead>
                <tr>
                    <th style="width: 8%;" class="text-center">Rank</th>
                    <th>Nama Produk</th>
                    <th class="text-center" style="width: 20%;">Kuantitas Terjual</th>
                    <th class="text-right" style="width: 30%;">Total Kontribusi Omset</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topProducts as $top)
                    <tr>
                        <td class="text-center font-mono fw-bold">{{ $loop->iteration }}</td>
                        <td><strong>{{ $top->product_name }}</strong></td>
                        <td class="text-center font-mono">{{ number_format($top->total_qty) }} pcs</td>
                        <td class="text-right font-mono fw-bold">Rp {{ number_format($top->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Detailed Transaction Table --}}
    <div class="section-header">3. Rincian Seluruh Transaksi Penjualan</div>
    <table class="data-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 20%;">No. Invoice</th>
                <th style="width: 14%;">Tanggal & Jam</th>
                <th style="width: 18%;">Pelanggan</th>
                <th style="width: 25%;">Rincian Item</th>
                <th style="width: 8%;" class="text-center">Metode</th>
                <th style="width: 15%;" class="text-right">Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="text-center font-mono">{{ $loop->iteration }}</td>
                    <td>
                        <strong class="font-mono" style="color: #1e40af;">{{ $order->invoice_number }}</strong>
                    </td>
                    <td>
                        {{ $order->created_at->format('d/m/Y') }}<br>
                        <span style="color: #64748b; font-size: 7.5pt;">{{ $order->created_at->format('H:i') }} WIB</span>
                    </td>
                    <td>
                        <strong>{{ $order->user->name ?? 'Pelanggan' }}</strong><br>
                        <span style="color: #64748b; font-size: 7.5pt;">{{ $order->user->email ?? '-' }}</span>
                    </td>
                    <td>
                        @foreach($order->items as $item)
                            <div style="font-size: 7.5pt; margin-bottom: 2px;">
                                • {{ $item->product_name }} ({{ $item->qty }}x @ {{ number_format($item->price, 0, ',', '.') }})
                            </div>
                        @endforeach
                    </td>
                    <td class="text-center">
                        @if($order->payment_method === 'qris')
                            <span class="badge-qris">QRIS</span>
                        @else
                            <span class="badge-cash">Tunai</span>
                        @endif
                    </td>
                    <td class="text-right font-mono fw-bold">
                        Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; color: #94a3b8;">
                        Tidak ada transaksi penjualan yang tercatat pada periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Grand Total Recap Box --}}
    <table class="total-box-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 50%;"></td>
            <td class="total-box">
                <table style="width: 100%;" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="color: #475569; font-size: 9pt;">Total Transaksi:</td>
                        <td class="text-right font-mono fw-bold" style="font-size: 9pt;">{{ number_format($totalOrders) }} Transaksi</td>
                    </tr>
                    <tr>
                        <td style="color: #475569; font-size: 9pt; padding-top: 4px;">Total Barang Terjual:</td>
                        <td class="text-right font-mono fw-bold" style="font-size: 9pt; padding-top: 4px;">{{ number_format($totalItemsSold) }} Pcs</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="border-top: 1px dashed #93c5fd; padding-top: 6px; margin-top: 6px;"></td>
                    </tr>
                    <tr>
                        <td style="font-size: 11pt; font-weight: bold; color: #1e3a8a;">Total Omset Bersih:</td>
                        <td class="text-right font-mono fw-bold" style="font-size: 12pt; color: #1d4ed8;">
                            Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Signatures Section --}}
    <table class="sig-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="sig-box">
                <div style="font-size: 8.5pt; color: #64748b;">Disiapkan oleh Sistem,</div>
                <div style="font-weight: bold; margin-top: 4px;">Sistem Kasir & Operasional Tokobii</div>
                <div style="height: 50px;"></div>
                <div style="font-size: 8.5pt; font-weight: bold; color: #334155;">( Terverifikasi Otomatis )</div>
            </td>
            <td style="width: 20%;"></td>
            <td class="sig-box">
                <div style="font-size: 8.5pt; color: #64748b;">Mengetahui & Menyetujui,</div>
                <div style="font-weight: bold; margin-top: 4px;">Pemilik Usaha (Owner Tokobii)</div>
                <div style="height: 50px;"></div>
                <div style="font-size: 9pt; font-weight: bold; text-decoration: underline; color: #0f172a;">
                    {{ auth()->user()->name ?? 'Owner Tokobii' }}
                </div>
            </td>
        </tr>
    </table>

    {{-- Footer Note --}}
    <div class="footer-note">
        Dokumen laporan ini dicetak secara otomatis dan sah dari Portal Eksekutif Tokobii Store pada {{ now()->translatedFormat('d F Y, H:i') }} WIB.
    </div>

</body>
</html>
