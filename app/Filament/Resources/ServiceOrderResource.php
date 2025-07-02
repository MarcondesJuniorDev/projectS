<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ServiceOrder;
use Filament\Resources\Resource;
use function Laravel\Prompts\search;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ServiceOrderResource\RelationManagers\MaterialsRelationManager;
use App\Filament\Resources\ServiceOrderResource\RelationManagers\ServicesRelationManager;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ServiceOrderResource\Pages;


class ServiceOrderResource extends Resource
{
    protected static ?string $model = ServiceOrder::class;
    protected static ?string $label = 'Ordem de Serviço';
    protected static ?string $pluralLabel = 'Ordens de Serviço';
    protected static ?string $slug = 'ordens-de-servico';
    protected static ?string $navigationGroup = 'Teste';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detalhes da Ordem de Serviço')
                    ->description('Preencha os detalhes da ordem de serviço')
                    ->schema([
                        Forms\Components\TextInput::make('os_number')
                            ->label('Número da OS')
                            ->placeholder(function ($state) {
                                // Se já existe um número, mostra ele
                                if ($state) {
                                    return $state;
                                }
                                // Gera um número temporário para exibir no formulário
                                $lastOs = ServiceOrder::orderByDesc('id')->first();
                                $nextNumber = $lastOs ? ((int)$lastOs->os_number + 1) : 1;
                                return str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
                            })
                            ->default(function () {
                                $lastOs = ServiceOrder::orderByDesc('id')->first();
                                $nextNumber = $lastOs ? ((int)$lastOs->os_number + 1) : 1;
                                return str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
                            })
                            ->unique(ignoreRecord: true)
                            ->readOnly()
                            ->helperText('Este campo é gerado automaticamente e não pode ser editado.'),

                        Forms\Components\Select::make('client_id')
                            ->label('Cliente')
                            ->relationship('client', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Selecione um cliente')
                            ->reactive(),

                        Forms\Components\Select::make('service_location_id')
                            ->label('Localização do Serviço')
                            ->relationship('serviceLocation', 'name', fn (Builder $query, Forms\Get $get) =>
                                $query->where('client_id', $get('client_id')))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Selecione uma localização')
                            ->hidden(fn (Forms\Get $get) => !$get('client_id')),

                        Forms\Components\Select::make('priority')
                            ->label('Prioridade')
                            ->options([
                                'baixa' => 'Baixa',
                                'media' => 'Média',
                                'alta' => 'Alta',
                                'critica' => 'Crítica',
                            ])
                            ->required()
                            ->default('media'),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'aberto' => 'Aberto',
                                'em_analise' => 'Em Análise',
                                'aguardando_materiais' => 'Aguardando Materiais',
                                'em_andamento' => 'Em Andamento',
                                'aguardando_aprovacao' => 'Aguardando Aprovação',
                                'concluido' => 'Concluído',
                                'cancelado' => 'Cancelado',
                                'reaberto' => 'Reaberto'
                            ])
                            ->default('aberto')
                            ->live()
                            ->required()
                            ->afterStateUpdated(function (string $state, Forms\Set $set) {
                                if ($state === 'concluido') {
                                    $set('completed_at', now());
                                } else {
                                    $set('completed_at', null);
                                }
                            }),

                        Forms\Components\Textarea::make('problem_description')
                            ->label('Descrição do Problema')
                            ->required()
                            ->maxLength(3000)
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Informações do Solicitante')
                    ->description('Preencha as informações do solicitante')
                    ->schema([
                        Forms\Components\TextInput::make('requester_name')
                            ->label('Nome do Solicitante')
                            ->nullable()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('requester_phone')
                            ->label('Telefone do Solicitante')
                            ->tel()
                            ->mask('(99) 99999-9999')
                            ->placeholder('(99) 99999-9999')
                            ->nullable()
                            ->maxLength(20),
                        Forms\Components\TextInput::make('requester_email')
                            ->label('E-mail do Solicitante')
                            ->email()
                            ->nullable()
                            ->maxLength(255),
                    ])->columns(3),

                Forms\Components\Section::make('Informações do Serviço')
                    ->description('Preencha as informações do serviço')
                    ->schema([
                        Forms\Components\Select::make('assigned_technician_id')
                            ->label('Técnico Responsável')
                            ->relationship('assignedTechnician', 'full_name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->placeholder('Selecione um técnico'),

                        Forms\Components\DateTimePicker::make('scheduled_for')
                            ->label('Agendado para')
                            ->nullable(),

                        Forms\Components\DateTimePicker::make('started_at')
                            ->label('Iniciado em')
                            ->nullable(),

                        Forms\Components\DateTimePicker::make('completed_at')
                            ->label('Concluído em')
                            ->nullable()
                            ->readOnly()
                            ->helperText('Este campo é preenchido automaticamente quando o status é alterado para "Concluído".'),

                    ])->columns(2),

                Forms\Components\Section::make('Informações Adicionais')
                    ->description('Preencha as informações adicionais')
                    ->schema([
                        Forms\Components\Textarea::make('service_performed_description')
                            ->label('Descrição do Serviço Realizado')
                            ->nullable()
                            ->maxLength(3000)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('time_spent_hours')
                            ->label('Tempo Gasto (Horas)')
                            ->numeric()
                            ->step(0.25)
                            ->minValue(0)
                            ->nullable(),

                        Forms\Components\FileUpload::make('customer_signature_path')
                            ->label('Assinatura do Cliente')
                            ->image()
                            ->nullable()
                            ->maxSize(1024)
                            ->directory('customer_signatures'),

                        Forms\Components\TextInput::make('customer_feedback')
                            ->label('Feedback do Cliente')
                            ->nullable()
                            ->maxLength(255),

                        Forms\Components\Select::make('customer_rating')
                            ->label('Avaliação do Cliente (1 a 5)')
                            ->options([
                                1 => '★☆☆☆☆',
                                2 => '★★☆☆☆',
                                3 => '★★★☆☆',
                                4 => '★★★★☆',
                                5 => '★★★★★',
                            ])
                            ->nullable(),
                    ])->columns(2),

                Forms\Components\Section::make('Notas Internas')
                    ->description('Preencha as notas internas')
                    ->schema([
                        Forms\Components\Textarea::make('internal_notes')
                            ->label('Notas Internas')
                            ->nullable()
                            ->maxLength(3000)
                            ->columnSpanFull(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('os_number')
                    ->searchable()
                    ->sortable()
                    ->label('Número da OS'),

                Tables\Columns\TextColumn::make('client.name')
                    ->searchable()
                    ->sortable()
                    ->label('Cliente'),

                Tables\Columns\TextColumn::make('serviceLocation.name')
                    ->searchable()
                    ->sortable()
                    ->label('Local de Serviço'),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'aberto' => 'Aberto',
                        'em_analise' => 'Em Análise',
                        'aguardando_materiais' => 'Aguardando Materiais',
                        'em_andamento' => 'Em Andamento',
                        'aguardando_aprovacao' => 'Aguardando Aprovação',
                        'concluido' => 'Concluído',
                        'cancelado' => 'Cancelado',
                        'reaberto' => 'Reaberto'
                    ]),

                Tables\Columns\TextColumn::make('priority')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'baixa' => 'success',
                        'media' => 'warning',
                        'alta' => 'danger',
                        'critica' => 'danger',
                        default => 'gray',
                    })
                    ->label('Prioridade'),

                Tables\Columns\TextColumn::make('assignedTechnician.full_name')
                    ->searchable()
                    ->sortable()
                    ->label('Técnico Responsável'),

                Tables\Columns\TextColumn::make('opened_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('client_id')
                    ->label('Cliente')
                    ->relationship('client', 'name')
                    ->default(null)
                    ->placeholder('Todos'),

                Tables\Filters\SelectFilter::make('service_location_id')
                    ->label('Localização do Serviço')
                    ->relationship('serviceLocation', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Todos'),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aberto' => 'Aberto',
                        'em_analise' => 'Em Análise',
                        'aguardando_materiais' => 'Aguardando Materiais',
                        'em_andamento' => 'Em Andamento',
                        'aguardando_aprovacao' => 'Aguardando Aprovação',
                        'concluido' => 'Concluído',
                        'cancelado' => 'Cancelado',
                        'reaberto' => 'Reaberto'
                    ])
                    ->multiple()
                    ->placeholder('Todos'),

                Tables\Filters\SelectFilter::make('assignedTechnician_id')
                    ->label('Técnico Responsável')
                    ->relationship('assignedTechnician', 'full_name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Todos'),

                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Data Inicial')
                            ->default(now()->subMonth())
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Data Final')
                            ->default(now())
                            ->required(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'] ?? null,
                                fn (Builder $query, $startDate) => $query->whereDate('opened_at', '>=', $startDate)
                            )
                            ->when(
                                $data['end_date'] ?? null,
                                fn (Builder $query, $endDate) => $query->whereDate('opened_at', '<=', $endDate)
                            );
                    })
                    ->label('Período'),
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
            MaterialsRelationManager::class,
            ServicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceOrders::route('/'),
            'create' => Pages\CreateServiceOrder::route('/create'),
            'edit' => Pages\EditServiceOrder::route('/{record}/edit'),
        ];
    }
}
