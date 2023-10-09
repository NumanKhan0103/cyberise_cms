<?php

namespace App\Filament\Resources;


use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use App\Models\BlogCategory;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextInputColumn;
use App\Filament\Resources\BlogCategoryResource\Pages;

class BlogCategoryResource extends Resource
{
    protected static ?string $model = BlogCategory::class;

    // navigation icon 
    protected static ?string $navigationIcon = 'heroicon-o-tag'; 

    // set active navigation 
    protected static ?string $activeNavigationIcon = 'heroicon-o-tag'; 
    
        // combine in the group
    protected static ?string $navigationGroup = 'Blog';  // to make a separate group
    
    // change navigation title 
    protected static ?string $navigationLabel = 'Category'; 

    protected static ?int $navigationSort = 3;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                

                TextInput::make('title')
                            ->required()
                            ->unique()
                            ->reactive()  // 
                            ->live('500')  // click out of input then create slug
                            ->afterStateUpdated(function($set, $state){

                                $set('slug', Str::slug($state));
                            }),
                            
                TextInput::make('slug')
                            ->required()
                            ->unique(),
                
                FileUpload::make('icon')
                            ->directory('blog-category-icons')
                            ->preserveFilenames()  // show original file name 
                            ->image()               
                            ->imageEditor(),     
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            
            ->columns([
                
          ImageColumn::make('icon')
                        ->disk('public')
                        ->square()
                        ->defaultImageUrl(url('https://fakeimg.pl/100x100'))
                        ->extraImgAttributes(fn (BlogCategory $record): array => [
                            'alt' => "{$record->slug}",
                        ]),

                        
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Published At'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBlogCategories::route('/'),
        ];
    }    
}
