<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Blog;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\MarkdownEditor;
use App\Filament\Resources\BlogResource\Pages;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-eye';

    // set active navigation 
    protected static ?string $activeNavigationIcon = 'heroicon-o-eye';

    protected static ?string $navigationGroup = 'Blog';  // to make a separate group

    // change navigation title 
    protected static ?string $navigationLabel = 'View Blogs';

    protected static ?int $navigationSort = 2;         // navigation sort no 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                // card no 1
                Card::make()
                    ->schema([
                        Grid::make(1)
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(2048)
                                    ->reactive()  // 
                                    ->live('100')  // click out of input then create slug
                                    ->afterStateUpdated(function($set, $state){

                                        $set('slug', Str::slug($state));
                                    }),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(2048),

                                MarkdownEditor::make('content')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                    ])->columnSpan(8),


                // card no 2
                Card::make()
                    ->schema([
                        Grid::make(1)
                            ->schema([

                                Hidden::make('user_id')
                                        ->default(auth()->user()->id),
                            

                                // Select::make('user_id')
                                //     ->relationship('user', 'name')
                                //     ->required()
                                //     ->searchable(['name', 'email'])  // search by name and email 
                                //     ->loadingMessage('Loading authors...')
                                //     ->noSearchResultsMessage('No authors found.')
                                //     ->preload()
                                //     ->createOptionForm([

                                //         // show blog category   create form in the blog creating time  
                                //         TextInput::make('name')
                                //             ->required()
                                //             ->maxLength(255),
                                //         TextInput::make('email')
                                //             ->required()
                                //             ->email()
                                //             ->maxLength(255),
                                //         TextInput::make('password')
                                //             ->required()
                                //             ->password()
                                //             ->minLength(8)
                                //             ->autocomplete(false)
                                //             ->maxLength(255)
                                //     ]),



                                Select::make('blogCategory')
                                    ->relationship('blogCategory', 'title')
                                    ->searchable()    // enable searching  
                                    ->loadingMessage('Loading categories ...')
                                    ->noSearchResultsMessage('No category found.')
                                    ->searchPrompt('Search authors by their name or email address')
                                    ->preload()       // load all categories when we searching 
                                    ->required()
                                    ->multiple()      // select multiple option 
                                    ->default(auth()->user()->id)
                                    ->createOptionForm([
                                        // show user create form in the blog creating time  
                                        TextInput::make('title')
                                            ->required()
                                            ->unique()
                                            ->reactive()
                                            ->live(500)
                                            ->afterStateUpdated(function ($set, $state) {

                                                $set('slug', Str::slug($state));
                                            }),

                                          
                                        TextInput::make('slug')
                                            ->required()
                                            ->unique(),
                                    ]),


                                FileUpload::make('thumbnail')
                                    ->required()
                                    ->directory('blogs')
                                    ->preserveFilenames()  // show original file name 
                                    ->image()
                                    ->imageEditor(),         // edit image)

                            ])
                    ])->columnSpan(4)

            ])->columns(12);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('thumbnail')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(url('https://fakeimg.pl/200x200'))
                    ->extraImgAttributes(fn (Blog $record): array => [
                        'alt' => "{$record->slug}",
                    ]),

                TextColumn::make('title')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Published')
                    ->dateTime()
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Draft' => 'danger',
                        'Published' => 'success',
                    }),

            ])
            ->filters([
                // set filter on status 
                SelectFilter::make('status')
                    ->options([
                        'Draft' => 'Draft',
                        'Published' => 'Published',
                    ])
                    ->label('Status?')
                    ->indicator('Status'),
            ])

            ->actions([
                // combine action button in the group
                ActionGroup::make([

                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),

                    // show publish button  when status is draft 
                    Action::make('Published')
                        ->icon('heroicon-o-eye')
                        ->action(function (Blog $record) {
                            $record->status = 'Published';
                            $record->save();
                        })
                        ->hidden(fn (Blog $record): bool => $record->status == "Published"),

                    // show draft button when status is publish
                    Action::make('Draft')
                        ->icon('heroicon-o-eye-slash')
                        ->action(function (Blog $record) {
                            $record->status = 'Draft';
                            $record->save();
                        })
                        ->hidden(fn (Blog $record): bool => $record->status == "Draft"),

                ])
                    ->button()
                    ->label('Actions'),
            ])

            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])

            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListBlogs::route('/'),
            // 'create' => Pages\CreateBlog::route('/create'),
            'create' => Pages\CustomCreateBlog::route('/create'),
            'view' => Pages\ViewBlog::route('/{record}'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
