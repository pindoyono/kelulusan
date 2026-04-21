<?php

namespace App\Filament\Imports;

use App\Models\Sekolah;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class SekolahImporter extends Importer
{
    protected static ?string $model = Sekolah::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('SMAN 1 Malinau'),
            ImportColumn::make('npsn')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:20'])
                ->example('30401001'),
            ImportColumn::make('jenis')
                ->requiredMapping()
                ->rules(['required', 'in:SMA,SMK'])
                ->example('SMA'),
            ImportColumn::make('alamat')
                ->rules(['nullable', 'string', 'max:255'])
                ->example('Jl. Pendidikan No. 1'),
            ImportColumn::make('kota')
                ->rules(['nullable', 'string', 'max:255'])
                ->example('Malinau'),
            ImportColumn::make('is_active')
                ->boolean()
                ->rules(['nullable', 'boolean'])
                ->example('1'),
        ];
    }

    public function resolveRecord(): ?Sekolah
    {
        return Sekolah::firstOrNew([
            'npsn' => $this->data['npsn'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Import sekolah selesai. ' . number_format($import->successful_rows) . ' baris berhasil diimport.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' baris gagal.';
        }

        return $body;
    }
}
