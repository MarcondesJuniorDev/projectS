<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Models\Material;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;
    protected static ?string $label = 'Material';
    protected static ?string $pluralLabel = 'Materiais';
    protected static ?string $navigationGroup = 'Gerenciamento de Estoque';
    protected static ?int $navigationSort = 1;
    protected static ?string $slug = 'materiais';
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->label('Nome do Material'),

                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->unique(ignoreRecord: true)
                    ->nullable()
                    ->maxLength(255),

                Forms\Components\Select::make('unit_of_measurement')
                    ->required()
                    ->options([
                        'unit' => 'Unidade',
                        'box' => 'Caixa',
                        'roll' => 'Rolo',
                        'kg' => 'Quilograma',
                        'liter' => 'Litro',
                        'meter' => 'Metro',
                        'piece' => 'Peça',
                        'other' => 'Outro',
                    ])
                    ->default('unit')
                    ->label('Unidade de Medida'),

                Forms\Components\TextInput::make('cost_price')
                    ->required()
                    ->numeric()
                    ->prefix('R$')
                    ->step(0.01)
                    ->default(0.00)
                    ->label('Preço de Custo'),

                Forms\Components\TextInput::make('min_stock_level')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->default(0)
                    ->label('Nível Mínimo de Estoque'),

                Forms\Components\TextInput::make('max_stock_level')
                    ->numeric()
                    ->integer()
                    ->nullable()
                    ->label('Nível Máximo de Estoque'),

                Forms\Components\TextInput::make('current_stock')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->default(0)
                    ->label('Estoque Atual'),

                Forms\Components\TextInput::make('location_in_warehouse')
                    ->maxLength(255)
                    ->nullable()
                    ->label('Localização no Armazém'),

                Forms\Components\Textarea::make('description')
                    ->nullable()
                    ->maxLength(3000)
                    ->label('Descrição')
                    ->columnSpanFull(),


            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Material'),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),

                Tables\Columns\TextColumn::make('unit_of_measurement')
                    ->label('Unidade de Medida')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'unit' => 'Unidade',
                        'box' => 'Caixa',
                        'roll' => 'Rolo',
                        'kg' => 'Quilograma',
                        'liter' => 'Litro',
                        'meter' => 'Metro',
                        'piece' => 'Peça',
                        'other' => 'Outro',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('cost_price')
                    ->money('BRL', true)
                    ->label('Preço de Custo')
                    ->sortable(),

                Tables\Columns\TextColumn::make('current_stock')
                    ->sortable()
                    ->color(fn (int $state, Material $record): string => match (true) {
                        $state <= $record->min_stock_level => 'danger',
                        $state < ($record->min_stock_level * 2) => 'warning',
                        default => 'success',
                    })
                    ->label('Estoque Atual'),



                Tables\Columns\TextColumn::make('min_stock_level')
                    ->label('Nível Mínimo de Estoque')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('location_in_warehouse')
                    ->searchable()
                    ->label('Localização no Armazém'),

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
                Tables\Filters\SelectFilter::make('unit_of_measurement')
                    ->label('Unidade de Medida')
                    ->options([
                        'unit' => 'Unidade',
                        'box' => 'Caixa',
                        'roll' => 'Rolo',
                        'kg' => 'Quilograma',
                        'liter' => 'Litro',
                        'meter' => 'Metro',
                        'piece' => 'Peça',
                        'other' => 'Outro',
                    ])
                    ->default(null)
                    ->placeholder('Todas'),

                Tables\Filters\Filter::make('low_stock')
                    ->query(fn (Builder $query): Builder => $query->whereColumn('current_stock', '<=', 'min_stock_level'))
                    ->label('Low Stock Only')
                    ->toggle(),
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}
