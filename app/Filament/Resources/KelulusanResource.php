<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelulusanResource\Pages;
use App\Models\Kelulusan;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use BackedEnum;

class KelulusanResource extends Resource
{
    protected static ?string $model = Kelulusan::class;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string | UnitEnum | null $navigationGroup = 'Pengumuman';

    protected static ?string $navigationLabel = 'Kelulusan';

    protected static ?string $modelLabel = 'Kelulusan';

    protected static ?string $pluralModelLabel = 'Kelulusan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\Select::make('siswa_id')
                    ->relationship(
                        'siswa',
                        'nama',
                        modifyQueryUsing: function (Builder $query) {
                            $user = auth()->user();
                            if ($user?->sekolah_id) {
                                $query->where('sekolah_id', $user->sekolah_id);
                            }
                        }
                    )
                    ->required()
                    ->searchable()
                    ->preload(),
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
                    ->getUploadedFileNameForStorageUsing(function ($file, $get) {
                        $siswa = \App\Models\Siswa::find($get('siswa_id'));
                        $nisn = $siswa?->nisn ?? 'unknown';
                        return "skl_{$nisn}.pdf";
                    })
                    ->downloadable()
                    ->openable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $user = auth()->user();
                if ($user?->sekolah_id) {
                    $query->whereHas('siswa', fn (Builder $q) => $q->where('sekolah_id', $user->sekolah_id));
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nisn')
                    ->label('NISN')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('siswa.sekolah.nama')
                    ->label('Sekolah')
                    ->searchable(),
                Tables\Columns\TextColumn::make('siswa.kelas')
                    ->label('Kelas'),
                Tables\Columns\TextColumn::make('pengumuman.tahun_ajaran')
                    ->label('Tahun Ajaran'),
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
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'lulus' => 'Lulus',
                        'tidak_lulus' => 'Tidak Lulus',
                    ]),
                Tables\Filters\SelectFilter::make('pengumuman_id')
                    ->relationship('pengumuman', 'judul')
                    ->label('Pengumuman'),
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
            'index' => Pages\ListKelulusans::route('/'),
            'create' => Pages\CreateKelulusan::route('/create'),
            'edit' => Pages\EditKelulusan::route('/{record}/edit'),
        ];
    }
}
