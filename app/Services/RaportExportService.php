<?php

namespace App\Services;

use App\Models\Raport;
use App\Filament\Resources\Raports\Schemas\RaportInfolist;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class RaportExportService
{
    // ══════════════════════════════════════════════════
    // PDF
    // ══════════════════════════════════════════════════

    public function exportPdf(Raport $raport): \Symfony\Component\HttpFoundation\Response
    {
        $detail = RaportInfolist::getDetailKegiatan($raport);
        $wb     = $raport->wargaBinaan;

        $totalSesi  = collect($detail)->sum('total');
        $totalHadir = collect($detail)->sum('hadir');
        $persen     = $totalSesi > 0 ? round($totalHadir / $totalSesi * 100, 1) : 0;

        $pdf = Pdf::loadView('exports.raport-pdf', [
            'raport'     => $raport,
            'wb'         => $wb,
            'detail'     => $detail,
            'totalSesi'  => $totalSesi,
            'totalHadir' => $totalHadir,
            'persen'     => $persen,
        ])->setPaper('a4', 'landscape');

        $filename = 'Raport_' . str_replace(' ', '_', $wb->nama_lengkap)
            . '_' . $raport->nama_bulan
            . '_' . $raport->tahun
            . '.pdf';

        return $pdf->download($filename);
    }

    // ══════════════════════════════════════════════════
    // EXCEL
    // ══════════════════════════════════════════════════

    public function exportExcel(Raport $raport): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $detail = RaportInfolist::getDetailKegiatan($raport);
        $wb     = $raport->wargaBinaan;

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Raport Pembinaan');

        // ── Warna header ──
        $navyHex  = '1E3A5F';
        $grayHex  = 'F3F4F6';
        $greenHex = 'DCFCE7';
        $redHex   = 'FEE2E2';

        // ── Helper style apply ──
        $applyStyle = function (string $range, array $style) use ($sheet) {
            $sheet->getStyle($range)->applyFromArray($style);
        };

        // ────────────────────────────────────────
        // BLOK IDENTITAS (baris 1-7)
        // ────────────────────────────────────────
        $identitas = [
            ['RAPORT PEMBINAAN', ''],
            ['Nama WBP',         $wb->nama_lengkap],
            ['No. Register',     $wb->no_register],
            ['Agama',            $wb->agama ?? '—'],
            ['Blok / Kamar',     $wb->blok_kamar ?? '—'],
            ['Periode',          $raport->nama_bulan . ' ' . $raport->tahun],
            ['Status',           $raport->is_finalized ? 'Sudah Difinalisasi' : 'Belum Difinalisasi'],
        ];

        foreach ($identitas as $r => $row) {
            $sheet->setCellValue('A' . ($r + 1), $row[0]);
            $sheet->setCellValue('B' . ($r + 1), $row[1]);
        }

        // Merge & style judul
        $sheet->mergeCells('A1:L1');
        $applyStyle('A1', [
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $navyHex]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        // Style label identitas
        $applyStyle('A2:A7', [
            'font' => ['bold' => true, 'color' => ['rgb' => $navyHex]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $grayHex]],
        ]);

        // ────────────────────────────────────────
        // RINGKASAN (baris 9)
        // ────────────────────────────────────────
        $totalSesi   = collect($detail)->sum('total');
        $totalHadir  = collect($detail)->sum('hadir');
        $totalTidak  = collect($detail)->sum('tidak_hadir');
        $totalIzin   = collect($detail)->sum('izin');
        $totalSakit  = collect($detail)->sum('sakit');
        $totalAktif  = collect($detail)->sum('aktif');
        $totalPasif  = collect($detail)->sum('pasif');
        $totalPerlu  = collect($detail)->sum('perlu');
        $persen      = $totalSesi > 0 ? round($totalHadir / $totalSesi * 100, 1) : 0;

        $sheet->setCellValue('A9', 'RINGKASAN KEHADIRAN');
        $sheet->mergeCells('A9:L9');
        $applyStyle('A9', [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $navyHex]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $summaryLabels = ['Total Sesi', 'Hadir', 'Tdk Hadir', 'Izin', 'Sakit', 'Aktif', 'Pasif', 'Perlu PB', 'Kehadiran %'];
        $summaryValues = [$totalSesi, $totalHadir, $totalTidak, $totalIzin, $totalSakit, $totalAktif, $totalPasif, $totalPerlu, $persen . '%'];
        $sumCols       = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        foreach ($summaryLabels as $i => $label) {
            $col = $sumCols[$i];
            $sheet->setCellValue($col . '10', $label);
            $sheet->setCellValue($col . '11', $summaryValues[$i]);
            $applyStyle($col . '10', [
                'font'      => ['bold' => true, 'size' => 9],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'E5E7EB']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
            ]);
            $applyStyle($col . '11', [
                'font'      => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']]],
            ]);
        }

        // ────────────────────────────────────────
        // HEADER TABEL DETAIL (baris 13)
        // ────────────────────────────────────────
        $sheet->setCellValue('A13', 'DETAIL PER KEGIATAN');
        $sheet->mergeCells('A13:L13');
        $applyStyle('A13', [
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $navyHex]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $headers = [
            'A' => 'Nama Kegiatan',
            'B' => 'Kategori',
            'C' => 'Frekuensi',
            'D' => 'Total Sesi',
            'E' => 'Hadir',
            'F' => 'Tdk Hadir',
            'G' => 'Izin',
            'H' => 'Sakit',
            'I' => 'Aktif',
            'J' => 'Pasif',
            'K' => 'Perlu PB',
            'L' => 'Kehadiran %',
        ];

        foreach ($headers as $col => $label) {
            $sheet->setCellValue($col . '14', $label);
        }

        $applyStyle('A14:L14', [
            'font'      => ['bold' => true, 'size' => 9, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => '374151']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '4B5563']]],
        ]);

        // ────────────────────────────────────────
        // DATA ROWS (mulai baris 15)
        // ────────────────────────────────────────
        $startRow = 15;
        foreach ($detail as $i => $row) {
            $r   = $startRow + $i;
            $bg  = $i % 2 === 0 ? 'FFFFFF' : 'F9FAFB';

            $sheet->setCellValue('A' . $r, $row['nama_kegiatan']);
            $sheet->setCellValue('B' . $r, $row['kategori']);
            $sheet->setCellValue('C' . $r, ucfirst($row['frekuensi']));
            $sheet->setCellValue('D' . $r, $row['total'] ?: 0);
            $sheet->setCellValue('E' . $r, $row['hadir']);
            $sheet->setCellValue('F' . $r, $row['tidak_hadir']);
            $sheet->setCellValue('G' . $r, $row['izin']);
            $sheet->setCellValue('H' . $r, $row['sakit']);
            $sheet->setCellValue('I' . $r, $row['aktif']);
            $sheet->setCellValue('J' . $r, $row['pasif']);
            $sheet->setCellValue('K' . $r, $row['perlu']);
            $sheet->setCellValue('L' . $r, $row['persen'] / 100);

            $sheet->getStyle('L' . $r)
                ->getNumberFormat()
                ->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

            $applyStyle('A' . $r . ':L' . $r, [
                'fill'    => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $bg]],
                'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'E5E7EB']]],
            ]);

            $applyStyle('D' . $r . ':L' . $r, [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);

            // Warna sel Hadir (hijau) dan Tdk Hadir (merah)
            if ($row['hadir'] > 0) {
                $applyStyle('E' . $r, ['font' => ['color' => ['rgb' => '166534']], 'font' => ['bold' => true, 'color' => ['rgb' => '166534']]]);
            }
            if ($row['tidak_hadir'] > 0) {
                $applyStyle('F' . $r, ['font' => ['bold' => true, 'color' => ['rgb' => '991B1B']]]);
            }
            if ($row['perlu'] > 0) {
                $applyStyle('K' . $r, ['font' => ['bold' => true, 'color' => ['rgb' => 'DC2626']]]);
            }

            // Warna % berdasarkan nilai
            $persenColor = match(true) {
                $row['persen'] >= 75 => '16A34A',
                $row['persen'] >= 60 => 'D97706',
                $row['total'] === 0  => '9CA3AF',
                default              => 'DC2626',
            };
            $applyStyle('L' . $r, ['font' => ['bold' => true, 'color' => ['rgb' => $persenColor]]]);
        }

        // ────────────────────────────────────────
        // FOOTER TOTAL
        // ────────────────────────────────────────
        $footerRow = $startRow + count($detail);
        $sheet->setCellValue('A' . $footerRow, 'TOTAL KESELURUHAN');
        $sheet->mergeCells('A' . $footerRow . ':C' . $footerRow);
        $sheet->setCellValue('D' . $footerRow, $totalSesi);
        $sheet->setCellValue('E' . $footerRow, $totalHadir);
        $sheet->setCellValue('F' . $footerRow, $totalTidak);
        $sheet->setCellValue('G' . $footerRow, $totalIzin);
        $sheet->setCellValue('H' . $footerRow, $totalSakit);
        $sheet->setCellValue('I' . $footerRow, $totalAktif);
        $sheet->setCellValue('J' . $footerRow, $totalPasif);
        $sheet->setCellValue('K' . $footerRow, $totalPerlu);
        $sheet->setCellValue('L' . $footerRow, $persen / 100);

        $sheet->getStyle('L' . $footerRow)
            ->getNumberFormat()
            ->setFormatCode(NumberFormat::FORMAT_PERCENTAGE_00);

        $applyStyle('A' . $footerRow . ':L' . $footerRow, [
            'font'      => ['bold' => true, 'color' => ['rgb' => $navyHex]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'DBEAFE']],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => $navyHex]]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getStyle('A' . $footerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // ────────────────────────────────────────
        // CATATAN & REKOMENDASI (setelah tabel)
        // ────────────────────────────────────────
        $noteRow = $footerRow + 2;
        $sheet->setCellValue('A' . $noteRow, 'Rekomendasi:');
        $sheet->setCellValue('B' . $noteRow, match($raport->rekomendasi) {
            'sangat_baik' => 'Sangat Baik — Layak Remisi / PB',
            'baik'        => 'Baik',
            'cukup'       => 'Cukup',
            'kurang'      => 'Kurang — Perlu Pembinaan Intensif',
            default       => '—',
        });
        $sheet->mergeCells('B' . $noteRow . ':L' . $noteRow);

        $sheet->setCellValue('A' . ($noteRow + 1), 'Catatan Petugas:');
        $sheet->setCellValue('B' . ($noteRow + 1), $raport->catatan_petugas ?? '—');
        $sheet->mergeCells('B' . ($noteRow + 1) . ':L' . ($noteRow + 1));
        $sheet->getStyle('B' . ($noteRow + 1))->getAlignment()->setWrapText(true);
        $sheet->getRowDimension($noteRow + 1)->setRowHeight(40);

        $applyStyle('A' . $noteRow . ':A' . ($noteRow + 1), [
            'font' => ['bold' => true, 'color' => ['rgb' => $navyHex]],
        ]);

        // ────────────────────────────────────────
        // LEBAR KOLOM
        // ────────────────────────────────────────
        $colWidths = [
            'A' => 32, 'B' => 12, 'C' => 12, 'D' => 10,
            'E' => 8,  'F' => 10, 'G' => 8,  'H' => 8,
            'I' => 8,  'J' => 8,  'K' => 10, 'L' => 14,
        ];
        foreach ($colWidths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // Freeze panes pada header detail
        $sheet->freezePane('A15');

        // ────────────────────────────────────────
        // OUTPUT
        // ────────────────────────────────────────
        $filename = 'Raport_' . str_replace(' ', '_', $wb->nama_lengkap)
            . '_' . $raport->nama_bulan
            . '_' . $raport->tahun
            . '.xlsx';

        $tempPath = tempnam(sys_get_temp_dir(), 'raport_excel_');

        if ($tempPath === false) {
            abort(500, 'Gagal menyiapkan file Excel sementara.');
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet, $writer);

        return response()->download($tempPath, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ])->deleteFileAfterSend(true);
    }
}