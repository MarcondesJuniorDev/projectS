<?php

namespace App\Filament\Resources\ServiceOrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Material;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materials';
    protected static ?string $label = 'Material';
    protected static ?string $title = 'Materiais';
    protected static ?string $icon = 'heroicon-o-wrench-screwdriver';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('material_id')
                    ->label('Material')
                    ->relationship('material', 'name')
                    ->getOptionLabelFromRecordUsing(fn (Material $record) => "{$record->name} (SKU: {$record->sku}, Stock: {$record->current_stock})")
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live() // Para atualizar o preço automaticamente
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $material = Material::find($state);
                            if ($material) {
                                $set('cost_price_at_use', $material->cost_price);
                            }
                        }
                    }),

                Forms\Components\TextInput::make('quantity_used')
                    ->label('Quantidade Usada')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
                    ->extraAttributes([
                        // Removido o wire:change para evitar erro de método inexistente
                    ])
                    ->rules([
                        fn (Forms\Get $get): \Closure => function ($value, \Closure $fail) use ($get) {
                            $materialId = $get('material_id');
                            if ($materialId) {
                                $material = Material::find($materialId);
                                if ($material && $value > $material->current_stock) {
                                    $fail("Estoque insuficiente. Apenas {$material->current_stock} unidades disponíveis.");
                                }
                            }
                        },
                    ]),

                Forms\Components\TextInput::make('cost_price_at_use')
                    ->label('Custo por Unidade (R$)')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->step(0.01)
                    ->readOnly() // Preço preenchido automaticamente, apenas leitura
                    ->helperText('Preenchido automaticamente a partir do preço de custo do material.'),
            ])->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('material.name')
            ->columns([
                Tables\Columns\TextColumn::make('material.name')
                    ->searchable()
                    ->sortable()
                    ->label('Material'),

                Tables\Columns\TextColumn::make('quantity_used')
                    ->label('Quantidade Usada'),

                Tables\Columns\TextColumn::make('cost_price_at_use')
                    ->money('BRL')
                    ->label('Custo (Unidade)'),

                Tables\Columns\TextColumn::make('total_cost')
                    ->getStateUsing(fn ($record) => $record->quantity_used * $record->cost_price_at_use)
                    ->money('BRL')
                    ->label('Custo Total'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->using(function (array $data, string $model): \Illuminate\Database\Eloquent\Model {
                        $data['service_order_id'] = $this->ownerRecord->id; // Define o service_order_id automaticamente

                        $material = Material::find($data['material_id']);
                        if ($material) {
                            $material->current_stock -= $data['quantity_used'];
                            $material->save();
                        }

                        return $model::create($data);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->using(function (\Illuminate\Database\Eloquent\Model $record, array $data, array $old): \Illuminate\Database\Eloquent\Model {
                        // Ao editar, ajustar o estoque
                        $oldQuantity = $old['quantity_used'];
                        $newQuantity = $data['quantity_used'];
                        $material = Material::find($data['material_id']);

                        if ($material) {
                            $material->current_stock += $oldQuantity; // Devolve a quantidade antiga
                            $material->current_stock -= $newQuantity; // Remove a nova quantidade
                            $material->save();
                        }
                        $record->update($data);
                        return $record;
                    }),
                Tables\Actions\DeleteAction::make()
                    ->using(function (\Illuminate\Database\Eloquent\Model $record) {
                        // Ao deletar, devolve ao estoque
                        $material = Material::find($record->material_id);
                        if ($material) {
                            $material->current_stock += $record->quantity_used;
                            $material->save();
                        }
                        $record->delete();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->using(function (\Illuminate\Database\Eloquent\Collection $records) {
                            foreach ($records as $record) {
                                $material = Material::find($record->material_id);
                                if ($material) {
                                    $material->current_stock += $record->quantity_used;
                                    $material->save();
                                }
                                $record->delete();
                            }
                        }),
                ]),
            ]);
    }
}
