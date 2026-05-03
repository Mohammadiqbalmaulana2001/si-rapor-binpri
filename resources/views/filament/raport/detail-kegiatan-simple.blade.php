@if(empty($detail))
    <p class="text-sm text-gray-400 py-4 text-center">Tidak ada data kegiatan</p>
@else
<div style="width:100%; overflow-x:auto; margin-top:0.5rem; border-radius:8px; border:1px solid rgba(0,0,0,0.1);">
    <table style="width:100%; min-width:100%; border-collapse:collapse; font-size:12px;">
        <thead>
            <tr style="background:#1e3a5f; color:white;">
                <th style="padding:8px 10px; text-align:left; font-weight:600; white-space:nowrap;">Nama Kegiatan</th>
                <th style="padding:8px 6px; text-align:center; font-weight:600;">Sesi</th>
                <th style="padding:8px 6px; text-align:center; font-weight:600;">Hadir</th>
                <th style="padding:8px 6px; text-align:center; font-weight:600;">Absen</th>
                <th style="padding:8px 6px; text-align:center; font-weight:600;">Aktif</th>
                <th style="padding:8px 6px; text-align:center; font-weight:600;">Pasif</th>
                <th style="padding:8px 6px; text-align:center; font-weight:600; white-space:nowrap;">Perlu PB</th>
                <th style="padding:8px 10px; text-align:center; font-weight:600; min-width:110px;">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detail as $i => $row)
            <tr style="background:{{ $i % 2 === 0 ? '#ffffff' : '#f9fafb' }}; border-top:1px solid #f0f0f0;">
                <td style="padding:8px 10px; font-weight:500; color:#111;">
                    {{ $row['nama_kegiatan'] }}
                    <span style="color:#9ca3af; font-weight:400; font-size:10px; margin-left:4px;">({{ $row['kategori'] }})</span>
                </td>
                <td style="padding:8px 6px; text-align:center; font-weight:700; color:#374151;">
                    {{ $row['total'] ?: '—' }}
                </td>
                <td style="padding:8px 6px; text-align:center; font-weight:700; color:#16a34a;">
                    {{ $row['hadir'] ?: '0' }}
                </td>
                <td style="padding:8px 6px; text-align:center; font-weight:700; color:#dc2626;">
                    {{ ($row['tidak_hadir'] + $row['izin'] + $row['sakit']) ?: '0' }}
                </td>
                <td style="padding:8px 6px; text-align:center; font-weight:700; color:#16a34a;">
                    {{ $row['aktif'] ?: '—' }}
                </td>
                <td style="padding:8px 6px; text-align:center; font-weight:700; color:#d97706;">
                    {{ $row['pasif'] ?: '—' }}
                </td>
                <td style="padding:8px 6px; text-align:center; font-weight:700; color:#dc2626;">
                    {{ $row['perlu'] ?: '—' }}
                </td>
                <td style="padding:8px 10px;">
                    @if($row['total'] > 0)
                        <div style="display:flex; align-items:center; gap:6px;">
                            <div style="flex:1; height:6px; border-radius:999px; background:#e5e7eb; overflow:hidden;">
                                <div style="height:100%; border-radius:999px; width:{{ $row['persen'] }}%; background:{{ $row['persen_color'] }};"></div>
                            </div>
                            <span style="font-size:10px; font-weight:700; white-space:nowrap; color:{{ $row['persen_color'] }};">{{ $row['persen'] }}%</span>
                        </div>
                    @else
                        <span style="font-size:10px; color:#d1d5db;">Tidak ada sesi</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif