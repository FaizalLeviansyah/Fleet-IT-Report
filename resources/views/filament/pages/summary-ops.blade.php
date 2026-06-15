<x-filament-panels::page>
    <div class="max-w-4xl mx-auto w-full">
        <x-filament-panels::form wire:submit="generatePdf">

            <!-- Render Native Form Schema (Section, Grid, DatePicker) -->
            {{ $this->form }}

            <!-- Native Button Filament (Pasti Muncul & Responsif) -->
            <div class="mt-6">
                <x-filament::button type="submit" size="lg" color="primary" class="w-full justify-center">
                    GENERATE SUMMARY PDF
                </x-filament::button>
            </div>

        </x-filament-panels::form>
    </div>
</x-filament-panels::page>
