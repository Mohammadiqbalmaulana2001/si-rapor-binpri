<x-filament-widgets::widget>
    <x-filament::section>
        <div style="padding: 20px;">
            
            <!-- Header dengan gradient hidup -->
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; flex-wrap: wrap; gap: 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); padding: 24px; border-radius: 28px; color: white;">
                <div>
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                        <div>
                            <h2 style="font-size: 18px; font-weight: 500; margin: 0; opacity: 0.9;">Rekap Kehadiran</h2>
                            <p style="font-size: 24px; font-weight: 700; margin: 4px 0 0 0;">{{ $bulan }}</p>
                        </div>
                    </div>
                </div>
                <div style="background: rgba(255,255,255,0.25); backdrop-filter: blur(10px); padding: 14px 28px; border-radius: 60px; text-align: center; border: 1px solid rgba(255,255,255,0.3);">
                    <div style="font-size: 13px; opacity: 0.9;">Total Absensi</div>
                    <div style="font-size: 36px; font-weight: 800; line-height: 1.2;">{{ array_sum($statusKehadiran) }}</div>
                </div>
            </div>

            <!-- Chart Bars Section -->
            <div style="margin-bottom: 36px;">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 16px;">
                    @foreach($mingguan as $index => $persen)
                        @php
                            if ($persen >= 80) {
                                $barColor = 'linear-gradient(180deg, #10b981 0%, #059669 100%)';
                                $glowColor = 'rgba(16,185,129,0.5)';
                                $badgeColor = '#10b981';
                            } elseif ($persen >= 50) {
                                $barColor = 'linear-gradient(180deg, #f59e0b 0%, #d97706 100%)';
                                $glowColor = 'rgba(245,158,11,0.5)';
                                $badgeColor = '#f59e0b';
                            } else {
                                $barColor = 'linear-gradient(180deg, #ef4444 0%, #dc2626 100%)';
                                $glowColor = 'rgba(239,68,68,0.5)';
                                $badgeColor = '#ef4444';
                            }
                            $height = min(max($persen * 3.5, 35), 180);
                        @endphp
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                            <div style="width: 100%; background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%); border-radius: 24px; overflow: hidden; height: 200px; position: relative; box-shadow: 0 6px 12px -4px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
                                <div style="width: 100%; background: {{ $barColor }}; border-radius: 24px 24px 0 0; height: {{ $height }}px; transition: height 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55); position: absolute; bottom: 0; left: 0; box-shadow: 0 -6px 16px {{ $glowColor }};">
                                    <div style="position: absolute; top: -28px; left: 50%; transform: translateX(-50%); background: {{ $badgeColor }}; color: white; font-size: 12px; font-weight: bold; padding: 5px 12px; border-radius: 30px; white-space: nowrap; box-shadow: 0 4px 12px rgba(0,0,0,0.25);">
                                        {{ $persen }}%
                                    </div>
                                </div>
                                <div style="position: absolute; bottom: 12px; left: 0; right: 0; text-align: center; font-size: 13px; font-weight: 700; color: #475569;">
                                    Minggu {{ $index + 1 }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Stats Ringkasan - Desktop 4 kolom, Tablet 2 kolom, Mobile 1 kolom -->
            <div class="stats-grid-container">
                <div style="display: grid; gap: 20px;">
                    @php
                        $total = array_sum($statusKehadiran);
                        $cards = [
                            'Hadir' => [
                                'gradient' => 'linear-gradient(135deg, #10b981 0%, #34d399 100%)',
                                'shadow' => '0 12px 24px -8px rgba(16,185,129,0.4)',
                                'icon' => '✅',
                                'icon_bg' => 'rgba(255,255,255,0.2)'
                            ],
                            'Izin' => [
                                'gradient' => 'linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%)',
                                'shadow' => '0 12px 24px -8px rgba(245,158,11,0.4)',
                                'icon' => '📝',
                                'icon_bg' => 'rgba(255,255,255,0.2)'
                            ],
                            'Sakit' => [
                                'gradient' => 'linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%)',
                                'shadow' => '0 12px 24px -8px rgba(59,130,246,0.4)',
                                'icon' => '🤒',
                                'icon_bg' => 'rgba(255,255,255,0.2)'
                            ],
                            'Alpha' => [
                                'gradient' => 'linear-gradient(135deg, #ef4444 0%, #f87171 100%)',
                                'shadow' => '0 12px 24px -8px rgba(239,68,68,0.4)',
                                'icon' => '❌',
                                'icon_bg' => 'rgba(255,255,255,0.2)'
                            ],
                        ];
                    @endphp
                    @foreach($statusKehadiran as $status => $jumlah)
                        @php
                            $persen = $total > 0 ? round(($jumlah / $total) * 100, 1) : 0;
                            $card = $cards[$status];
                        @endphp
                        <div class="stat-card" style="background: {{ $card['gradient'] }}; border-radius: 28px; padding: 22px; box-shadow: {{ $card['shadow'] }}; transition: transform 0.3s ease, box-shadow 0.3s ease; cursor: pointer; position: relative; overflow: hidden; height: 100%; min-height: 180px;">
                            <div style="position: absolute; top: -30px; right: -30px; width: 100px; height: 100px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
                            <div style="position: absolute; bottom: -40px; left: -40px; width: 140px; height: 140px; background: rgba(255,255,255,0.06); border-radius: 50%;"></div>
                            
                            <div style="display: flex; flex-direction: column; justify-content: space-between; height: 100%; position: relative; z-index: 1;">
                                <div>
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="background: {{ $card['icon_bg'] }}; padding: 10px; border-radius: 20px; backdrop-filter: blur(4px);">
                                                <span style="font-size: 24px;">{{ $card['icon'] }}</span>
                                            </div>
                                            <span style="color: white; font-size: 15px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">{{ $status }}</span>
                                        </div>
                                        <div style="background: rgba(255,255,255,0.2); padding: 6px 14px; border-radius: 40px;">
                                            <span style="color: white; font-size: 13px; font-weight: 700;">{{ $persen }}%</span>
                                        </div>
                                    </div>
                                    
                                    <div style="font-size: 52px; font-weight: 800; color: white; line-height: 1; margin-bottom: 12px;">
                                        {{ number_format($jumlah) }}
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div style="margin-top: 16px;">
                                    <div style="background: rgba(0,0,0,0.25); border-radius: 12px; height: 8px; overflow: hidden;">
                                        <div style="width: {{ $persen }}%; background: white; height: 100%; border-radius: 12px; transition: width 1s ease;"></div>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-top: 8px;">
                                        <span style="color: rgba(255,255,255,0.8); font-size: 11px;">0%</span>
                                        <span style="color: rgba(255,255,255,0.8); font-size: 11px;">50%</span>
                                        <span style="color: rgba(255,255,255,0.8); font-size: 11px;">100%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Styles untuk Responsive Grid -->
            <style>
                /* Desktop: 4 kolom (>= 1280px) */
                .stats-grid-container > div {
                    grid-template-columns: repeat(4, 1fr);
                }
                
                /* Desktop Medium: 4 kolom (1024px - 1279px) */
                @media (min-width: 1024px) and (max-width: 1279px) {
                    .stats-grid-container > div {
                        grid-template-columns: repeat(4, 1fr);
                        gap: 16px !important;
                    }
                    .stat-card {
                        padding: 18px !important;
                        min-height: 170px !important;
                    }
                    .stat-card div[style*="font-size: 52px"] {
                        font-size: 42px !important;
                    }
                }
                
                /* Tablet: 2 kolom (768px - 1023px) */
                @media (min-width: 768px) and (max-width: 1023px) {
                    .stats-grid-container > div {
                        grid-template-columns: repeat(2, 1fr) !important;
                        gap: 18px !important;
                    }
                    .stat-card {
                        padding: 20px !important;
                        min-height: 175px !important;
                    }
                    .stat-card div[style*="font-size: 52px"] {
                        font-size: 46px !important;
                    }
                    /* Chart grid tablet */
                    div[style*="grid-template-columns: repeat(4, 1fr)"] {
                        gap: 16px !important;
                    }
                    div[style*="height: 200px"] {
                        height: 170px !important;
                    }
                }
                
                /* Mobile: 1 kolom (< 768px) */
                @media (max-width: 767px) {
                    .stats-grid-container > div {
                        grid-template-columns: repeat(1, 1fr) !important;
                        gap: 16px !important;
                    }
                    .stat-card {
                        padding: 18px !important;
                        min-height: 160px !important;
                    }
                    .stat-card div[style*="font-size: 52px"] {
                        font-size: 40px !important;
                    }
                    /* Chart grid mobile */
                    div[style*="grid-template-columns: repeat(4, 1fr)"] {
                        gap: 12px !important;
                    }
                    div[style*="height: 200px"] {
                        height: 140px !important;
                    }
                    div[style*="top: -28px"] {
                        top: -24px !important;
                    }
                    div[style*="font-size: 12px"] {
                        font-size: 10px !important;
                        padding: 4px 8px !important;
                    }
                    div[style*="font-size: 13px"] {
                        font-size: 11px !important;
                    }
                    /* Header mobile */
                    div[style*="padding: 24px"] {
                        padding: 16px !important;
                    }
                    div[style*="font-size: 36px"] {
                        font-size: 28px !important;
                    }
                    div[style*="padding: 14px 28px"] {
                        padding: 10px 20px !important;
                    }
                    div[style*="font-size: 36px"] {
                        font-size: 28px !important;
                    }
                }
                
                /* Hover effect untuk cards */
                .stat-card:hover {
                    transform: translateY(-6px) !important;
                    box-shadow: 0 20px 30px -12px rgba(0,0,0,0.35) !important;
                }
                
                /* Dark Mode */
                @media (prefers-color-scheme: dark) {
                    div[style*="background: linear-gradient(180deg, #f8fafc 0%, #e2e8f0 100%)"] {
                        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%) !important;
                    }
                    div[style*="color: #475569"] {
                        color: #94a3b8 !important;
                    }
                }
            </style>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>