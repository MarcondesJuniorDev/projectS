<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ServiceLocation;
use Filament\Resources\Resource;
use AnourValar\EloquentSerialize\Service;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ServiceLocationResource\Pages;
use App\Filament\Resources\ServiceLocationResource\RelationManagers;
use Filament\Forms\Components\Select;

class ServiceLocationResource extends Resource
{
    protected static ?string $model = ServiceLocation::class;
    protected static ?string $label = 'Localização';
    protected static ?string $pluralLabel = 'Localizações';
    protected static ?string $slug = 'localizacoes';
    protected static ?string $navigationGroup = 'Teste';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-map';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', titleAttribute: 'name')
                    ->searchable()
                    ->required()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->label('Nome da Localização')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull()
                    ->maxLength(255),

                Forms\Components\TextInput::make('address')
                    ->label('Endereço')
                    ->columnSpanFull()
                    ->maxLength(255),

                Forms\Components\TextInput::make('city')
                    ->label('Cidade')
                    ->maxLength(255),

                Forms\Components\TextInput::make('state')
                    ->label('Estado')
                    ->maxLength(2),

                Forms\Components\TextInput::make('zip_code')
                    ->label('CEP')
                    ->mask('99999-999')
                    ->placeholder('99999-999')
                    ->maxLength(10),

                Forms\Components\TextInput::make('phone')
                    ->label('Telefone')
                    ->mask('(99) 99999-9999')
                    ->placeholder('(22) 55555-4444')
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

                Forms\Components\Textarea::make('reference_point')
                    ->label('Ponto de Referência')
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('notes')
                    ->label('Observações')
                    ->maxLength(3000)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Cliente')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome da Localização')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('Cidade')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Telefone')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact_person_name')
                    ->label('Nome da Pessoa de Contato')
                    ->sortable()
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
                SelectFilter::make('client_id')
                    ->label('Cliente')
                    ->options(fn () => \App\Models\Client::all()->pluck('name', 'id'))
                    ->default(null)
                    ->placeholder('Todos'),

                SelectFilter::make('state')
                    ->label('Estado')
                    ->options(ServiceLocation::pluck('state', 'state'))
                    ->default(null)
                    ->placeholder('Todos'),
            ])->headerActions([
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
                Tables\Actions\DeleteAction::make()->label('Excluir'),
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
            'index' => Pages\ListServiceLocations::route('/'),
            'create' => Pages\CreateServiceLocation::route('/create'),
            'edit' => Pages\EditServiceLocation::route('/{record}/edit'),
        ];
    }
}
