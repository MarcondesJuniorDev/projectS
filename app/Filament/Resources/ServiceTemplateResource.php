<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceTemplateResource\Pages;
use App\Filament\Resources\ServiceTemplateResource\RelationManagers;
use App\Models\ServiceTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ServiceTemplateResource extends Resource
{
    protected static ?string $model = ServiceTemplate::class;
    protected static ?string $label = 'Modelo de Serviço';
    protected static ?string $pluralLabel = 'Modelos de Serviço';
    protected static ?string $slug = 'modelos-de-servico';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull()
                    ->label('Nome do Modelo'),

                Forms\Components\Textarea::make('description')
                    ->maxLength(3000)
                    ->columnSpanFull()
                    ->nullable()
                    ->label('Descrição do Modelo'),

                Forms\Components\TextInput::make('standard_price')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->step(0.01)
                    ->default(0.00)
                    ->label('Preço Padrão'),

                Forms\Components\TextInput::make('estimated_time_hours')
                    ->numeric()
                    ->step(0.25)
                    ->nullable()
                    ->label('Tempo Estimado (Horas)'),

                Forms\Components\Toggle::make('requires_materials')
                    ->label('Requer Materiais')
                    ->helperText('Se ativado, este modelo de serviço requer materiais para ser realizado.')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Modelo'),
                Tables\Columns\TextColumn::make('standard_price')
                    ->money('BRL')
                    ->sortable()
                    ->label('Preço Padrão'),

                Tables\Columns\TextColumn::make('estimated_time_hours')
                    ->label('Tempo Estimado (Horas)')
                    ->sortable(),

                Tables\Columns\IconColumn::make('requires_materials')
                    ->label('Requer Materiais')
                    ->boolean(),

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
                Tables\Filters\TernaryFilter::make('requires_materials')
                    ->label('Requer Materiais')
                    ->placeholder('Todos')
                    ->trueLabel('Sim')
                    ->falseLabel('Não'),
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
            'index' => Pages\ListServiceTemplates::route('/'),
            'create' => Pages\CreateServiceTemplate::route('/create'),
            'edit' => Pages\EditServiceTemplate::route('/{record}/edit'),
        ];
    }
}
