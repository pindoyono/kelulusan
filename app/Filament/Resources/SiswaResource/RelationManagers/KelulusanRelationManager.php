<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Actions;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class KelulusanRelationManager extends RelationManager
{
    protected static string $relationship = 'kelulusan';

    protected static ?string $title = 'Data Kelulusan';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('pengumuman_id')
                    ->relationship('pengumuman', 'judul')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('status')
                    ->options([
                        'lulus' => 'Lulus',
                        'tidak_lulus' => 'Tidak Lulus',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('nilai_rata_rata')
                    ->label('Nilai Rata-rata')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01),
                Forms\Components\Textarea::make('keterangan')
                    ->maxLength(500),
                Forms\Components\FileUpload::make('skl_path')
                    ->label('File SKL (PDF)')
                    ->disk('public')
                    ->directory('skl')
                    ->acceptedFileTypes(['application/pdf'])
                    ->maxSize(2048)
                    ->getUploadedFileNameForStorageUsing(function ($file) {
                        $nisn = $this->ownerRecord->nisn ?? 'unknown';
                        return "skl_{$nisn}.pdf";
                    })
                    ->downloadable()
                    ->openable()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pengumuman.tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lulus' => 'success',
                        'tidak_lulus' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'lulus' => 'LULUS',
                        'tidak_lulus' => 'TIDAK LULUS',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('nilai_rata_rata')
                    ->label('Nilai Rata-rata')
                    ->sortable(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->limit(30),
                Tables\Columns\IconColumn::make('skl_path')
                    ->label('SKL')
                    ->icon(fn ($state) => $state ? 'heroicon-o-document-check' : 'heroicon-o-document-minus')
                    ->color(fn ($state) => $state ? 'success' : 'gray'),
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
