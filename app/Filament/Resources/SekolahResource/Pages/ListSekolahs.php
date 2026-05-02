<?php

namespace App\Filament\Resources\SekolahResource\Pages;

use App\Filament\Imports\SekolahImporter;
use App\Filament\Resources\SekolahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSekolahs extends ListRecords
{
    protected static string $resource = SekolahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(SekolahImporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
