@props([
    'mensalidades',
    'compact' => false,
])

@php
    $meses = collect(range(1, 12))->map(function (int $mes) use ($mensalidades) {
        $mensalidade = $mensalidades->firstWhere('mes', $mes);

        return [
            'mes' => $mes,
            'label' => \App\Models\Mensalidade::nomeMes($mes),
            'pago' => $mensalidade?->status === \App\Enums\StatusMensalidade::Pago,
        ];
    });
    $pagas = $meses->where('pago', true)->count();
    $percentual = round(($pagas / 12) * 100);
@endphp

<div {{ $attributes }}>
    @if ($compact)
        <div class="h-2.5 w-full bg-gray-100 rounded-full overflow-hidden">
            <div
                class="h-full bg-brand-500 rounded-full transition-all duration-300"
                style="width: {{ $percentual }}%"
            ></div>
        </div>
    @else
        <div class="grid grid-cols-6 gap-2 sm:grid-cols-12">
            @foreach ($meses as $item)
                <div class="flex flex-col items-center gap-1">
                    <span
                        @class([
                            'w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold',
                            'bg-brand-500 text-white' => $item['pago'],
                            'bg-gray-100 text-gray-400' => ! $item['pago'],
                        ])
                        title="{{ $item['label'] }}"
                    >
                        @if ($item['pago'])
                            ✓
                        @else
                            {{ $item['mes'] }}
                        @endif
                    </span>
                    <span class="text-[10px] text-gray-400 hidden sm:block">
                        {{ \Illuminate\Support\Str::substr($item['label'], 0, 3) }}
                    </span>
                </div>
            @endforeach
        </div>
        <p class="text-sm text-gray-600 mt-3 text-center">
            {{ $pagas }}/12 mensalidades quitadas em {{ $mensalidades->first()?->ano ?? now()->year }}
        </p>
    @endif
</div>
