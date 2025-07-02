<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TokenResource\Pages;
use App\Models\Token;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class TokenResource extends Resource
{
    protected static ?string $model = Token::class;

    protected static ?string $navigationIcon = 'tabler-key';

    protected static bool $isGloballySearchable = true;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return TokenResource::getUrl('view', ['record' => $record]);
    }

    public static function getModelLabel(): string
    {
        return __('tokens.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('tokens.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('tokens.navigation.label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('tokens.navigation.group');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Cache::rememberForever('tokens_count', fn () => Token::query()->count());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('tokens.form.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('packages')
                    ->label(__('tokens.form.packages'))
                    ->relationship('packages', 'name', fn (Builder $query) => $query->orderBy('name'))
                    ->multiple(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label(__('tokens.infolist.name'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('token')
                            ->label(__('tokens.infolist.token'))
                            ->copyable()
                            ->copyMessage(__('tokens.copy_message.token'))
                            ->copyMessageDuration(1500)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('composer_command')
                            ->label(__('tokens.infolist.composer_command'))
                            ->copyable()
                            ->copyMessage(__('tokens.copy_message.composer_command'))
                            ->copyMessageDuration(1500)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('composer_repository')
                            ->label(__('tokens.infolist.composer_repository'))
                            ->formatStateUsing(fn ($state) => '<pre>'.nl2br($state).'</pre>')
                            ->html()
                            ->copyable()
                            ->copyMessage(__('tokens.copy_message.composer_repository'))
                            ->copyMessageDuration(1500)
                            ->columnSpanFull(),
                    ]),
                Infolists\Components\Section::make()
                    ->label(__('tokens.infolist.section.packages'))
                    ->schema([
                        Infolists\Components\TextEntry::make('packages.name')
                            ->hiddenLabel()
                            ->listWithLineBreaks()
                            ->bulleted(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('tokens.table.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('packages_count')
                    ->label(__('tokens.table.packages_count'))
                    ->counts('packages'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('tokens.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('tokens.table.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTokens::route('/'),
            'create' => Pages\CreateToken::route('/create'),
            'view' => Pages\ViewToken::route('/{record}'),
            'edit' => Pages\EditToken::route('/{record}/edit'),
        ];
    }
}
