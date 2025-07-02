<?php

namespace App\Filament\Resources;

use App\Enums\PackageType;
use App\Filament\Resources\PackageResource\Pages;
use App\Filament\Resources\PackageResource\RelationManagers;
use App\Models\Package;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;

use function App\Support\enum_equals;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;

    protected static ?string $navigationIcon = 'tabler-packages';

    protected static bool $isGloballySearchable = true;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }

    public static function getGlobalSearchResultUrl($record): string
    {
        return PackageResource::getUrl('view', ['record' => $record]);
    }

    public static function getModelLabel(): string
    {
        return __('packages.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('packages.plural');
    }

    public static function getNavigationLabel(): string
    {
        return __('packages.navigation.label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('packages.navigation.group');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Cache::rememberForever('packages_count', fn () => Package::query()->count());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(
                        fn (Forms\Get $get) => match (PackageType::of($get('type'))) {
                            PackageType::Composer => 'vendor/package',
                            PackageType::Github => 'user/repo',
                        }
                    )
                    ->rule(
                        fn (Forms\Get $get): Closure => function (string $attribute, string $value, Closure $fail) use ($get) {
                            if (enum_equals($get('type'), PackageType::Composer)) {
                                if (preg_match('/^[a-z0-9-]+\/[a-z0-9-]+$/', $value)) {
                                    return;
                                }

                                $fail(__('packages.validations.name.composer'));
                            }

                            if (enum_equals($get('type'), PackageType::Github)) {
                                if (preg_match('/^[a-z0-9-]+\/[a-z0-9-]+$/', $value)) {
                                    return;
                                }

                                $fail(__('packages.validations.name.github'));
                            }
                        },
                    )
                    ->required(),
                Forms\Components\ToggleButtons::make('type')
                    ->hidden(fn ($context): bool => $context === 'edit')
                    ->hiddenLabel()
                    ->live()
                    ->options(PackageType::class)
                    ->default(PackageType::Composer)
                    ->required(),
                Forms\Components\Fieldset::make()
                    ->columns()
                    ->schema([
                        Forms\Components\Placeholder::make('composer-instructions')
                            ->label(__('packages.instructions.composer.label'))
                            ->content(__('packages.instructions.composer.content'))
                            ->visible(fn (Forms\Get $get): bool => enum_equals($get('type'), PackageType::Composer)),
                        Forms\Components\Placeholder::make('github-instructions')
                            ->label(__('packages.instructions.github.label'))
                            ->content(__('packages.instructions.github.content'))
                            ->visible(fn (Forms\Get $get): bool => enum_equals($get('type'), PackageType::Github)),
                        Forms\Components\TextInput::make('url')
                            ->label(
                                fn (Forms\Get $get) => match (PackageType::of($get('type'))) {
                                    PackageType::Composer => __('packages.form.url.composer'),
                                    PackageType::Github => __('packages.form.url.github'),
                                }
                            )
                            ->rule(
                                fn (Forms\Get $get): Closure => function (string $attribute, string $value, Closure $fail) use ($get) {
                                    if (! enum_equals($get('type'), PackageType::Github)) {
                                        return filter_var($value, FILTER_VALIDATE_URL);
                                    }

                                    if (preg_match('/^git@github.com:/', $value)) {
                                        return;
                                    }

                                    $fail(__('packages.validations.url.github'));
                                },
                            )
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('username')
                            ->label(
                                fn (Forms\Get $get) => match (PackageType::of($get('type'))) {
                                    PackageType::Composer => __('packages.form.username.composer'),
                                    PackageType::Github => __('packages.form.username.github'),
                                }
                            )
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->label(
                                fn (Forms\Get $get) => match (PackageType::of($get('type'))) {
                                    PackageType::Composer => __('packages.form.password.composer'),
                                    PackageType::Github => __('packages.form.password.github'),
                                }
                            )
                            ->password()
                            ->revealable()
                            ->rule(
                                fn (Forms\Get $get): Closure => function (string $attribute, string $value, Closure $fail) use ($get) {
                                    if (! enum_equals($get('type'), PackageType::Github)) {
                                        return;
                                    }

                                    if (preg_match('/^github_pat_/', $value)) {
                                        return;
                                    }

                                    $fail(__('packages.validations.password.github'));
                                },
                            )
                            ->required(),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label(__('packages.infolist.name'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('type')
                            ->label(__('packages.infolist.type'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('url')
                            ->label(__('packages.infolist.url'))
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('composer_command')
                            ->label(__('packages.infolist.composer_command'))
                            ->copyable()
                            ->copyMessage(__('packages.copy_message.composer_command'))
                            ->copyMessageDuration(1500)
                            ->columnSpanFull(),
                    ]),
                Infolists\Components\Grid::make()
                    ->relationship('packageRelease')
                    ->schema([
                        Infolists\Components\Section::make()
                            ->columnSpanFull()
                            ->label(__('packages.infolist.section.package_release'))
                            ->schema([
                                Infolists\Components\TextEntry::make('version')
                                    ->label(__('package_releases.infolist.version'))
                                    ->badge()
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('time')
                                    ->label(__('package_releases.infolist.time'))
                                    ->columnSpanFull()
                                    ->hidden(fn ($state) => blank($state)),
                                Infolists\Components\TextEntry::make('type')
                                    ->label(__('package_releases.infolist.type'))
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('description')
                                    ->label(__('package_releases.infolist.description'))
                                    ->columnSpanFull()
                                    ->hidden(fn ($state) => blank($state)),
                                Infolists\Components\TextEntry::make('homepage')
                                    ->label(__('package_releases.infolist.homepage'))
                                    ->columnSpanFull()
                                    ->hidden(fn ($state) => blank($state)),
                            ]),
                        Infolists\Components\RepeatableEntry::make('dependencies')
                            ->label(__('package_releases.infolist.section.dependencies'))
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label(__('dependencies.infolist.name')),
                                Infolists\Components\TextEntry::make('pivot.version')
                                    ->label(__('package_releases.infolist.version'))
                                    ->badge(),
                            ])
                            ->columns()
                            ->grid()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('packages.table.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('package_releases_count')
                    ->label(__('packages.table.package_releases_count'))
                    ->counts('packageReleases'),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('packages.table.type'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('url')
                    ->label(__('packages.table.url'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('packages.table.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('packages.table.updated_at'))
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
            RelationManagers\PackageReleasesRelationManager::class,
            RelationManagers\PackageDownloadsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'view' => Pages\ViewPackage::route('/{record}'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
