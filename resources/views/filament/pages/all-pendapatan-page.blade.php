<x-filament::page>
<div class="grid gap-6">
    <div style="grid-row: 2;">
        {{ $this->table }}
    </div>

    <x-filament::card class="py-6" style="grid-row: 1;">
    <p class="text-sm text-gray-700">Semua Pendapatan</p>
    <p class="text-3xl" style="margin-top: 7px;">{{ currency_IDR($this->pendapatan) }}</p>
    <p style="color: #D97706; margin-top: 7px;" class="text-sm">Total semua pendapatan yang terdaftar <x-heroicon-o-cash class="w-4 h-4 inline"/></p>
    </x-filament::card>
</div>
</x-filament::page>
