<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Usuário';
    protected static ?string $pluralLabel = 'Usuários';
    protected static ?string $navigationGroup = 'Gerenciamento de Acesso';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'usuarios';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nome Completo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->unique(ignoreRecord: true)
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context) => $context === 'create'),
                Forms\Components\TextInput::make('password_confirmation')
                    ->label('Confirmação de Senha')
                    ->password()
                    ->same('password')
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context) => $context === 'create'),
                Select::make('roles')
                    ->label('Funções')
                    ->multiple()
                    ->relationship('roles', 'name', fn (Builder $query)=>
                        auth()->user()->hasRole('Admin') ? $query : $query->where('name', '!=', 'Admin')
                    )
                    ->preload()
                    ->required(fn (string $context) => $context === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Nome Completo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado Em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado Em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->hasRole('Admin')
            ? parent::getEloquentQuery()
            : parent::getEloquentQuery()->whereHas('roles', function (Builder $query) {
                $query->where('name', '!=', 'Admin');
            });
    }
}
