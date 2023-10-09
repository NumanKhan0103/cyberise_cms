<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}
        
        <!-- Add the Publish button -->
        <button type="submit" wire:click="save('publish')" class="btn btn-primary">
            Publish
        </button>
        
        <button type="submit" wire:click="save('draft')" class="btn btn-secondary">
            Save as Draft
        </button>
    </x-filament-panels::form>
</x-filament-panels::page>
