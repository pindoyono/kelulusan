<?php

namespace App\Filament\Imports;

use App\Models\Sekolah;
use App\Models\Siswa;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class SiswaImporter extends Importer
{
    protected static ?string $model = Siswa::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nisn')
                ->label('NISN')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:20']),
            ImportColumn::make('nis')
                ->label('NIS')
                ->rules(['nullable', 'string', 'max:20']),
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('npsn_sekolah')
                ->label('NPSN Sekolah')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('jenis_kelamin')
                ->requiredMapping()
                ->rules(['required', 'in:L,P']),
            ImportColumn::make('kelas')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:20']),
            ImportColumn::make('jurusan')
                ->rules(['nullable', 'string', 'max:100']),
            ImportColumn::make('tempat_lahir')
                ->rules(['nullable', 'string', 'max:100']),
            ImportColumn::make('tanggal_lahir')
                ->rules(['nullable', 'date']),
        ];
    }

    public function resolveRecord(): ?Siswa
    {
        $sekolah = Sekolah::where('npsn', $this->data['npsn_sekolah'])->first();

        if (! $sekolah) {
            throw new \Exception("Sekolah dengan NPSN '{$this->data['npsn_sekolah']}' tidak ditemukan.");
        }

        $siswa = Siswa::firstOrNew([
            'nisn' => $this->data['nisn'],
        ]);

        $siswa->sekolah_id = $sekolah->id;

        return $siswa;
    }

    public function fillRecord(): void
    {
        unset($this->data['npsn_sekolah']);

        parent::fillRecord();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import siswa selesai. ' . number_format($import->successful_rows) . ' baris berhasil diimport.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal.';
        }

        return $body;
    }
}
