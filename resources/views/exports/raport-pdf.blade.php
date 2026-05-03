<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans', Arial, sans-serif;
        font-size: 12px;
        color: #111827;
        background: #fff;
    }

    /* ── HEADER DOKUMEN ── */
    .doc-header {
        background: #1e3a5f;
        color: white;
        padding: 16px 20px;
        margin-bottom: 14px;
        border-radius: 4px;
    }
    .doc-header h1 {
        font-size: 17px;
        font-weight: bold;
        letter-spacing: 0.05em;
        margin-bottom: 3px;
    }
    .doc-header p {
        font-size: 11px;
        opacity: 0.75;
    }

    /* ── IDENTITAS + RINGKASAN (2 kolom) ── */
    .top-grid {
        display: table;
        width: 100%;
        margin-bottom: 12px;
        border-spacing: 8px 0;
    }
    .top-left, .top-right {
        display: table-cell;
        vertical-align: top;
    }
    .top-left  { width: 52%; }
    .top-right { width: 48%; }

    .section-title {
        background: #374151;
        color: white;
        font-size: 11px;
        font-weight: bold;
        padding: 6px 10px;
        letter-spacing: 0.05em;
        border-radius: 3px 3px 0 0;
    }
    .info-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #e5e7eb;
    }
    .info-table td {
        padding: 6px 10px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 11px;
    }
    .info-table td:first-child {
        width: 38%;
        color: #6b7280;
        font-weight: bold;
        background: #f9fafb;
    }
    .info-table td:last-child {
        font-weight: 500;
        color: #111827;
    }

    /* ── RINGKASAN ANGKA ── */
    .stats-grid {
        display: table;
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #e5e7eb;
    }
    .stat-cell {
        display: table-cell;
        text-align: center;
        padding: 8px 4px;
        border-right: 1px solid #e5e7eb;
        vertical-align: middle;
    }
    .stat-cell:last-child { border-right: none; }
    .stat-label {
        font-size: 10px;
        color: #6b7280;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 3px;
    }
    .stat-value {
        font-size: 17px;
        font-weight: bold;
        color: #111827;
    }
    .stat-value.green  { color: #16a34a; }
    .stat-value.red    { color: #dc2626; }
    .stat-value.yellow { color: #d97706; }
    .stat-value.purple { color: #7c3aed; }
    .stat-value.orange { color: #ea580c; }

    /* ── TABEL DETAIL ── */
    .detail-title {
        background: #1e3a5f;
        color: white;
        font-size: 11px;
        font-weight: bold;
        padding: 7px 10px;
        letter-spacing: 0.05em;
        margin-bottom: 0;
        border-radius: 3px 3px 0 0;
    }
    .detail-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }
    .detail-table thead tr {
        background: #374151;
        color: white;
    }
    .detail-table thead th {
        padding: 7px 5px;
        text-align: center;
        font-weight: 600;
        font-size: 9.5px;
        letter-spacing: 0.03em;
        border: 1px solid #4b5563;
    }
    .detail-table thead th:first-child {
        text-align: left;
        padding-left: 8px;
    }
    .detail-table tbody tr:nth-child(even) { background: #f9fafb; }
    .detail-table tbody tr:nth-child(odd)  { background: #ffffff; }
    .detail-table tbody td {
        padding: 6px 5px;
        text-align: center;
        border: 1px solid #f0f0f0;
        vertical-align: middle;
    }
    .detail-table tbody td:first-child {
        text-align: left;
        padding-left: 8px;
        font-weight: 600;
        color: #111827;
    }
    .detail-table tfoot tr {
        background: #dbeafe;
    }
    .detail-table tfoot td {
        padding: 7px 5px;
        text-align: center;
        border: 2px solid #1e3a5f;
        font-weight: bold;
        color: #1e3a5f;
        font-size: 11px;
    }
    .detail-table tfoot td:first-child {
        text-align: left;
        padding-left: 8px;
    }

    /* ── BADGE ── */
    .badge {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 20px;
        font-size: 9.5px;
        font-weight: 600;
    }
    .badge-islam    { background: #fef9c3; color: #92400e; }
    .badge-kristen  { background: #dbeafe; color: #1e40af; }
    .badge-harian   { background: #dcfce7; color: #166534; }
    .badge-mingguan { background: #dbeafe; color: #1e40af; }
    .badge-bulanan  { background: #ffedd5; color: #9a3412; }
    .badge-default  { background: #f3f4f6; color: #374151; }

    /* ── PROGRESS BAR ── */
    .bar-wrap {
        background: #e5e7eb;
        border-radius: 999px;
        height: 5px;
        width: 60px;
        display: inline-block;
        vertical-align: middle;
        overflow: hidden;
        margin-right: 3px;
    }
    .bar-fill {
        height: 100%;
        border-radius: 999px;
    }

    /* ── FOOTER CATATAN ── */
    .note-section {
        margin-top: 12px;
        border: 1px solid #e5e7eb;
        border-radius: 3px;
        overflow: hidden;
    }
    .note-section .section-title { border-radius: 0; }
    .note-table { width: 100%; border-collapse: collapse; }
    .note-table td {
        padding: 7px 10px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 11px;
        vertical-align: top;
    }
    .note-table td:first-child {
        width: 18%;
        font-weight: bold;
        color: #374151;
        background: #f9fafb;
    }
    .note-table td:last-child { color: #111827; }

    /* ── STEMPEL FINALISASI ── */
    .stamp {
        text-align: right;
        margin-top: 10px;
        font-size: 10px;
        color: #6b7280;
    }
    .stamp .finalized {
        display: inline-block;
        border: 2px solid #16a34a;
        color: #16a34a;
        font-weight: bold;
        font-size: 12px;
        padding: 4px 12px;
        border-radius: 4px;
        letter-spacing: 0.08em;
        transform: rotate(-3deg);
        margin-left: 8px;
    }
</style>
</head>
<body>

{{-- ══ HEADER ══ --}}
<div class="doc-header">
    <h1 style="text-align: center">RAPORT PEMBINAAN WBP LAPAS NARKOTIKA KLS II A PAMEKASAN/h1>
    <p>Dicetak pada: {{ now()->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
</div>

{{-- ══ IDENTITAS + RINGKASAN ══ --}}
<div class="top-grid">
    {{-- Identitas --}}
    <div class="top-left">
        <div class="section-title">IDENTITAS WARGA BINAAN</div>
        <table class="info-table">
            <tr><td>Nama Lengkap</td><td>{{ $wb->nama_lengkap }}</td></tr>
            <tr><td>No. Register</td><td>{{ $wb->no_register }}</td></tr>
            <tr><td>Agama</td><td>{{ $wb->agama ?? '—' }}</td></tr>
            <tr><td>Blok / Kamar</td><td>{{ $wb->blok_kamar ?? '—' }}</td></tr>
            <tr><td>Periode</td><td>{{ $raport->nama_bulan }}</td></tr>
        </table>
    </div>

    {{-- Ringkasan --}}
    <div class="top-right">
        <div class="section-title">RINGKASAN KEHADIRAN</div>
        <table class="stats-grid">
            <tr>
                <td class="stat-cell" style="background:#f0fdf4;">
                    <div class="stat-label">Total Sesi</div>
                    <div class="stat-value">{{ $totalSesi }}</div>
                </td>
                <td class="stat-cell">
                    <div class="stat-label">Hadir</div>
                    <div class="stat-value green">{{ $totalHadir }}</div>
                </td>
                <td class="stat-cell">
                    <div class="stat-label">Tdk Hadir</div>
                    <div class="stat-value red">{{ collect($detail)->sum('tidak_hadir') }}</div>
                </td>
                <td class="stat-cell">
                    <div class="stat-label">Izin</div>
                    <div class="stat-value purple">{{ collect($detail)->sum('izin') }}</div>
                </td>
                <td class="stat-cell">
                    <div class="stat-label">Sakit</div>
                    <div class="stat-value orange">{{ collect($detail)->sum('sakit') }}</div>
                </td>
            </tr>
            <tr style="border-top: 1px solid #e5e7eb;">
                <td class="stat-cell" style="background:#eff6ff;">
                    @php
                        $pColor = $persen >= 75 ? '#16a34a' : ($persen >= 60 ? '#d97706' : '#dc2626');
                    @endphp
                    <div class="stat-label">Kehadiran</div>
                    <div class="stat-value" style="color:{{ $pColor }}; font-size:20px;">{{ $persen }}%</div>
                </td>
                <td class="stat-cell" colspan="2">
                    <div class="stat-label">Aktif</div>
                    <div class="stat-value green">{{ collect($detail)->sum('aktif') }}</div>
                </td>
                <td class="stat-cell">
                    <div class="stat-label">Pasif</div>
                    <div class="stat-value yellow">{{ collect($detail)->sum('pasif') }}</div>
                </td>
                <td class="stat-cell">
                    <div class="stat-label">Perlu PB</div>
                    <div class="stat-value red">{{ collect($detail)->sum('perlu') }}</div>
                </td>
            </tr>
        </table>
    </div>
</div>

{{-- ══ TABEL DETAIL ══ --}}
<div class="detail-title">DETAIL KEHADIRAN PER KEGIATAN</div>
<table class="detail-table">
    <thead>
        <tr>
            <th style="width:26%; text-align:left; padding-left:8px;">Nama Kegiatan</th>
            <th style="width:9%;">Kategori</th>
            <th style="width:9%;">Frekuensi</th>
            <th style="width:6%;">Sesi</th>
            <th style="width:6%;">Hadir</th>
            <th style="width:7%;">Tdk Hadir</th>
            <th style="width:6%;">Izin</th>
            <th style="width:6%;">Sakit</th>
            <th style="width:6%;">Aktif</th>
            <th style="width:6%;">Pasif</th>
            <th style="width:7%;">Perlu PB</th>
            <th style="width:12%;">Kehadiran %</th>
        </tr>
    </thead>
    <tbody>
        @foreach($detail as $row)
        @php
            $katClass  = match($row['kategori']) {
                'Islam'           => 'badge-islam',
                'Kristen','Katolik' => 'badge-kristen',
                default           => 'badge-default',
            };
            $frekClass = match($row['frekuensi']) {
                'harian'   => 'badge-harian',
                'mingguan' => 'badge-mingguan',
                'bulanan'  => 'badge-bulanan',
                default    => 'badge-default',
            };
            $frekLabel = match($row['frekuensi']) {
                'harian'   => 'Harian',
                'mingguan' => 'Mingguan',
                'bulanan'  => 'Bulanan',
                default    => $row['frekuensi'],
            };
        @endphp
        <tr>
            <td>
                {{ $row['nama_kegiatan'] }}
                @if($row['penanggung_jawab'] !== '—')
                    <br><span style="font-size:9px; color:#9ca3af; font-weight:400;">{{ $row['penanggung_jawab'] }}</span>
                @endif
            </td>
            <td><span class="badge {{ $katClass }}">{{ $row['kategori'] }}</span></td>
            <td><span class="badge {{ $frekClass }}">{{ $frekLabel }}</span></td>
            <td style="font-weight:700;">{{ $row['total'] ?: '—' }}</td>
            <td style="color:#16a34a; font-weight:700;">{{ $row['hadir'] }}</td>
            <td style="color:#dc2626; font-weight:700;">{{ $row['tidak_hadir'] }}</td>
            <td style="color:#7c3aed; font-weight:700;">{{ $row['izin'] }}</td>
            <td style="color:#ea580c; font-weight:700;">{{ $row['sakit'] }}</td>
            <td style="color:#16a34a; font-weight:700;">{{ $row['aktif'] ?: '—' }}</td>
            <td style="color:#d97706; font-weight:700;">{{ $row['pasif'] ?: '—' }}</td>
            <td style="color:#dc2626; font-weight:700;">{{ $row['perlu'] ?: '—' }}</td>
            <td>
                @if($row['total'] > 0)
                    <div class="bar-wrap">
                        <div class="bar-fill" style="width:{{ $row['persen'] }}%; background:{{ $row['persen_color'] }};"></div>
                    </div>
                    <span style="font-weight:700; color:{{ $row['persen_color'] }}; font-size:8.5px;">{{ $row['persen'] }}%</span>
                @else
                    <span style="color:#d1d5db; font-size:8px;">—</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        @php
            $totalTidak = collect($detail)->sum('tidak_hadir');
            $totalIzin  = collect($detail)->sum('izin');
            $totalSakit = collect($detail)->sum('sakit');
            $totalAktif = collect($detail)->sum('aktif');
            $totalPasif = collect($detail)->sum('pasif');
            $totalPerlu = collect($detail)->sum('perlu');
            $pColor     = $persen >= 75 ? '#16a34a' : ($persen >= 60 ? '#d97706' : '#dc2626');
        @endphp
        <tr>
            <td colspan="3">TOTAL KESELURUHAN</td>
            <td>{{ $totalSesi }}</td>
            <td style="color:#16a34a;">{{ $totalHadir }}</td>
            <td style="color:#dc2626;">{{ $totalTidak }}</td>
            <td style="color:#7c3aed;">{{ $totalIzin }}</td>
            <td style="color:#ea580c;">{{ $totalSakit }}</td>
            <td style="color:#16a34a;">{{ $totalAktif }}</td>
            <td style="color:#d97706;">{{ $totalPasif }}</td>
            <td style="color:#dc2626;">{{ $totalPerlu }}</td>
            <td>
                <div class="bar-wrap" style="width:55px;">
                    <div class="bar-fill" style="width:{{ $persen }}%; background:{{ $pColor }};"></div>
                </div>
                <span style="font-weight:700; color:{{ $pColor }}; font-size:9px;">{{ $persen }}%</span>
            </td>
        </tr>
    </tfoot>
</table>

{{-- ══ REKOMENDASI & CATATAN ══ --}}
<div class="note-section">
    <div class="section-title">REKOMENDASI & CATATAN PETUGAS</div>
    <table class="note-table">
        <tr>
            <td>Rekomendasi</td>
            <td>
                @php
                    $rekLabel = match($raport->rekomendasi) {
                        'sangat_baik' => 'Sangat Baik — Layak Remisi / Pembebasan Bersyarat',
                        'baik'        => 'Baik',
                        'cukup'       => 'Cukup',
                        'kurang'      => 'Kurang — Perlu Pembinaan Intensif',
                        default       => '—',
                    };
                    $rekColor = match($raport->rekomendasi) {
                        'sangat_baik' => '#16a34a',
                        'baik'        => '#2563eb',
                        'cukup'       => '#d97706',
                        'kurang'      => '#dc2626',
                        default       => '#6b7280',
                    };
                @endphp
                <span style="font-weight:700; color:{{ $rekColor }};">{{ $rekLabel }}</span>
            </td>
        </tr>
        <tr>
            <td>Catatan Petugas</td>
            <td>{{ $raport->catatan_petugas ?? '—' }}</td>
        </tr>
    </table>
</div>

{{-- ══ STEMPEL ══ --}}
<div class="stamp">
    <span>Dicetak dari Sistem Informasi Manajemen Pembinaan</span>
    @if($raport->is_finalized)
        <span class="finalized">&#10003; DIFINALISASI</span>
    @endif
</div>

</body>
</html>