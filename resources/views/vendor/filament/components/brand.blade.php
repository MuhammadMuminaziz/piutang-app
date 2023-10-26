@if (filled($brand = config('filament.brand')))
    <div
        @class([
            'filament-brand text-xl font-bold leading-5 tracking-tight',
            'dark:text-white' => config('filament.dark_mode'),
        ])
    >
        <img src="{{ asset('/img/ADS.png') }}" alt="Logo" class="h-10" style="display: inline-block">
        {{ $brand }}
    </div>
@endif
