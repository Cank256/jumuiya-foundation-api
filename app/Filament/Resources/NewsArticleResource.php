<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasResourcePermissions;
use App\Filament\Resources\NewsArticleResource\Pages;
use App\Models\NewsArticle;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class NewsArticleResource extends Resource
{
    use HasResourcePermissions;

    protected static ?string $model = NewsArticle::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'News & Articles';

    protected static ?int $navigationSort = 2;

    protected static function viewPermission(): string   { return 'view content'; }
    protected static function createPermission(): string { return 'create content'; }
    protected static function editPermission(): string   { return 'edit content'; }
    protected static function deletePermission(): string { return 'delete content'; }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Article Information')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, Forms\Set $set) =>
                            $set('slug', Str::slug($state))
                        )
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('slug')
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText('Auto-generated from title. Used in the URL.'),

                    Forms\Components\Select::make('category')
                        ->options([
                            'Community'          => 'Community',
                            'Education'          => 'Education',
                            'Gender Empowerment' => 'Gender Empowerment',
                            'Health & Wellbeing' => 'Health & Wellbeing',
                            'Environment'        => 'Environment',
                            'Annual Report'      => 'Annual Report',
                        ])
                        ->searchable(),

                    Forms\Components\DateTimePicker::make('published_at')
                        ->native(false)
                        ->default(now()),

                    Forms\Components\Select::make('author_id')
                        ->label('Author')
                        ->relationship('author', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable(),

                    Forms\Components\Toggle::make('featured')
                        ->label('Featured / Hero Article')
                        ->helperText('Pins this article to the top of the news page.'),
                ]),

            Forms\Components\Section::make('Featured Image')
                ->schema([
                    Forms\Components\FileUpload::make('featured_image')
                        ->image()
                        ->directory('news')
                        ->disk('public')
                        ->imagePreviewHeight('150'),
                ]),

            Forms\Components\Section::make('Content')
                ->schema([
                    Forms\Components\Textarea::make('excerpt')
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText('Short summary shown in cards and as a pull-quote.')
                        ->columnSpanFull(),

                    Forms\Components\RichEditor::make('content')
                        ->toolbarButtons([
                            'attachFiles', 'bold', 'italic', 'underline', 'strike',
                            'h2', 'h3', 'bulletList', 'orderedList',
                            'link', 'blockquote', 'undo', 'redo',
                        ])
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->disk('public')
                    ->width(60)
                    ->height(40),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('category')
                    ->badge(),

                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('featured')
                    ->boolean()
                    ->label('Featured'),

                Tables\Columns\TextColumn::make('published_at')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'Community'          => 'Community',
                        'Education'          => 'Education',
                        'Gender Empowerment' => 'Gender Empowerment',
                        'Health & Wellbeing' => 'Health & Wellbeing',
                        'Environment'        => 'Environment',
                        'Annual Report'      => 'Annual Report',
                    ]),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('published_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListNewsArticles::route('/'),
            'create' => Pages\CreateNewsArticle::route('/create'),
            'edit'   => Pages\EditNewsArticle::route('/{record}/edit'),
        ];
    }
}
