<?php

namespace App\Filament\Resources\BlogResource\Pages;

use Filament\Actions;
use App\Filament\Resources\BlogResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;

    protected static string $view = 'filament.resources.blog-resource.pages.custom-create-blog';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = 1;
    
        return $data;
    }
    

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    
    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Post Created Successfully');
    }

}
