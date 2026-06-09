@props(['aluno'])

@php
    $pagas = $aluno->mensalidadesPagasCount();
    $total = 12;
    $percentual = $total > 0 ? round(($pagas / $total) * 100) : 0;
@endphp

<a
    href="{{ route('aluno.doar', $aluno) }}"
    wire:navigate
    class="block bg-white rounded-xl border border-brand-100 p-4 shadow-sm hover:border-brand-300 hover:shadow-md transition active:scale-[0.99]"
>
    <div class="flex items-start gap-4">
        @if ($aluno->foto_url)
            <img
                src="{{ $aluno->foto_url }}"
                alt="Foto de {{ $aluno->nome }}"
                width="64"
                height="64"
                loading="lazy"
                decoding="async"
                class="w-16 h-16 rounded-full object-cover flex-shrink-0 bg-gray-100"
            >
        @else
            <div class="w-16 h-16 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center font-bold text-lg flex-shrink-0">
                {{ $aluno->iniciais }}
            </div>
        @endif

        <div class="flex-1 min-w-0">
            <h2 class="font-semibold text-gray-900 truncate">{{ $aluno->nome }}</h2>
            <p class="text-sm text-gray-500 mt-0.5">
                {{ $aluno->idade }} anos · {{ $aluno->tipo->label() }}
            </p>
            <p class="text-sm text-gray-600 truncate">
                {{ $aluno->instituicao->nome }} — {{ $aluno->serie_ou_curso }}
            </p>
            <p class="text-sm font-medium text-brand-700 mt-1">
                Mensalidade: {{ $aluno->valorMensalFormatado() }}
            </p>
        </div>
    </div>

    <div class="mt-4">
        <x-aluno.progresso-mensalidades
            :mensalidades="$aluno->mensalidadesDoAno()"
            :compact="true"
        />
        <p class="text-xs text-gray-500 mt-2 text-center">{{ $pagas }}/{{ $total }} mensalidades pagas</p>
    </div>

    <div class="mt-4">
        <span class="block w-full text-center py-2.5 rounded-lg bg-brand-600 text-white text-sm font-semibold">
            Quero ajudar →
        </span>
    </div>
</a>
