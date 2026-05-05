{{-- resources/views/filament/widgets/panduan-sistem-widget.blade.php --}}
<x-filament-widgets::widget>
<style>
    [x-cloak] {
        display: none !important;
    }

    .panduan-sistem-widget {
        padding: 1.5rem 0 0;
        color: #374151;
    }

    .panduan-sistem-widget .panduan-card,
    .panduan-sistem-widget .panduan-step,
    .panduan-sistem-widget .panduan-status,
    .panduan-sistem-widget .panduan-summary {
        background: #ffffff;
        border-color: #e5e7eb;
    }

    .panduan-sistem-widget .panduan-card > button,
    .panduan-sistem-widget .panduan-detail,
    .panduan-sistem-widget .panduan-intro-toggle,
    .panduan-sistem-widget .panduan-intro-panel,
    .panduan-sistem-widget .panduan-conclusion-toggle,
    .panduan-sistem-widget .panduan-conclusion-panel,
    .panduan-sistem-widget .panduan-status-card,
    .panduan-sistem-widget .panduan-summary {
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.06), 0 1px 1px rgba(15, 23, 42, 0.04);
    }

    .panduan-sistem-widget .panduan-card > button:hover,
    .panduan-sistem-widget .panduan-intro-toggle:hover,
    .panduan-sistem-widget .panduan-conclusion-toggle:hover {
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08), 0 2px 4px rgba(15, 23, 42, 0.06);
    }

    .dark .panduan-sistem-widget {
        color: #e5e7eb;
    }

    .dark .panduan-sistem-widget .panduan-card,
    .dark .panduan-sistem-widget .panduan-step,
    .dark .panduan-sistem-widget .panduan-status,
    .dark .panduan-sistem-widget .panduan-summary {
        background: #111827;
        border-color: #374151;
    }

    .dark .panduan-sistem-widget .panduan-card > button {
        background: #111827 !important;
        border-color: #374151 !important;
    }

    .dark .panduan-sistem-widget .panduan-card > button .panduan-action-text {
        color: #f8fafc !important;
    }

    .dark .panduan-sistem-widget .panduan-card > button svg {
        stroke: #cbd5e1 !important;
    }

    .dark .panduan-sistem-widget .panduan-summary {
        background: #0f172a !important;
        border-left-color: #60a5fa !important;
    }

    .dark .panduan-sistem-widget .panduan-muted {
        color: #cbd5e1 !important;
    }

    .dark .panduan-sistem-widget .panduan-body-text {
        color: #e2e8f0 !important;
    }

    .dark .panduan-sistem-widget .panduan-detail {
        background: #0f172a !important;
        border-color: #334155 !important;
        color: #e2e8f0 !important;
    }

    .dark .panduan-sistem-widget .panduan-action-text {
        color: #f8fafc !important;
    }

    .dark .panduan-sistem-widget .panduan-footer,
    .dark .panduan-sistem-widget .panduan-muted,
    .dark .panduan-sistem-widget .panduan-header-copy {
        color: #cbd5e1 !important;
    }

    .dark .panduan-sistem-widget .panduan-intro-toggle,
    .dark .panduan-sistem-widget .panduan-intro-panel,
    .dark .panduan-sistem-widget .panduan-conclusion-toggle,
    .dark .panduan-sistem-widget .panduan-conclusion-panel {
        background: #111827 !important;
        border-color: #374151 !important;
    }

    .dark .panduan-sistem-widget .panduan-intro-panel,
    .dark .panduan-sistem-widget .panduan-conclusion-panel {
        color: #e2e8f0 !important;
    }

    .dark .panduan-sistem-widget .panduan-status-section {
        border-top-color: #374151 !important;
    }

    .dark .panduan-sistem-widget .panduan-status-section-title {
        color: #cbd5e1 !important;
    }

    .dark .panduan-sistem-widget .panduan-status-card {
        background: #111827 !important;
        border-color: #475569 !important;
    }

    .dark .panduan-sistem-widget .panduan-status-label {
        color: #f8fafc !important;
    }

    .dark .panduan-sistem-widget .panduan-status-desc {
        color: #cbd5e1 !important;
    }

    .dark .panduan-sistem-widget .panduan-subtle {
        color: #94a3b8 !important;
    }
