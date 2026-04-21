<?php

namespace App\Filament\Imports;

use App\Models\Kelulusan;
use App\Models\Pengumuman;
use App\Models\Siswa;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class KelulusanImporter extends Importer
{
    protected static ?string $model = Kelulusan::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nisn')
                ->label('NISN Siswa')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('nama_siswa')
                ->label('Nama Siswa (referensi)')
                ->ignoreBlankState()
                ->rules([]),
            ImportColumn::make('sekolah')
                ->label('Sekolah (referensi)')
                ->ignoreBlankState()
                ->rules([]),
            ImportColumn::make('tahun_ajaran')
                ->label('Tahun Ajaran Pengumuman')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['required', 'in:lulus,tidak_lulus']),
            ImportColumn::make('nilai_rata_rata')
                ->label('Nilai Rata-rata')
                ->numeric(decimalPlaces: 2)
                ->rules(['nullable', 'numeric', 'min:0', 'max:100']),
            ImportColumn::make('keterangan')
                ->rules(['nullable', 'string', 'max:500']),
        ];
    }

    public function resolveRecord(): ?Kelulusan
    {
        $siswa = Siswa::where('nisn', $this->data['nisn'])->first();

        if (! $siswa) {
            throw new \Exception("Siswa dengan NISN '{$this->data['nisn']}' tidak ditemukan.");
        }

        $pengumuman = Pengumuman::where('tahun_ajaran', $this->data['tahun_ajaran'])->first();

        if (! $pengumuman) {
            throw new \Exception("Pengumuman dengan tahun ajaran '{$this->data['tahun_ajaran']}' tidak ditemukan.");
        }

        $kelulusan = Kelulusan::firstOrNew([
            'siswa_id' => $siswa->id,
            'pengumuman_id' => $pengumuman->id,
        ]);

        return $kelulusan;
    }

    public function fillRecord(): void
    {
        $siswa = Siswa::where('nisn', $this->data['nisn'])->first();
        $pengumuman = Pengumuman::where('tahun_ajaran', $this->data['tahun_ajaran'])->first();

        $this->record->siswa_id = $siswa->id;
        $this->record->pengumuman_id = $pengumuman->id;
        $this->record->status = $this->data['status'];
        $this->record->nilai_rata_rata = $this->data['nilai_rata_rata'] ?? null;
        $this->record->keterangan = $this->data['keterangan'] ?? null;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import kelulusan selesai. ' . number_format($import->successful_rows) . ' baris berhasil diimport.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal.';
        }

        return $body;
    }
}
