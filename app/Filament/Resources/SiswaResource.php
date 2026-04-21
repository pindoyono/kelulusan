<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Models\Siswa;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use BackedEnum;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-user-group';

    protected static string | UnitEnum | null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Siswa';

    protected static ?string $modelLabel = 'Siswa';

    protected static ?string $pluralModelLabel = 'Siswa';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('sekolah_id')
                    ->relationship('sekolah', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->default(fn () => auth()->user()?->sekolah_id)
                    ->disabled(fn () => auth()->user()?->sekolah_id !== null)
                    ->dehydrated(),
                Forms\Components\TextInput::make('nisn')
                    ->label('NISN')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(20),
                Forms\Components\TextInput::make('nis')
                    ->label('NIS')
                    ->maxLength(20),
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('jenis_kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('kelas')
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('jurusan')
                    ->maxLength(100),
                Forms\Components\TextInput::make('tempat_lahir')
                    ->maxLength(100),
                Forms\Components\DatePicker::make('tanggal_lahir'),
                Forms\Components\FileUpload::make('foto')
                    ->image()
                    ->directory('siswa-foto')
                    ->maxSize(2048),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                if ($user?->sekolah_id) {
                    $query->where('sekolah_id', $user->sekolah_id);
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sekolah.nama')
                    ->label('Sekolah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kelas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jurusan'),
                Tables\Columns\BadgeColumn::make('jenis_kelamin')
                    ->colors([
                        'primary' => 'L',
                        'danger' => 'P',
                    ])
                    ->formatStateUsing(fn (string $state): string => $state === 'L' ? 'Laki-laki' : 'Perempuan'),
                Tables\Columns\TextColumn::make('kelulusan.status')
                    ->label('Status Kelulusan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'lulus' => 'success',
                        'tidak_lulus' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'lulus' => 'LULUS',
                        'tidak_lulus' => 'TIDAK LULUS',
                        default => 'Belum Ditentukan',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sekolah_id')
                    ->relationship('sekolah', 'nama')
                    ->label('Sekolah'),
                Tables\Filters\SelectFilter::make('jenis_kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
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
        return [
            SiswaResource\RelationManagers\KelulusanRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}
