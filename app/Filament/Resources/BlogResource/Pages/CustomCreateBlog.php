<?php

namespace App\Filament\Resources\BlogResource\Pages;

use Filament\Forms;
use Illuminate\Support\Str;
use PhpParser\Builder\Function_;
use Filament\Forms\Components\Submit;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Card;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Button;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use App\Filament\Resources\BlogResource;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\Layout\Grid;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Concerns\InteractsWithForms;

class CustomCreateBlog extends Page implements HasForms
{
    
    use InteractsWithForms;
 
    // public ?array $blogs = []; 
   
    
    
    protected static string $resource = BlogResource::class;

    protected static string $view = 'filament.resources.blog-resource.pages.custom-create-blog';

    public function mount(): void 
    {
        $formData = request('form');
        $this->title = $formData['title'] ?? null;
        // $slug = $formData['slug'] ?? null;
    
        // Now you can use $title and $slug as needed
        // For example, you can set them as properties of your Livewire component
        // $this->title = $title;
        // $this->slug = $slug;
    }

    public function getCachedFormActions()
    {
        // return '';
        return [
            Action::make('Publish'),
            // ->live(onBlur: true)
            // ->required(),
            // Button::make('save')->label('Save Draft')->submitsForm(),
            // Button::make('publish')->label('Publish')->submitsForm(),
        ];
    }

    

    public Function hasFullWidthFormActions(){
        
        return [
            //
        ];
    }


    protected function getFormSchema(): array
{
    return [
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

                Hidden::make('status')
            ->default(0)
            ->reactive(),
        
        // ... Other form fields
        
      
    ];
}



    public function save($action)
    {

      dd($this->title);
        // Determine the status based on the button clicked
        $status = ($action === 'publish') ? 1 : 0;
        
        // Set the status in the form data
        $this->form->setFieldValue('status', $status);
        
        // Validate the form data (you can add validation rules)
        $this->validate([
            'form.title' => 'required',
            'form.content' => 'required',
            // Add any other validation rules as needed
        ]);
        
        // Now you can save the blog with the updated status and form data
        $blogData = $this->form->toArray();
        
        // Replace this with your code to save the blog data to the database
        // For example, if you have a Blog model:
        // Blog::create($blogData);
        
        // Optionally, you can redirect or show a success message here
        session()->flash('success', 'Blog saved successfully.');
        
        // Redirect to a specific page (change the route as needed)
        return redirect()->route('blogs.index');
    }



}
