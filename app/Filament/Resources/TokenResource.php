<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TokenResource\Pages;
use App\Models\Package;
use App\Models\Token;
use Dvarilek\FilamentTableSelect\Components\Form\TableSelect;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

class TokenResource extends Resource
{
    protected static ?string $model = Token::class;

    protected static ?string $navigationIcon = 'tabler-key';

    protected static bool $isGloballySearchable = true;

    protected static ?string $recordTitleAttribute = 'name';

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
        return __('Token');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Tokens');
    }

    public static function getNavigationLabel(): string
    {
        return __('Tokens');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Package');
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
                    ->required()
                    ->maxLength(255),
                TableSelect::make('packages')
                    ->relationship('packages', 'name')
                    ->multiple()
                    ->optionColor('success')
                    ->tableLocation(PackageResource::class)
                    ->maxItems(fn () => Package::query()->count())
                    ->selectionAction(function (Forms\Components\Actions\Action $action) {
                        return $action
                            ->modalHeading(__('Select Packages'))
                            ->modalWidth(MaxWidth::MaxContent)
                            ->slideOver(false);
                    }),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('token')
                            ->copyable()
                            ->copyMessage(__('Token copied successfully!'))
                            ->copyMessageDuration(1500)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('composer_command')
                            ->copyable()
                            ->copyMessage(__('Composer command copied successfully!'))
                            ->copyMessageDuration(1500)
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('composer_repository')
                            ->formatStateUsing(fn ($state) => nl2br($state))
                            ->html()
                            ->copyable()
                            ->copyMessage(__('Composer repository copied successfully!'))
                            ->copyMessageDuration(1500)
                            ->columnSpanFull(),
                    ]),
                Infolists\Components\Section::make()
                    ->heading(__('Packages'))
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
