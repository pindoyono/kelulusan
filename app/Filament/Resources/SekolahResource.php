<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SekolahResource\Pages;
use App\Models\Sekolah;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use BackedEnum;

class SekolahResource extends Resource
{
    protected static ?string $model = Sekolah::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-building-library';

    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Sekolah';

    protected static ?string $modelLabel = 'Sekolah';

    protected static ?string $pluralModelLabel = 'Sekolah';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('npsn')
                    ->label('NPSN')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20),
                Forms\Components\Select::make('jenis')
                    ->options([
                        'SMA' => 'SMA',
                        'SMK' => 'SMK',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('alamat')
                    ->maxLength(255),
                Forms\Components\TextInput::make('kota')
                    ->maxLength(100),
                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                if ($user?->sekolah_id) {
                    $query->where('id', $user->sekolah_id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('npsn')
                    ->label('NPSN')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('jenis')
                    ->colors([
                        'primary' => 'SMA',
                        'success' => 'SMK',
                    ]),
                Tables\Columns\TextColumn::make('kota')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('siswas_count')
                    ->counts('siswas')
                    ->label('Jumlah Siswa'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis')
                    ->options([
                        'SMA' => 'SMA',
                        'SMK' => 'SMK',
                    ]),
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

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSekolahs::route('/'),
            'create' => Pages\CreateSekolah::route('/create'),
            'edit' => Pages\EditSekolah::route('/{record}/edit'),
        ];
    }
}
