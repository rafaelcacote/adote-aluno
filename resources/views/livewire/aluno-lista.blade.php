<div class="max-w-lg mx-auto px-4 py-6">
    <div class="text-center mb-6">
        <h1 class="text-xl font-bold text-brand-900 sm:text-2xl">
            {{ $config->titulo }}
        </h1>
        @if ($config->subtitulo)
            <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                {{ $config->subtitulo }}
            </p>
        @endif
    </div>

    <div class="relative mb-6">
        <label for="busca" class="sr-only">Buscar aluno pelo nome</label>
        <input
            wire:model.live.debounce.300ms="busca"
            id="busca"
            type="search"
            placeholder="Buscar aluno pelo nome..."
            autocomplete="off"
            class="w-full rounded-xl border-brand-200 bg-white pl-10 pr-4 py-3 text-sm shadow-sm focus:border-brand-400 focus:ring-brand-400"
        >
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
    </div>

    <div wire:loading wire:target="busca" class="text-center text-sm text-gray-500 py-4">
        Buscando...
    </div>

    @if ($alunos->isEmpty())
        <div class="bg-white rounded-xl border border-brand-100 p-8 text-center">
            @if (filled($busca))
                <p class="text-gray-700 font-medium">Nenhum aluno encontrado</p>
                <p class="text-sm text-gray-500 mt-1">
                    Não há resultados para "{{ $busca }}".
                </p>
            @else
                <p class="text-gray-700 font-medium">Nenhum aluno disponível</p>
                <p class="text-sm text-gray-500 mt-1">
                    Em breve novos estudantes serão cadastrados.
                </p>
            @endif
        </div>
    @else
        <div class="space-y-4" wire:loading.remove wire:target="busca">
            @foreach ($alunos as $aluno)
                <x-aluno.card :aluno="$aluno" />
            @endforeach
        </div>

        <div class="mt-6">
            {{ $alunos->links() }}
        </div>
    @endif
</div>
