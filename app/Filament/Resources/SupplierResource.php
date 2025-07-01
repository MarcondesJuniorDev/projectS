<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;
    protected static ?string $label = 'Fornecedor';
    protected static ?string $pluralLabel = 'Fornecedores';
    protected static ?string $slug = 'fornecedores';
    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull()
                    ->label('Nome da Empresa'),

                Forms\Components\TextInput::make('corporate_name')
                    ->nullable()
                    ->maxLength(255)
                    ->label('Nome Fantasia'),

                Forms\Components\TextInput::make('cnpj')
                    ->maxLength(18)
                    ->unique(ignoreRecord: true)
                    ->mask('99.999.999/9999-99')
                    ->placeholder('99.999.999/9999-99')
                    ->nullable()
                    ->label('CNPJ'),

                Forms\Components\TextInput::make('state_registration')
                    ->maxLength(20)
                    ->nullable()
                    ->label('Inscrição Estadual'),

                Forms\Components\TextInput::make('address')
                    ->columnSpanFull()
                    ->nullable()
                    ->maxLength(255)
                    ->label('Endereço'),

                Forms\Components\TextInput::make('city')
                    ->maxLength(255)
                    ->nullable()
                    ->label('Cidade'),

                Forms\Components\TextInput::make('state')
                    ->maxLength(2)
                    ->nullable()
                    ->label('Estado (UF)')
                    ->helperText('Use a sigla do estado, por exemplo: AM, PA, RR')
                    ->placeholder('AM'),

                Forms\Components\TextInput::make('zip_code')
                    ->maxLength(10)
                    ->mask('99999-999')
                    ->placeholder('99999-999')
                    ->nullable()
                    ->label('CEP'),

                Forms\Components\TextInput::make('phone')
                    ->maxLength(20)
                    ->tel()
                    ->nullable()
                    ->label('Telefone'),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->nullable()
                    ->label('E-mail'),

                Forms\Components\TextInput::make('contact_person_name')
                    ->maxLength(255)
                    ->nullable()
                    ->label('Nome do Contato'),

                Forms\Components\TextInput::make('contact_person_role')
                    ->maxLength(255)
                    ->nullable()
                    ->label('Cargo do Contato'),

                Forms\Components\TextInput::make('contact_person_phone')
                    ->tel()
                    ->maxLength(15)
                    ->nullable()
                    ->label('Telefone do Contato'),

                Forms\Components\Textarea::make('products_services_offered')
                    ->label('Produtos/Serviços Oferecidos')
                    ->maxLength(2000)
                    ->nullable()
                    ->columnSpanFull(),

                Forms\Components\Select::make('payment_terms')
                    ->label('Condições de Pagamento')
                    ->options([
                        'avista' => 'À vista',
                        '30dias' => '30 dias',
                        '60dias' => '60 dias',
                        '90dias' => '90 dias',
                        'parcelado' => 'Parcelado',
                        'outros' => 'Outros',
                    ])
                    ->required()
                    ->columnSpanFull(),

                Forms\Components\Textarea::make('notes')
                    ->label('Observações')
                    ->maxLength(3000)
                    ->nullable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->searchable()
                    ->sortable()
                    ->label('Nome da Empresa'),

                Tables\Columns\TextColumn::make('cnpj')
                    ->searchable()
                    ->sortable()
                    ->label('CNPJ'),

                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->label('Telefone'),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('E-mail'),

                Tables\Columns\TextColumn::make('contact_person_name')
                    ->searchable()
                    ->label('Nome do Contato'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('city')
                    ->label('Cidade')
                    ->options(fn () => Supplier::query()->distinct()->pluck('city', 'city')->filter()->toArray()),

                Tables\Filters\SelectFilter::make('state')
                    ->label('Estado')
                    ->options(fn () => Supplier::query()->distinct()->pluck('state', 'state')->filter()->toArray()),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