</style>
<div class="panduan-sistem-widget">

    {{-- ══ HEADER ══ --}}
    <div style="background:#1e3a5f;border-radius:12px;padding:1.25rem 1.5rem;
                position:relative;overflow:hidden;margin-bottom:1.5rem;">
        <div style="position:absolute;top:-2rem;right:-2rem;width:8rem;height:8rem;
                     border-radius:50%;background:rgba(255,255,255,.05);pointer-events:none;"></div>
        <div style="position:absolute;bottom:-1.5rem;right:4rem;width:6rem;height:6rem;
                     border-radius:50%;background:rgba(255,255,255,.05);pointer-events:none;"></div>
        <div style="display:flex;align-items:center;flex-wrap:wrap;
                     justify-content:space-between;gap:1rem;position:relative;">
            <div>
                <div style="display:flex;align-items:center;gap:.625rem;margin-bottom:.25rem;">
                    <span style="font-size:1.5rem;line-height:1;">🏛️</span>
                    <span style="font-size:1.1rem;font-weight:700;color:#fff;letter-spacing:.03em;">
                        SI-RAPOR-BINPRI
                    </span>
                </div>
                <p class="panduan-header-copy" style="color:rgba(255,255,255,.7);font-size:.85rem;margin:0;">
                    Sistem Informasi Raport Pembina kepribadian — Lapas Narkotika Kelas IIA Pamekasan
                </p>
            </div>
            <div style="text-align:right;">
                <p class="panduan-header-copy" style="color:rgba(255,255,255,.5);font-size:.7rem;margin:0;">Hari ini</p>
                <p style="color:#fff;font-weight:600;font-size:.85rem;margin:0;">
                    {{ now()->isoFormat('dddd, D MMMM Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- ══ TOGGLE UTAMA — membungkus semua konten panduan ══ --}}
    @php
    $steps = [
        [
            'no'     => '01',
            'judul'  => 'Petugas memilih bulan',
            'color'  => '#185FA5',
            'detail' => 'Petugas membuka sistem dan memilih bulan yang ingin dibuatkan rapornya — misalnya <strong>April 2026</strong> — lalu menekan tombol <em>Generate Rapor</em>. Proses selanjutnya dikerjakan sepenuhnya oleh sistem.',
        ],
        [
            'no'     => '02',
            'judul'  => 'Sistem memilih siapa yang dinilai',
            'color'  => '#3B6D11',
            'detail' => 'Sistem otomatis hanya memilih warga binaan yang <strong>masih aktif</strong> di lapas. Yang sudah bebas atau dipindahkan tidak ikut dinilai, sehingga rapor tidak pernah dibuat untuk orang yang sudah tidak ada.',
        ],
        [
            'no'     => '03',
            'judul'  => 'Sistem membuka catatan absensi',
            'color'  => '#854F0B',
            'detail' => 'Untuk setiap warga binaan, sistem membaca seluruh catatan kehadiran selama bulan tersebut: berapa kali <strong>hadir</strong>, berapa kali <strong>aktif berpartisipasi</strong>, berapa kali <strong>pasif</strong> (hadir tapi diam), dan berapa kali <strong>perlu pembinaan khusus</strong>.',
        ],
        [
            'no'     => '04',
            'judul'  => 'Sistem menghitung persentase kehadiran',
            'color'  => '#534AB7',
            'detail' => 'Rumus sederhana: <code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:.8rem;">Persentase = (jumlah hadir ÷ total kegiatan) × 100</code><br><br>Contoh: kegiatan 20 kali, hadir 16 kali → <strong>16 ÷ 20 × 100 = 80%</strong>',
        ],
        [
            'no'     => '05',
            'judul'  => 'Sistem menentukan nilai',
            'color'  => '#993C1D',
            'detail' => '<div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:6px;">
                <div style="padding:8px 12px;border-radius:8px;background:#EAF3DE;border:1px solid #C0DD97;">
                    <div style="font-size:.75rem;font-weight:600;color:#27500A;">Sangat baik</div>
                    <div style="font-size:.7rem;color:#3B6D11;margin-top:2px;">90%+ dan aktif</div>
                </div>
                <div style="padding:8px 12px;border-radius:8px;background:#E6F1FB;border:1px solid #B5D4F4;">
                    <div style="font-size:.75rem;font-weight:600;color:#0C447C;">Baik</div>
                    <div style="font-size:.7rem;color:#185FA5;margin-top:2px;">75% – 89%</div>
                </div>
                <div style="padding:8px 12px;border-radius:8px;background:#FAEEDA;border:1px solid #FAC775;">
                    <div style="font-size:.75rem;font-weight:600;color:#633806;">Cukup</div>
                    <div style="font-size:.7rem;color:#854F0B;margin-top:2px;">60% – 74%</div>
                </div>
                <div style="padding:8px 12px;border-radius:8px;background:#FCEBEB;border:1px solid #F7C1C1;">
                    <div style="font-size:.75rem;font-weight:600;color:#791F1F;">Kurang</div>
                    <div style="font-size:.7rem;color:#A32D2D;margin-top:2px;">di bawah 60%</div>
                </div>
            </div>',
        ],
        [
            'no'     => '06',
            'judul'  => 'Rapor disimpan',
            'color'  => '#0F6E56',
            'detail' => 'Sistem menangani tiga kondisi secara otomatis:<ul style="margin:8px 0 0;padding-left:18px;display:flex;flex-direction:column;gap:4px;">
                <li><strong>Belum ada</strong> — rapor baru dibuat</li>
                <li><strong>Sudah ada</strong> — rapor diperbarui dengan data terbaru</li>
                <li><strong>Sudah disahkan</strong> — rapor tidak disentuh, untuk melindungi dokumen resmi</li>
            </ul>',
        ],
        [
            'no'     => '07',
            'judul'  => 'Selesai',
            'color'  => '#5F5E5A',
            'detail' => 'Sistem menampilkan ringkasan hasil, misalnya:<div style="margin-top:8px;padding:10px 14px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;font-size:.8rem;color:#374151;">"50 warga binaan aktif — 45 rapor berhasil dibuat, 5 dilewati karena tidak ada data absensi."</div>',
        ],
    ];
    @endphp

    <div x-data="{ open: false }" style="margin:0 0 1.25rem;">

        {{-- Tombol toggle utama --}}
        <button
            type="button"
            x-on:click="open = !open"
            class="panduan-intro-toggle"
            style="width:100%;text-align:left;border:1px solid #e5e7eb;border-radius:10px;
                   padding:12px 14px;background:#f8fafc;display:flex;align-items:center;
                   justify-content:space-between;gap:12px;cursor:pointer;">
            <span class="panduan-action-text" style="font-size:.85rem;font-weight:700;color:#1f2937;line-height:1.6;">
                Klik setiap tahap untuk melihat penjelasan detail proses penilaian rapor warga binaan.
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                 stroke="#6b7280" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                 x-bind:style="open ? 'transform:rotate(180deg);transition:transform .2s;flex-shrink:0;' : 'transform:rotate(0deg);transition:transform .2s;flex-shrink:0;'">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </button>

        {{-- Semua konten panduan di dalam toggle utama --}}
        <div x-cloak x-show="open" x-transition.opacity.duration.150ms
             style="display:none;margin-top:.75rem;">

            {{-- ══ 7 TAHAP ══ --}}
            <div style="display:flex;flex-direction:column;gap:6px;margin-bottom:1.75rem;">
                @foreach($steps as $i => $step)
                <div x-data="{ open: false }" class="panduan-card">
                    <button
                        type="button"
                        x-on:click="open = !open"
                        x-bind:aria-expanded="open.toString()"
                        style="width:100%;text-align:left;background:#fff;
                               border:1px solid #e5e7eb;border-radius:10px;
                               padding:12px 16px;cursor:pointer;
                               display:flex;align-items:center;gap:12px;
                               transition:background .15s,border-color .15s;">
                        <span style="width:28px;height:28px;border-radius:50%;
                                      background:{{ $step['color'] }};color:#fff;
                                      display:inline-flex;align-items:center;justify-content:center;
                                      font-size:.7rem;font-weight:700;flex-shrink:0;">
                            {{ $step['no'] }}
                        </span>
                        <span class="panduan-action-text" style="font-size:.875rem;font-weight:600;color:#1f2937;flex:1;">
                            Tahap {{ $step['no'] }} — {{ $step['judul'] }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg"
                             width="14" height="14" viewBox="0 0 24 24" fill="none"
                             stroke="#9ca3af" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round"
                             style="flex-shrink:0;transition:transform .2s;"
                             x-bind:style="open ? 'flex-shrink:0;transition:transform .2s;transform:rotate(180deg);' : 'flex-shrink:0;transition:transform .2s;transform:rotate(0deg);'">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                    <div
                        x-cloak
                        x-show="open"
                        x-transition.opacity.duration.150ms
                        class="panduan-detail"
                        style="display:none;padding:14px 16px 16px 56px;
                               background:#f9fafb;border:1px solid #e5e7eb;border-top:none;
                               border-radius:0 0 10px 10px;
                               font-size:.82rem;color:#4b5563;line-height:1.75;">
                        {!! $step['detail'] !!}
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ══ PENILAIAN KEAKTIFAN ══ --}}
            <div class="panduan-status-section" style="border-top:1px solid #e5e7eb;padding-top:1.5rem;margin-bottom:1.5rem;">
                <p class="panduan-status-section-title" style="font-size:.7rem;font-weight:700;color:#9ca3af;letter-spacing:.1em;
                           text-transform:uppercase;margin:0 0 1rem;">
                    Sistem penilaian keaktifan — anti titip absen
                </p>
                <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:8px;">
                    @foreach([
                        [
                            'bg'     => '#EAF3DE',
                            'border' => '#C0DD97',
                            'title'  => '#27500A',
                            'text'   => '#3B6D11',
                            'label'  => 'Aktif',
                            'desc'   => 'Hadir dan berpartisipasi penuh. Dinilai langsung oleh ustad/pendeta/petugas yang memimpin kegiatan saat itu.',
                        ],
                        [
                            'bg'     => '#FAEEDA',
                            'border' => '#FAC775',
                            'title'  => '#633806',
                            'text'   => '#854F0B',
                            'label'  => 'Pasif',
                            'desc'   => 'Hadir secara fisik namun tidak aktif mengikuti kegiatan. Perlu perhatian lebih dari pembina.',
                        ],
                        [
                            'bg'     => '#FCEBEB',
                            'border' => '#F7C1C1',
                            'title'  => '#791F1F',
                            'text'   => '#A32D2D',
                            'label'  => 'Perlu pembinaan lanjutan',
                            'desc'   => 'Membutuhkan perhatian dan tindak lanjut khusus dari petugas pembinaan.',
                        ],
                    ] as $k)
                    <div class="panduan-status-card" style="padding:12px 14px;border-radius:10px;
                                 background:{{ $k['bg'] }};border:1px solid {{ $k['border'] }};">
                        <p class="panduan-status-label panduan-body-text" style="font-size:.78rem;font-weight:700;color:{{ $k['title'] }};margin:0 0 .3rem;">
                            {{ $k['label'] }}
                        </p>
                        <p class="panduan-status-desc panduan-body-text" style="font-size:.75rem;color:{{ $k['text'] }};line-height:1.6;margin:0;">
                            {{ $k['desc'] }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ══ KESIMPULAN ══ --}}
            <div x-data="{ open: false }" style="border-top:1px solid #e5e7eb;padding-top:1.5rem;">
                <button
                    type="button"
                    x-on:click="open = !open"
                    class="panduan-conclusion-toggle"
                    style="width:100%;text-align:left;border:1px solid #e5e7eb;border-radius:10px;
                           padding:12px 14px;background:#f8fafc;display:flex;align-items:center;
                           justify-content:space-between;gap:12px;cursor:pointer;margin-bottom:1rem;">
                    <span class="panduan-action-text" style="font-size:.7rem;font-weight:700;color:#9ca3af;letter-spacing:.1em;text-transform:uppercase;">
                        Kesimpulan
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                         stroke="#6b7280" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                         x-bind:style="open ? 'transform:rotate(180deg);transition:transform .2s;flex-shrink:0;' : 'transform:rotate(0deg);transition:transform .2s;flex-shrink:0;'">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>

                <div x-cloak x-show="open" x-transition.opacity.duration.150ms class="panduan-conclusion-panel"
                     style="display:none;">
                    <div style="display:flex;flex-direction:column;gap:.875rem;
                                 font-size:.85rem;color:#4b5563;line-height:1.8;">
                        <p class="panduan-body-text" style="margin:0;">
                            Sistem rapor ini bekerja seperti guru yang menilai siswa secara otomatis dan jujur —
                            tanpa subjektivitas, tanpa pilih kasih. Penilaian didasarkan pada dua hal yang tidak
                            bisa dimanipulasi: seberapa sering warga binaan hadir, dan seberapa sungguh-sungguh
                            ia berpartisipasi ketika hadir.
                        </p>
                        <p class="panduan-body-text" style="margin:0;">
                            Seseorang yang selalu hadir tapi tidak pernah aktif tidak akan mendapat nilai terbaik.
                            Sebaliknya, seseorang yang benar-benar hadir dan aktif berpartisipasi akan diakui
                            usahanya secara otomatis oleh sistem.
                        </p>
                        <p class="panduan-body-text" style="margin:0;">
                            Rapor dibuat sekali klik oleh petugas — sistem mengerjakan sisanya: mengumpulkan data,
                            menghitung, menilai, dan menyimpan untuk seluruh warga binaan aktif sekaligus.
                            Dan ketika petugas sudah mengesahkan sebuah rapor, sistem tidak akan mengganggunya,
                            karena dokumen resmi harus terlindungi dari perubahan yang tidak disengaja.
                        </p>
                        <div class="panduan-summary" style="padding:14px 18px;border-left:3px solid #1e3a5f;
                                     background:#eff6ff;border-radius:0 8px 8px 0;margin-top:.25rem;">
                            <p class="panduan-action-text" style="margin:0;font-size:.875rem;font-weight:700;color:#1e3a5f;">
                                Hadir saja tidak cukup — harus aktif. Dan sistem mencatat semuanya
                                secara adil dan otomatis.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end konten toggle utama --}}
    </div>{{-- end toggle utama --}}
</div>
</x-filament-widgets::widget>