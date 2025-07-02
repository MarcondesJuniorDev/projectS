<?php

namespace App\Filament\Resources\ServiceOrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ServiceTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ServicesRelationManager extends RelationManager
{
    protected static string $relationship = 'services';
    protected static ?string $label = 'Serviço';
    protected static ?string $title = 'Serviços';
    protected static ?string $icon = 'heroicon-o-cog';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('service_template_id')
                    ->label('Modelo de Serviço')
                    ->relationship('serviceTemplate', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable() // Pode ser um serviço não-template (ad-hoc)
                    ->live() // Para preencher descrição e preço automaticamente
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $template = ServiceTemplate::find($state);
                            if ($template) {
                                $set('description', $template->description);
                                $set('price', $template->standard_price);
                                $set('time_spent_hours', $template->estimated_time_hours);
                            }
                        }
                    }),

                Forms\Components\TextInput::make('description')
                    ->label('Descrição do Serviço')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Esta será a descrição final do serviço.'),

                Forms\Components\TextInput::make('price')
                    ->label('Preço (R$)')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->step(0.01)
                    ->default(0.00),

                Forms\Components\TextInput::make('time_spent_hours')
                    ->label('Tempo Gasto (Horas)')
                    ->numeric()
                    ->step(0.25)
                    ->nullable(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->label('Descrição do Serviço'),

                Tables\Columns\TextColumn::make('price')
                    ->money('BRL')
                    ->label('Preço'),

                Tables\Columns\TextColumn::make('time_spent_hours')
                    ->label('Tempo Gasto (Horas)'),

                Tables\Columns\TextColumn::make('serviceTemplate.name')
                    ->label('Baseado no Modelo')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
