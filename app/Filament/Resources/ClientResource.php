<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;
    protected static ?string $label = 'Cliente';
    protected static ?string $pluralLabel = 'Clientes';
    protected static ?string $slug = 'clientes';
    protected static ?string $navigationGroup = 'Teste';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nome')
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\TextInput::make('corporate_name')
                    ->label('Razão Social')
                    ->maxLength(255),
                Forms\Components\TextInput::make('cnpj')
                    ->label('CNPJ')
                    ->unique(ignoreRecord: true)
                    ->mask('99.999.999/9999-99')
                    ->maxLength(18),
                Forms\Components\TextInput::make('state_registration')
                    ->label('Inscrição Estadual')
                    ->maxLength(20),
                Forms\Components\TextInput::make('address')
                    ->label('Endereço')
                    ->columnSpanFull()
                    ->maxLength(255),
                Forms\Components\TextInput::make('city')
                    ->label('Cidade')
                    ->maxLength(255),
                Forms\Components\TextInput::make('state')
                    ->label('Estado')
                    ->placeholder('AM')
                    ->maxLength(2),
                Forms\Components\TextInput::make('zip_code')
                    ->label('CEP')
                    ->mask('99999-999')
                    ->placeholder('99999-999')
                    ->maxLength(10),
                Forms\Components\TextInput::make('phone')
                    ->label('Telefone')
                    ->mask('(99) 99999-9999')
                    ->placeholder('(99) 99999-9999')
                    ->tel()
                    ->maxLength(20),
                Forms\Components\TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_person_name')
                    ->label('Nome da Pessoa de Contato')
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_person_phone')
                    ->label('Telefone da Pessoa de Contato')
                    ->mask('(99) 99999-9999')
                    ->placeholder('(99) 99999-9999')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('contact_person_email')
                    ->label('E-mail da Pessoa de Contato')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('notes')
                    ->label('Observações')
                    ->maxLength(length: 3000),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Empresa')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cnpj')
                    ->label('CNPJ')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_person_name')
                    ->label('Nome da Pessoa de Contato')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('state')
                    ->label('Estado')
                    ->options(Client::pluck('state', 'state'))
                    ->default(null)
                    ->placeholder('Todos'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
