<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectNameResource\Pages;
use App\Filament\Resources\ProjectNameResource\RelationManagers;
use App\Models\ProjectName;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action as TableAction;

class ProjectNameResource extends Resource
{
    protected static ?string $model = ProjectName::class;

    protected static ?string $navigationIcon = 'heroicon-m-briefcase';
    protected static ?string $label = 'Projects';
    protected static ?string $pluralLabel = 'Projects';
    protected static ?string $navigationLabel = 'Projects';
    protected static ?string $navigationGroup = 'Project Management';

    protected static bool $shouldRegisterNavigation = false; 
   
    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('partner')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('pic')
                    ->required()
                    ->options(User::pluck('name', 'id')->toArray())
                    ->reactive(),
                Forms\Components\Select::make('team')
                    ->required()
                    ->options(User::pluck('name', 'id')->toArray())
                    ->reactive()
                    ->multiple(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('partner')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pic')
                    ->getStateUsing(fn ($record) => User::find($record->pic)?->name) // Ambil nama user berdasarkan ID
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('team')
                    ->getStateUsing(function ($record) {
                        // Ambil data array langsung dari properti 'team'
                        $teamIds = $record->team; // Sudah berupa array karena di-cast
                        $userNames = User::whereIn('id', $teamIds)->pluck('name')->toArray();
                
                        // Gabungkan nama-nama dengan koma
                        return !empty($userNames) ? implode(', ', $userNames) : '-';
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                
                TableAction::make('Task Board')
                ->label('Task Board')
                // ->icon('heroicon-m-rectangle-group')
                ->url(fn (ProjectName $record) => url('admin/board') . '?projectId=' . $record->id)                
                ->color('primary'),


                TableAction::make('Task list')
                ->label('Task list')
                // ->icon('heroicon-m-list-bullet')
                ->url(fn (ProjectName $record) => url('admin/projects') . '?projectId=' . $record->id)
                ->color('primary'),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjectNames::route('/'),
            'create' => Pages\CreateProjectName::route('/create'),
            'edit' => Pages\EditProjectName::route('/{record}/edit'),
        ];
    }
}
