<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static function generateRandomPassword(): string
    {
        return Str::random(12);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    TextInput::make('email')
                        ->required()
                        ->email()
                        ->unique(User::class, 'email')
                        ->maxLength(255),
                    TextInput::make('location')
                        ->nullable()
                        ->maxLength(255),
                    Forms\Components\Hidden::make('password')
                        ->default('12345678')
                        ->dehydrated(fn($state) => filled($state)),
                ]),
                
                Forms\Components\Section::make('Roles')
                    ->schema([
                        Forms\Components\CheckboxList::make('roles')
                            ->relationship('roles', 'name', function (Builder $query) {
                                if (!auth()->user()->hasRole('super_admin')) {
                                    $query->where('name', '!=', 'super_admin');
                                }
                            })
                            ->searchable()
                            ->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                if (!auth()->user()->hasRole('super_admin')) {
                    $query->whereDoesntHave('roles', function ($q) {
                        $q->where('name', 'super_admin');
                    });
                }
            })
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->badge()
                    ->color('success')
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->searchable(),
                Tables\Columns\BooleanColumn::make('email_verified_at')
                    ->label('Verified')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->relationship('roles', 'name', function (Builder $query) {
                        if (!auth()->user()->hasRole('super_admin')) {
                            $query->where('name', '!=', 'super_admin');
                        }
                    })
                    ->multiple()
                    ->preload()
            ])
            ->filtersTriggerAction(
                fn(\Filament\Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit User')
                    ->form([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->required()
                                ->email()
                                ->unique(User::class, 'email', ignoreRecord: true)
                                ->maxLength(255),
                            TextInput::make('location')
                                ->nullable()
                                ->maxLength(255),
                            Forms\Components\Hidden::make('password')
                                ->default('12345678')
                                ->dehydrated(fn($state) => filled($state)),
                        ]),
                        
                        Forms\Components\Section::make('Roles')
                            ->schema([
                                Forms\Components\CheckboxList::make('roles')
                                    ->relationship('roles', 'name', function (Builder $query) {
                                        if (!auth()->user()->hasRole('super_admin')) {
                                            $query->where('name', '!=', 'super_admin');
                                        }
                                    })
                                    ->searchable()
                                    ->columns(3),
                            ]),
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalIcon('heroicon-o-trash')
                    ->modalHeading(fn($record) => 'Delete User: ' . $record->name)
                    ->modalDescription('Are you sure you\'d like to delete this user? This cannot be undone.')
                    ->modalSubmitActionLabel('Yes, delete it'),
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

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
        ];
    }
}
