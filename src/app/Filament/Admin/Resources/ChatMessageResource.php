<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ChatMessageResource\Pages;
use App\Filament\Admin\Resources\ChatMessageResource\RelationManagers;
use App\Models\ChatMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ChatMessageResource extends Resource
{
    protected static ?string $model = ChatMessage::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Chat Messages';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('chat_session_id')
                    ->relationship('session', 'id')
                    ->required()
                    ->label('Chat Session')
                    ->preload(),
                Forms\Components\Select::make('sender')
                    ->options([
                        'user' => 'User',
                        'ai' => 'AI',
                    ])
                    ->required()
                    ->label('Sender'),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->label('Message')
                    ->rows(4)
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('session.id')
                    ->label('Session ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('session.user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sender')
                    ->label('Sender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'user' => 'primary',
                        'ai' => 'success',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('message')
                    ->label('Message')
                    ->limit(50)
                    ->tooltip(fn (string $state): string => $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('sender')
                    ->options([
                        'user' => 'User',
                        'ai' => 'AI',
                    ])
                    ->label('Sender'),
                Tables\Filters\SelectFilter::make('chat_session_id')
                    ->relationship('session', 'id')
                    ->label('Chat Session')
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListChatMessages::route('/'),
            'create' => Pages\CreateChatMessage::route('/create'),
            'view' => Pages\ViewChatMessage::route('/{record}'),
            'edit' => Pages\EditChatMessage::route('/{record}/edit'),
        ];
    }
}

