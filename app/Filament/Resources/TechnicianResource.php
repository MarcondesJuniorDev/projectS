<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TechnicianResource\Pages;
use App\Filament\Resources\TechnicianResource\RelationManagers;
use App\Models\Technician;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TechnicianResource extends Resource
{
    protected static ?string $model = Technician::class;
    protected static ?string $label = 'Técnico';
    protected static ?string $pluralLabel = 'Técnicos';
    protected static ?string $slug = 'tecnicos';
    protected static ?string $navigationGroup = 'Teste';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    const SPECIALITY_TRANSLATIONS = [
        'Electrical' => 'Elétrica',
        'Plumbing' => 'Hidráulica',
        'HVAC' => 'Climatização',
        'Carpentry' => 'Marcenaria',
        'General Maintenance' => 'Manutenção Geral',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Usuário')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->placeholder('Selecione um usuário(opcional)')
                    ->required(fn (string $context) => $context === 'create'),

                Forms\Components\TextInput::make('full_name')
                    ->label('Nome Completo')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cpf')
                    ->label('CPF')
                    ->mask('999.999.999-99')
                    ->placeholder('999.999.999-99')
                    ->required(fn (string $context) => $context === 'create')
                    ->maxLength(14)
                    ->disabled(fn (string $context) => $context === 'edit'),
                Forms\Components\TextInput::make('rg')
                    ->label('RG')
                    ->mask('9999999-9')
                    ->placeholder('9999999-9')
                    ->maxLength(20),
                Forms\Components\TextInput::make('phone')
                    ->label('Telefone')
                    ->mask('(99) 99999-9999')
                    ->placeholder('(99) 99999-9999')
                    ->tel()
                    ->maxLength(15),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\Select::make('specialities')
                    ->label('Especialidades')
                    ->multiple()
                    ->options([
                        'eletrica' => 'Elétrica',
                        'hidraulica' => 'Hidráulica',
                        'climatizacao' => 'Climatização',
                        'marcenaria' => 'Marcenaria',
                        'manutencao_geral' => 'Manutenção Geral',
                    ]),
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                        'on_leave' => 'Em licença',
                    ])
                    ->default('inactive')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->label('Observações')
                    ->nullable()
                    ->maxLength(3000)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable()
                    ->sortable()
                    ->label('Nome Completo'),

                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable()
                    ->label('Usuário'),

                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->label('Telefone'),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('Email'),

                Tables\Columns\TextColumn::make('specialities')
                    ->label('Especialidades')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_array($state) ? collect($state)->map(fn ($item) => sprintf('<span class="badge">%s</span>', self::SPECIALITY_TRANSLATIONS[$item] ?? $item))->implode(' ') : $state)
                    ->html()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                        'on_leave' => 'Em licença',
                    ])
                    ->label('Status'),

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
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Ativo',
                        'inactive' => 'Inativo',
                        'on_leave' => 'Em licença',
                    ])
                    ->default(null)
                    ->placeholder('Todos')
                    ->label('Status'),
                Tables\Filters\SelectFilter::make('specialities')
                    ->label('Especialidades')
                    ->options([
                        'eletrica' => 'Elétrica',
                        'hidraulica' => 'Hidráulica',
                        'climatizacao' => 'Climatização',
                        'marcenaria' => 'Marcenaria',
                        'manutencao_geral' => 'Manutenção Geral',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value']) && !empty($data['value'])) {
                            return $query->whereJsonContains('specialities', $data['value']);
                        }
                        return $query;
                    })
                    ->label('Especialidades')
            ])->headerActions([
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
            'index' => Pages\ListTechnicians::route('/'),
            'create' => Pages\CreateTechnician::route('/create'),
            'edit' => Pages\EditTechnician::route('/{record}/edit'),
        ];
    }
}
