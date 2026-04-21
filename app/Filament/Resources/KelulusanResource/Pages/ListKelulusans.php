<?php

namespace App\Filament\Resources\KelulusanResource\Pages;

use App\Filament\Imports\KelulusanImporter;
use App\Filament\Resources\KelulusanResource;
use App\Models\Kelulusan;
use App\Models\Siswa;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Writer\XLSX\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;
use ZipArchive;

class ListKelulusans extends ListRecords
{
    protected static string $resource = KelulusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function (): StreamedResponse {
                    $user = auth()->user();

                    $query = Siswa::with('sekolah')->orderBy('nama');
                    if ($user?->sekolah_id) {
                        $query->where('sekolah_id', $user->sekolah_id);
                    }
                    $siswas = $query->get();

                    return response()->streamDownload(function () use ($siswas) {
                        $writer = new Writer();
                        $writer->openToFile('php://output');

                        $writer->addRow(Row::fromValues([
                            'nisn', 'nama_siswa', 'sekolah', 'tahun_ajaran', 'status', 'nilai_rata_rata', 'keterangan',
                        ]));

                        foreach ($siswas as $siswa) {
                            $writer->addRow(Row::fromValues([
                                $siswa->nisn,
                                $siswa->nama,
                                $siswa->sekolah?->nama ?? '',
                                '2025/2026',
                                'lulus',
                                '',
                                '',
                            ]));
                        }

                        $writer->close();
                    }, 'template_import_kelulusan.xlsx', [
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    ]);
                }),
            Actions\Action::make('uploadSklMassal')
                ->label('Upload SKL Massal')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->form([
                    Forms\Components\FileUpload::make('zip_file')
                        ->label('File ZIP berisi SKL (PDF)')
                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                        ->maxSize(51200)
                        ->required()
                        ->helperText('Upload file ZIP berisi file PDF yang dinamai sesuai NISN (contoh: 0051234501.pdf)'),
                ])
                ->action(function (array $data): void {
                    $zipPath = Storage::disk('local')->path($data['zip_file']);

                    $zip = new ZipArchive();
                    if ($zip->open($zipPath) !== true) {
                        Notification::make()
                            ->title('Gagal membuka file ZIP')
                            ->danger()
                            ->send();
                        return;
                    }

                    $user = auth()->user();
                    $success = 0;
                    $skipped = [];

                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $filename = $zip->getNameIndex($i);

                        if (! str_ends_with(strtolower($filename), '.pdf')) {
                            continue;
                        }

                        $nisn = pathinfo(basename($filename), PATHINFO_FILENAME);

                        $query = Siswa::where('nisn', $nisn);
                        if ($user?->sekolah_id) {
                            $query->where('sekolah_id', $user->sekolah_id);
                        }
                        $siswa = $query->first();

                        if (! $siswa) {
                            $skipped[] = "{$filename} (NISN tidak ditemukan)";
                            continue;
                        }

                        $kelulusan = Kelulusan::where('siswa_id', $siswa->id)->first();

                        if (! $kelulusan) {
                            $skipped[] = "{$filename} (data kelulusan belum ada)";
                            continue;
                        }

                        $content = $zip->getFromIndex($i);
                        $storagePath = "skl/skl_{$nisn}.pdf";
                        Storage::disk('public')->put($storagePath, $content);

                        $kelulusan->update(['skl_path' => $storagePath]);
                        $success++;
                    }

                    $zip->close();
                    Storage::disk('local')->delete($data['zip_file']);

                    $message = "{$success} file SKL berhasil diupload.";
                    if (count($skipped) > 0) {
                        $message .= ' ' . count($skipped) . ' file dilewati: ' . implode(', ', array_slice($skipped, 0, 5));
                        if (count($skipped) > 5) {
                            $message .= '... dan ' . (count($skipped) - 5) . ' lainnya';
                        }
                    }

                    Notification::make()
                        ->title('Upload SKL Massal Selesai')
                        ->body($message)
                        ->when($success > 0 && count($skipped) === 0, fn ($n) => $n->success())
                        ->when($success > 0 && count($skipped) > 0, fn ($n) => $n->warning())
                        ->when($success === 0, fn ($n) => $n->danger())
                        ->persistent()
                        ->send();
                }),
            Actions\ImportAction::make()
                ->importer(KelulusanImporter::class)
                ->modalDescription(null),
            Actions\CreateAction::make(),
        ];
    }
}
