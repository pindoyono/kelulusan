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
                ->rules(['nullable'])
                ->castStateUsing(function (?string $state): ?string {
                    if (blank($state)) {
                        return null;
                    }

                    $state = trim($state);

                    $months = [
                        'JANUARI' => '01', 'FEBRUARI' => '02', 'MARET' => '03',
                        'APRIL' => '04', 'MEI' => '05', 'JUNI' => '06',
                        'JULI' => '07', 'AGUSTUS' => '08', 'SEPTEMBER' => '09',
                        'OKTOBER' => '10', 'NOVEMBER' => '11', 'DESEMBER' => '12',
                    ];

                    $upper = strtoupper($state);
                    foreach ($months as $indo => $num) {
                        if (str_contains($upper, $indo)) {
                            $upper = str_replace($indo, $num, $upper);
                            break;
                        }
                    }

                    try {
                        return \Carbon\Carbon::createFromFormat('d m Y', trim($upper))->format('Y-m-d');
                    } catch (\Exception) {
                        $timestamp = strtotime($state);
                        return $timestamp ? date('Y-m-d', $timestamp) : null;
                    }
                }),
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
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal. Klik "Download Failed Rows" untuk melihat detail error.';
        }

        return $body;
    }

    public function getValidationMessages(): array
    {
        return [
            'nisn.required' => 'Kolom NISN wajib diisi.',
            'nisn.max' => 'NISN maksimal 20 karakter.',
            'nama.required' => 'Kolom Nama wajib diisi.',
            'npsn_sekolah.required' => 'Kolom NPSN Sekolah wajib diisi.',
            'jenis_kelamin.required' => 'Kolom Jenis Kelamin wajib diisi.',
            'jenis_kelamin.in' => 'Jenis Kelamin harus L (Laki-laki) atau P (Perempuan).',
            'kelas.required' => 'Kolom Kelas wajib diisi.',
        ];
    }

    public function getValidationAttributes(): array
    {
        return [
            'nisn' => 'NISN',
            'nis' => 'NIS',
            'nama' => 'Nama',
            'npsn_sekolah' => 'NPSN Sekolah',
            'jenis_kelamin' => 'Jenis Kelamin',
            'kelas' => 'Kelas',
            'jurusan' => 'Jurusan',
            'tempat_lahir' => 'Tempat Lahir',
            'tanggal_lahir' => 'Tanggal Lahir',
        ];
    }
}
