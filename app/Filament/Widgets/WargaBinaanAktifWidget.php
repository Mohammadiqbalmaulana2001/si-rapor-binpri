<?php

namespace App\Filament\Widgets;

use App\Models\WargaBinaan as ModelsWargaBinaan;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\HtmlString;

class WargaBinaanAktifWidget extends TableWidget
{
    protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                ModelsWargaBinaan::query()
                    ->with(['absensis' => function ($query) {
                        $query->whereMonth('tanggal', now()->month)
                              ->whereYear('tanggal', now()->year);
                    }])
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('no_register')
                    ->label('📋 No. Register')
                    ->searchable()
                    ->copyable()
                    ->toggleable()
                    ->weight('medium')
                    ->color('gray')
                    ->copyMessage('Nomor register berhasil disalin')
                    ->copyMessageDuration(1500),
                    
                TextColumn::make('nama_lengkap')
                    ->label('👤 Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->icon('heroicon-o-user')
                    ->iconColor('primary'),
                    
                TextColumn::make('blok_kamar')
                    ->label('🏢 Blok / Kamar')
                    ->searchable()
                    ->badge()
                    ->color('info')
                    ->formatStateUsing(fn ($state): string => "🏠 {$state}"),
                    
                TextColumn::make('tanggal_masuk')
                    ->label('📅 Tanggal Masuk')
                    ->date('d M Y')
                    ->since()
                    ->sortable()
                    ->icon('heroicon-o-calendar')
                    ->iconColor('success')
                    ->tooltip(fn ($record): string => "Masuk: " . $record->tanggal_masuk->format('d F Y')),
                    
                TextColumn::make('sisa_masa_tahanan')
                    ->label('⏳ Sisa Masa Tahanan')
                    ->formatStateUsing(fn ($state): string => match(true) {
                        $state == 'Sudah Bebas' => '✅ ' . $state,
                        str_contains($state, 'hari') => '📆 ' . $state,
                        str_contains($state, 'bulan') => '🗓️ ' . $state,
                        default => $state ? '⏰ ' . $state : '—',
                    })
                    ->badge()
                    ->color(fn ($state): string => match(true) {
                        $state == 'Sudah Bebas' => 'success',
                        str_contains($state, 'hari') && (int)filter_var($state, FILTER_SANITIZE_NUMBER_INT) <= 30 => 'warning',
                        str_contains($state, 'hari') => 'info',
                        default => 'gray',
                    }),
                    
                TextColumn::make('persentase_kehadiran_bulan_ini')
                    ->label('📊 Kehadiran Bulan Ini')
                    ->formatStateUsing(function ($state) {
                        $persen = (float)$state;
                        $barColor = $persen >= 80 ? '#10b981' : ($persen >= 50 ? '#f59e0b' : '#ef4444');
                        $emoji = $persen >= 80 ? '🎯' : ($persen >= 50 ? '📈' : '⚠️');
                        
                        return new HtmlString("
                            <div style='display: flex; align-items: center; gap: 8px;'>
                                <div style='flex: 1;'>
                                    <div style='background: #e2e8f0; border-radius: 20px; height: 8px; overflow: hidden;'>
                                        <div style='width: {$persen}%; background: {$barColor}; height: 100%; border-radius: 20px;'></div>
                                    </div>
                                </div>
                                <div style='min-width: 55px; display: flex; align-items: center; gap: 4px;'>
                                    <span style='font-size: 12px;'>{$emoji}</span>
                                    <span style='font-size: 13px; font-weight: 600; color: {$barColor};'>{$persen}%</span>
                                </div>
                            </div>
                        ");
                    }),
                    
                TextColumn::make('status')
                    ->label('🔄 Status')
                    ->formatStateUsing(fn ($state): string => match($state) {
                        'aktif' => '✅ Aktif',
                        'pindah' => '🚚 Pindah',
                        'bebas' => '🎉 Bebas',
                        default => '❓ ' . ucfirst($state),
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success',
                        'pindah' => 'info',
                        'bebas' => 'gray',
                        default => 'warning',
                    }),
            ])
            ->defaultSort('tanggal_masuk', 'desc')
            ->searchable()
            ->paginated([10, 25, 50])
            ->emptyStateHeading('📭 Tidak ada data WBP')
            ->emptyStateDescription('Silakan tambahkan WBP baru dengan mengklik tombol di atas')
            ->emptyStateIcon('heroicon-o-user-group')
            ->striped()
            ->deferLoading()
            ->persistSearchInSession()
            ->persistSortInSession();
    }
}