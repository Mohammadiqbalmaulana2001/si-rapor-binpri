@php $detail = $getState(); @endphp

@if(empty($detail))
    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding:3rem 0; color:#9ca3af;">
        <svg style="width:40px; height:40px; margin-bottom:8px; opacity:0.4;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M3 6h18M3 14h18M3 18h18"/>
        </svg>
        <p style="font-size:14px; margin:0;">Tidak ada data kegiatan untuk periode ini</p>
    </div>
@else
<div style="width:100%; overflow-x:auto; border-radius:8px; border:1px solid rgba(0,0,0,0.1);">
    <table style="width:100%; min-width:100%; border-collapse:collapse; font-size:12px;">

        {{-- HEADER --}}
        <thead>
            <tr style="background:#1e3a5f; color:white;">
                <th style="padding:10px 12px; text-align:left; font-size:11px; font-weight:600; letter-spacing:0.03em; white-space:nowrap;">Nama Kegiatan</th>
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:600; white-space:nowrap;">Kategori</th>
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:600; white-space:nowrap;">Frekuensi</th>
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:600;">Total Sesi</th>
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:600;">Hadir</th>
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:600;">Tdk Hadir</th>
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:600;">Izin</th>
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:600;">Sakit</th>
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:600; white-space:nowrap;">Aktif</th>
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:600; white-space:nowrap;">Pasif</th>
                <th style="padding:10px 8px; text-align:center; font-size:11px; font-weight:600; white-space:nowrap;">Perlu PB</th>
                <th style="padding:10px 12px; text-align:center; font-size:11px; font-weight:600; min-width:120px;">Kehadiran %</th>
            </tr>
        </thead>

        {{-- BODY --}}
        <tbody>
            @foreach($detail as $i => $row)
                @php
                    $katColor = match($row['kategori']) {
                        'Islam'              => ['bg'=>'#fef9c3','text'=>'#92400e','border'=>'#fde68a'],
                        'Kristen','Katolik'  => ['bg'=>'#dbeafe','text'=>'#1e40af','border'=>'#bfdbfe'],
                        'Hindu'              => ['bg'=>'#dcfce7','text'=>'#166534','border'=>'#bbf7d0'],
                        default              => ['bg'=>'#f3f4f6','text'=>'#374151','border'=>'#e5e7eb'],
                    };
                    $frekColor = match($row['frekuensi']) {
                        'harian'   => ['bg'=>'#dcfce7','text'=>'#166534'],
                        'mingguan' => ['bg'=>'#dbeafe','text'=>'#1e40af'],
                        'bulanan'  => ['bg'=>'#ffedd5','text'=>'#9a3412'],
                        default    => ['bg'=>'#f3f4f6','text'=>'#374151'],
                    };
                    $frekLabel = match($row['frekuensi']) {
                        'harian'   => 'Harian',
                        'mingguan' => 'Mingguan',
                        'bulanan'  => 'Bulanan',
                        default    => $row['frekuensi'],
                    };
                    $rowBg = $i % 2 === 0 ? '#ffffff' : '#f9fafb';
                @endphp
                <tr style="background:{{ $rowBg }}; border-top:1px solid #f0f0f0;">

                    {{-- Nama Kegiatan + PJ --}}
                    <td style="padding:10px 12px;">
                        <div style="font-weight:600; color:#111827; font-size:13px;">
                            {{ $row['nama_kegiatan'] }}
                        </div>
                        @if($row['penanggung_jawab'] !== '—')
                            <div style="font-size:11px; color:#9ca3af; margin-top:2px;">
                                👤 {{ $row['penanggung_jawab'] }}
                            </div>
                        @endif
                    </td>

                    {{-- Kategori --}}
                    <td style="padding:10px 8px; text-align:center;">
                        <span style="display:inline-block; padding:2px 8px; border-radius:999px; font-size:11px; font-weight:500; background:{{ $katColor['bg'] }}; color:{{ $katColor['text'] }}; border:1px solid {{ $katColor['border'] }};">
                            {{ $row['kategori'] }}
                        </span>
                    </td>

                    {{-- Frekuensi --}}
                    <td style="padding:10px 8px; text-align:center;">
                        <span style="display:inline-block; padding:2px 8px; border-radius:999px; font-size:11px; font-weight:500; background:{{ $frekColor['bg'] }}; color:{{ $frekColor['text'] }};">
                            {{ $frekLabel }}
                        </span>
                    </td>

                    {{-- Total Sesi --}}
                    <td style="padding:10px 8px; text-align:center;">
                        @if($row['total'] === 0)
                            <span style="color:#d1d5db; font-size:11px;">—</span>
                        @else
                            <span style="font-weight:700; color:#111827;">{{ $row['total'] }}</span>
                        @endif
                    </td>

                    {{-- Hadir --}}
                    <td style="padding:10px 8px; text-align:center;">
                        @if($row['hadir'] > 0)
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:50%; background:#dcfce7; color:#166534; font-weight:700; font-size:11px;">
                                {{ $row['hadir'] }}
                            </span>
                        @else
                            <span style="color:#d1d5db; font-size:11px;">0</span>
                        @endif
                    </td>

                    {{-- Tidak Hadir --}}
                    <td style="padding:10px 8px; text-align:center;">
                        @if($row['tidak_hadir'] > 0)
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:50%; background:#fee2e2; color:#991b1b; font-weight:700; font-size:11px;">
                                {{ $row['tidak_hadir'] }}
                            </span>
                        @else
                            <span style="color:#d1d5db; font-size:11px;">0</span>
                        @endif
                    </td>

                    {{-- Izin --}}
                    <td style="padding:10px 8px; text-align:center;">
                        @if($row['izin'] > 0)
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:50%; background:#f3e8ff; color:#6b21a8; font-weight:700; font-size:11px;">
                                {{ $row['izin'] }}
                            </span>
                        @else
                            <span style="color:#d1d5db; font-size:11px;">0</span>
                        @endif
                    </td>

                    {{-- Sakit --}}
                    <td style="padding:10px 8px; text-align:center;">
                        @if($row['sakit'] > 0)
                            <span style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:50%; background:#ffedd5; color:#9a3412; font-weight:700; font-size:11px;">
                                {{ $row['sakit'] }}
                            </span>
                        @else
                            <span style="color:#d1d5db; font-size:11px;">0</span>
                        @endif
                    </td>

                    {{-- Aktif --}}
                    <td style="padding:10px 8px; text-align:center;">
                        @if($row['aktif'] > 0)
                            <span style="font-weight:700; color:#16a34a;">{{ $row['aktif'] }}</span>
                        @else
                            <span style="color:#d1d5db; font-size:11px;">—</span>
                        @endif
                    </td>

                    {{-- Pasif --}}
                    <td style="padding:10px 8px; text-align:center;">
                        @if($row['pasif'] > 0)
                            <span style="font-weight:700; color:#d97706;">{{ $row['pasif'] }}</span>
                        @else
                            <span style="color:#d1d5db; font-size:11px;">—</span>
                        @endif
                    </td>

                    {{-- Perlu Pembinaan --}}
                    <td style="padding:10px 8px; text-align:center;">
                        @if($row['perlu'] > 0)
                            <span style="font-weight:700; color:#dc2626;">{{ $row['perlu'] }}</span>
                        @else
                            <span style="color:#d1d5db; font-size:11px;">—</span>
                        @endif
                    </td>

                    {{-- Progress Bar --}}
                    <td style="padding:10px 12px;">
                        @if($row['total'] === 0)
                            <div style="text-align:center; font-size:11px; color:#d1d5db;">Tidak ada sesi</div>
                        @else
                            <div style="display:flex; align-items:center; gap:6px;">
                                <div style="flex:1; height:6px; border-radius:999px; background:#e5e7eb; overflow:hidden;">
                                    <div style="height:100%; border-radius:999px; width:{{ $row['persen'] }}%; background:{{ $row['persen_color'] }};"></div>
                                </div>
                                <span style="font-size:10px; font-weight:700; white-space:nowrap; color:{{ $row['persen_color'] }};">
                                    {{ $row['persen'] }}%
                                </span>
                            </div>
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>

        {{-- FOOTER TOTAL --}}
        <tfoot>
            @php
                $totalSesi  = collect($detail)->sum('total');
                $totalHadir = collect($detail)->sum('hadir');
                $persen     = $totalSesi > 0
                    ? round($totalHadir / $totalSesi * 100, 1)
                    : 0;
                $color = match(true) {
                    $persen >= 75  => '#22c55e',
                    $persen >= 60  => '#f59e0b',
                    $totalSesi === 0 => '#e5e7eb',
                    default        => '#ef4444',
                };
            @endphp
            <tr style="background:rgba(30,58,95,0.08); border-top:2px solid rgba(30,58,95,0.2); font-weight:600;">
                <td colspan="3" style="padding:10px 12px; font-size:13px; color:#374151;">Total Keseluruhan</td>
                <td style="padding:10px 8px; text-align:center; font-weight:700; color:#111827;">{{ $totalSesi }}</td>
                <td style="padding:10px 8px; text-align:center; font-weight:700; color:#16a34a;">{{ collect($detail)->sum('hadir') }}</td>
                <td style="padding:10px 8px; text-align:center; font-weight:700; color:#dc2626;">{{ collect($detail)->sum('tidak_hadir') }}</td>
                <td style="padding:10px 8px; text-align:center; font-weight:700; color:#7c3aed;">{{ collect($detail)->sum('izin') }}</td>
                <td style="padding:10px 8px; text-align:center; font-weight:700; color:#ea580c;">{{ collect($detail)->sum('sakit') }}</td>
                <td style="padding:10px 8px; text-align:center; font-weight:700; color:#16a34a;">{{ collect($detail)->sum('aktif') }}</td>
                <td style="padding:10px 8px; text-align:center; font-weight:700; color:#d97706;">{{ collect($detail)->sum('pasif') }}</td>
                <td style="padding:10px 8px; text-align:center; font-weight:700; color:#dc2626;">{{ collect($detail)->sum('perlu') }}</td>
                <td style="padding:10px 12px;">
                    <div style="display:flex; align-items:center; gap:6px;">
                        <div style="flex:1; height:8px; border-radius:999px; background:#e5e7eb; overflow:hidden;">
                            <div style="height:100%; border-radius:999px; width:{{ $persen }}%; background:{{ $color }};"></div>
                        </div>
                        <span style="font-size:11px; font-weight:700; white-space:nowrap; color:{{ $color }};">{{ $persen }}%</span>
                    </div>
                </td>
            </tr>
        </tfoot>

    </table>
</div>
@endif