<?php

namespace App\Filament\Resources;

use App\Enums\PackageType;
use App\Filament\Resources\PackageResource\Pages;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $isGloballySearchable = true;

    protected static ?string $recordTitleAttribute = 'name';

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
        return __('Package');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Packages');
    }

    public static function getNavigationLabel(): string
    {
        return __('Packages');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Package');
    }

    public static function getNavigationBadge(): ?string
    {
        return (string)Cache::rememberForever('packages_count', fn() => Package::query()->count());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(
                        fn(Forms\Get $get) => match (PackageType::of($get('type'))) {
                            PackageType::Composer => 'vendor/package',
                            PackageType::Github => 'user/repo',
                        }
                    )
                    ->rule(
                        fn(Forms\Get $get): Closure => function (string $attribute, string $value, Closure $fail) use ($get) {
                            if (enum_equals($get('type'), PackageType::Composer)) {
                                if (preg_match('/^[a-z0-9-]+\/[a-z0-9-]+$/', $value)) {
                                    return;
                                }

                                $fail('O nome do pacote deve seguir o formato "vendor/package".');
                            }

                            if (enum_equals($get('type'), PackageType::Github)) {
                                if (preg_match('/^[a-z0-9-]+\/[a-z0-9-]+$/', $value)) {
                                    return;
                                }

                                $fail('O nome do pacote deve seguir o formato "user/repo".');
                            }
                        },
                    )
                    ->required(),
                Forms\Components\ToggleButtons::make('type')
                    ->hidden(fn($context): bool => $context === 'edit')
                    ->hiddenLabel()
                    ->live()
                    ->options(PackageType::class)
                    ->default(PackageType::Composer)
                    ->required(),
                Forms\Components\Fieldset::make()
                    ->columns()
                    ->schema([
                        Forms\Components\Placeholder::make('composer-instructions')
                            ->label('Configurações do Composer')
                            ->content('Para adicionar um pacote do tipo Composer, você deve informar as credenciais de acesso ao repositório privado. O produto será sub-licenciado usando o Satis. Cada membro do time receberá uma credencial de acesso individual. Não compartilhe essas credenciais com ninguém.')
                            ->visible(fn(Forms\Get $get): bool => enum_equals($get('type'), PackageType::Composer)),
                        Forms\Components\Placeholder::make('github-instructions')
                            ->label('Configurações do GitHub')
                            ->content('Para adicionar um pacote do tipo GitHub, você deve informar as credenciais de acesso ao repositório privado. O produto será sub-licenciado usando o GitHub Packages. Cada membro do time receberá um Personal Access Token (PAT). Não compartilhe essas credenciais com ninguém.')
                            ->visible(fn(Forms\Get $get): bool => enum_equals($get('type'), PackageType::Github)),
                        Forms\Components\TextInput::make('url')
                            ->label(
                                fn(Forms\Get $get) => match (PackageType::of($get('type'))) {
                                    PackageType::Composer => 'URL do Repositório Composer',
                                    PackageType::Github => 'URL SSH do Repositório',
                                }
                            )
                            ->rule(
                                fn(Forms\Get $get): Closure => function (string $attribute, string $value, Closure $fail) use ($get) {
                                    if (!enum_equals($get('type'), PackageType::Github)) {
                                        return filter_var($value, FILTER_VALIDATE_URL);
                                    }

                                    if (preg_match('/^git@github.com:/', $value)) {
                                        return;
                                    }

                                    $fail('Utilize uma URL SSH válida para o repositório do GitHub.');
                                },
                            )
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('username')
                            ->label(
                                fn(Forms\Get $get) => match (PackageType::of($get('type'))) {
                                    PackageType::Composer => 'Username do Composer',
                                    PackageType::Github => 'Username ou Organização do GitHub',
                                }
                            )
                            ->required(),
                        Forms\Components\TextInput::make('password')
                            ->label(
                                fn(Forms\Get $get) => match (PackageType::of($get('type'))) {
                                    PackageType::Composer => 'Password do Composer',
                                    PackageType::Github => 'Personal Access Token (PAT)',
                                }
                            )
                            ->password()
                            ->revealable()
                            ->rule(
                                fn(Forms\Get $get): Closure => function (string $attribute, string $value, Closure $fail) use ($get) {
                                    if (!enum_equals($get('type'), PackageType::Github)) {
                                        return;
                                    }

                                    if (preg_match('/^github_pat_/', $value)) {
                                        return;
                                    }

                                    $fail('O Personal Access Token (PAT) deve seguir o formato "github_pat_".');
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
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('type')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('url')
                            ->columnSpanFull(),
                    ]),
                Infolists\Components\Group::make()
                    ->relationship('packageRelease')
                    ->schema([
                        Infolists\Components\Section::make()
                            ->heading(__('Package Last Release'))
                            ->schema([
                                Infolists\Components\TextEntry::make('version')
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('time')
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('type')
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('description')
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('homepage')
                                    ->columnSpanFull(),
                            ]),
                        Infolists\Components\Section::make()
                            ->heading(__('Dependencies'))
                            ->schema([
                                Infolists\Components\TextEntry::make('dependencies.name')
                                    ->hiddenLabel()
                                    ->listWithLineBreaks()
                                    ->bulleted(),
                            ]),
                    ]),
                Infolists\Components\Section::make()
                    ->relationship('packageReleases')
                    ->heading(__('Package Releases'))
                    ->schema([
                        Infolists\Components\TextEntry::make('version')
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('package_releases_count')
                    ->counts('packageReleases'),
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable()
                    ->toggleable()
                    ->sortable(),
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
            'index' => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'view' => Pages\ViewPackage::route('/{record}'),
            'edit' => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
