<x-filament-widgets::widget>
    <x-filament::section>
        <div style="padding: 20px;">
            
            <!-- Header dengan gradient hidup -->
            <div style="background: linear-gradient(135deg, #f59e0b 0%, #ef4444 50%, #ec4899 100%); padding: 24px; border-radius: 28px; margin-bottom: 24px; color: white;">
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="font-size: 48px;">📅</div>
                        <div>
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                <span style="background: rgba(255,255,255,0.2); padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">Hari Ini</span>
                            </div>
                            <h2 style="font-size: 22px; font-weight: 700; margin: 0;">{{ $tanggal }}</h2>
                        </div>
                    </div>
                    <div style="background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); padding: 10px 20px; border-radius: 40px; text-align: center;">
                        <div style="font-size: 11px; opacity: 0.9;">Total Kegiatan</div>
                        <div style="font-size: 28px; font-weight: 800; line-height: 1.2;">{{ $kegiatans->count() }}</div>
                    </div>
                </div>
            </div>

            <!-- Daftar Kegiatan -->
            @if($kegiatans->count() > 0)
                <div class="kegiatan-grid">
                    <div style="display: grid; gap: 16px;">
                        @foreach($kegiatans as $index => $kegiatan)
                            @php
                                $gradients = [
                                    'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                                    'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                                    'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                                    'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                                    'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                                    'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)',
                                ];
                                $selectedGradient = $gradients[$index % count($gradients)];
                                $jamMulai = \Carbon\Carbon::parse($kegiatan->jam_mulai);
                                $jamSelesai = \Carbon\Carbon::parse($kegiatan->jam_selesai);
                                
                                // Status waktu (pagi/siang/sore/malam)
                                $hour = (int)$jamMulai->format('H');
                                if ($hour < 10) $waktuIcon = '🌅';
                                elseif ($hour < 14) $waktuIcon = '☀️';
                                elseif ($hour < 18) $waktuIcon = '🌤️';
                                else $waktuIcon = '🌙';
                            @endphp
                            
                            <div class="kegiatan-card" style="background: {{ $selectedGradient }}; border-radius: 24px; padding: 20px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15); transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer; position: relative; overflow: hidden;">
                                <!-- Decorative circles -->
                                <div style="position: absolute; top: -30px; right: -30px; width: 120px; height: 120px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                                <div style="position: absolute; bottom: -40px; left: -40px; width: 150px; height: 150px; background: rgba(255,255,255,0.08); border-radius: 50%;"></div>
                                
                                <div style="display: flex; flex-wrap: wrap; gap: 16px; position: relative; z-index: 1;">
                                    <!-- Waktu -->
                                    <div style="background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); padding: 12px 18px; border-radius: 20px; text-align: center; min-width: 100px;">
                                        <div style="font-size: 28px; margin-bottom: 4px;">{{ $waktuIcon }}</div>
                                        <div style="color: white; font-size: 20px; font-weight: 800;">{{ $jamMulai->format('H:i') }}</div>
                                        <div style="color: rgba(255,255,255,0.8); font-size: 12px;">- {{ $jamSelesai->format('H:i') }}</div>
                                    </div>
                                    
                                    <!-- Detail Kegiatan -->
                                    <div style="flex: 1;">
                                        <h3 style="font-size: 18px; font-weight: 700; color: white; margin: 0 0 8px 0;">
                                            {{ $kegiatan->nama_kegiatan }}
                                        </h3>
                                        
                                        <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 12px;">
                                            <div style="display: flex; align-items: center; gap: 6px;">
                                                <span style="font-size: 14px;">🏷️</span>
                                                <span style="color: rgba(255,255,255,0.9); font-size: 12px; font-weight: 500;">{{ ucfirst($kegiatan->kategori) }}</span>
                                            </div>
                                            <div style="display: flex; align-items: center; gap: 6px;">
                                                <span style="font-size: 14px;">🔄</span>
                                                <span style="color: rgba(255,255,255,0.9); font-size: 12px; font-weight: 500;">{{ $kegiatan->getFrekuensiHint() }}</span>
                                            </div>
                                        </div>
                                        
                                        @if($kegiatan->deskripsi)
                                            <p style="color: rgba(255,255,255,0.85); font-size: 13px; line-height: 1.5; margin: 0 0 12px 0;">
                                                {{ \Illuminate\Support\Str::limit($kegiatan->deskripsi, 120) }}
                                            </p>
                                        @endif
                                        
                                        <!-- Penanggung Jawab -->
                                        <div style="display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.15); padding: 6px 12px; border-radius: 20px; display: inline-flex;">
                                            <span style="font-size: 14px;">👤</span>
                                            <span style="color: white; font-size: 12px; font-weight: 500;">{{ $kegiatan->penanggung_jawab ?? 'Belum ditentukan' }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Icon panah -->
                                    <div style="align-self: center;">
                                        <div style="background: rgba(255,255,255,0.2); width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <span style="font-size: 20px;">→</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Progress time bar -->
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $start = $jamMulai;
                                    $end = $jamSelesai;
                                    $progress = 0;
                                    if ($now->between($start, $end)) {
                                        $total = $start->diffInMinutes($end);
                                        $elapsed = $start->diffInMinutes($now);
                                        $progress = ($elapsed / $total) * 100;
                                    } elseif ($now->gt($end)) {
                                        $progress = 100;
                                    }
                                @endphp
                                @if($progress > 0 && $progress < 100)
                                    <div style="margin-top: 16px; position: relative; z-index: 1;">
                                        <div style="background: rgba(0,0,0,0.2); border-radius: 10px; height: 4px; overflow: hidden;">
                                            <div style="width: {{ $progress }}%; background: rgba(255,255,255,0.8); height: 100%; border-radius: 10px; transition: width 0.5s ease;"></div>
                                        </div>
                                        <div style="text-align: right; margin-top: 4px;">
                                            <span style="color: rgba(255,255,255,0.7); font-size: 10px;">Sedang berlangsung</span>
                                        </div>
                                    </div>
                                @elseif($progress >= 100)
                                    <div style="margin-top: 16px; position: relative; z-index: 1;">
                                        <div style="background: rgba(0,0,0,0.2); border-radius: 10px; height: 4px; overflow: hidden;">
                                            <div style="width: 100%; background: rgba(255,255,255,0.5); height: 100%; border-radius: 10px;"></div>
                                        </div>
                                        <div style="text-align: right; margin-top: 4px;">
                                            <span style="color: rgba(255,255,255,0.7); font-size: 10px;">✅ Selesai</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 28px; padding: 60px 20px; text-align: center;">
                    <div style="font-size: 64px; margin-bottom: 16px;">📭</div>
                    <h3 style="font-size: 20px; font-weight: 600; color: #475569; margin: 0 0 8px 0;">Tidak Ada Kegiatan</h3>
                    <p style="font-size: 14px; color: #64748b; margin: 0;">Tidak ada kegiatan yang dijadwalkan untuk hari ini</p>
                </div>
            @endif

            <!-- Styles -->
            <style>
                /* Hover effect untuk cards */
                .kegiatan-card:hover {
                    transform: translateY(-6px) !important;
                    box-shadow: 0 20px 35px -10px rgba(0,0,0,0.3) !important;
                }
                
                /* Responsive Grid */
                @media (min-width: 1024px) {
                    .kegiatan-grid > div {
                        grid-template-columns: repeat(2, 1fr) !important;
                        gap: 20px !important;
                    }
                }
                
                @media (min-width: 768px) and (max-width: 1023px) {
                    .kegiatan-grid > div {
                        grid-template-columns: repeat(2, 1fr) !important;
                        gap: 18px !important;
                    }
                    .kegiatan-card {
                        padding: 16px !important;
                    }
                    .kegiatan-card div[style*="font-size: 20px"] {
                        font-size: 18px !important;
                    }
                    .kegiatan-card h3 {
                        font-size: 16px !important;
                    }
                }
                
                @media (max-width: 767px) {
                    .kegiatan-grid > div {
                        grid-template-columns: repeat(1, 1fr) !important;
                        gap: 16px !important;
                    }
                    .kegiatan-card {
                        padding: 16px !important;
                    }
                    .kegiatan-card div[style*="min-width: 100px"] {
                        min-width: 80px !important;
                        padding: 8px 12px !important;
                    }
                    .kegiatan-card div[style*="font-size: 20px"] {
                        font-size: 16px !important;
                    }
                    .kegiatan-card div[style*="font-size: 28px"] {
                        font-size: 24px !important;
                    }
                    .kegiatan-card h3 {
                        font-size: 15px !important;
                    }
                    /* Header mobile */
                    div[style*="padding: 24px"] {
                        padding: 16px !important;
                    }
                    div[style*="font-size: 48px"] {
                        font-size: 36px !important;
                    }
                    h2[style*="font-size: 22px"] {
                        font-size: 18px !important;
                    }
                }
                
                /* Dark Mode */
                @media (prefers-color-scheme: dark) {
                    .kegiatan-card {
                        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.4) !important;
                    }
                    div[style*="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%)"] {
                        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;
                    }
                    div[style*="color: #475569"] {
                        color: #e2e8f0 !important;
                    }
                    div[style*="color: #64748b"] {
                        color: #94a3b8 !important;
                    }
                }
            </style>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>