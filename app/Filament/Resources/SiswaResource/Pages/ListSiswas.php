<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Imports\SiswaImporter;
use App\Filament\Resources\SiswaResource;
use App\Models\Sekolah;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function (): StreamedResponse {
                    $user = auth()->user();

                    $query = Sekolah::where('is_active', true)->orderBy('nama');
                    if ($user?->sekolah_id) {
                        $query->where('id', $user->sekolah_id);
                    }
                    $sekolahs = $query->get();

                    return response()->streamDownload(function () use ($sekolahs) {
                        $writer = new Writer();
                        $writer->openToFile('php://output');

                        $writer->addRow(Row::fromValues([
                            'nisn', 'nis', 'nama', 'npsn_sekolah', 'jenis_kelamin', 'kelas', 'jurusan', 'tempat_lahir', 'tanggal_lahir',
                        ]));

                        foreach ($sekolahs as $sekolah) {
                            $writer->addRow(Row::fromValues([
                                '', '', '', $sekolah->npsn, '', '', '', '', '',
                            ]));
                        }

                        $writer->close();
                    }, 'template_import_siswa.xlsx', [
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]);
                }),
            Actions\ImportAction::make()
                ->importer(SiswaImporter::class)
                ->modalDescription(null),
            Actions\CreateAction::make(),
        ];
    }
}
