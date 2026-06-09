<div class="max-w-lg mx-auto px-4 py-6">
    <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-1 text-sm text-brand-700 font-medium mb-6">
        ← Voltar
    </a>

    <div class="bg-white rounded-xl border border-brand-100 p-5 shadow-sm mb-6">
        <div class="flex items-center gap-4">
            @if ($aluno->foto_url)
                <img src="{{ $aluno->foto_url }}" alt="" width="56" height="56" loading="lazy" decoding="async" class="w-14 h-14 rounded-full object-cover">
            @else
                <div class="w-14 h-14 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center font-bold">
                    {{ $aluno->iniciais }}
                </div>
            @endif
            <div>
                <h1 class="font-bold text-gray-900">{{ $aluno->nome }}</h1>
                <p class="text-sm text-gray-600">{{ $aluno->instituicao->nome }}</p>
                <p class="text-sm text-brand-700 font-medium mt-0.5">
                    Mensalidade: {{ $aluno->valorMensalFormatado() }}
                </p>
            </div>
        </div>

        <div class="mt-5">
            <x-aluno.progresso-mensalidades
                :mensalidades="$aluno->mensalidadesDoAno()"
                :compact="false"
            />
        </div>
    </div>

    <div class="bg-white rounded-xl border border-brand-100 p-5 shadow-sm space-y-4">
        <h2 class="font-semibold text-gray-900">Dados para doação</h2>

        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Instituição</p>
            <p class="font-medium text-gray-900">{{ $aluno->instituicao->nome }}</p>
        </div>

        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">CNPJ</p>
            <p class="font-medium text-gray-900">{{ $aluno->instituicao->cnpj }}</p>
        </div>

        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Chave PIX</p>
            <div
                x-data="{ copied: false }"
                class="flex items-center gap-2"
            >
                <code class="flex-1 bg-brand-50 border border-brand-100 rounded-lg px-3 py-2.5 text-sm font-mono text-brand-900 break-all">
                    {{ $aluno->instituicao->chave_pix }}
                </code>
                <button
                    type="button"
                    @click="navigator.clipboard.writeText(@js($aluno->instituicao->chave_pix)); copied = true; setTimeout(() => copied = false, 2000)"
                    class="flex-shrink-0 px-3 py-2.5 rounded-lg bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700 transition"
                >
                    <span x-show="!copied">Copiar</span>
                    <span x-show="copied" x-cloak>Copiado!</span>
                </button>
            </div>
        </div>

        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wide">Nome no PIX</p>
            <p class="font-medium text-gray-900">{{ $aluno->instituicao->nome_pix }}</p>
        </div>

        <div class="rounded-lg bg-amber-50 border border-amber-200 p-4">
            <p class="text-sm text-amber-900 font-medium">Na descrição do PIX, escreva:</p>
            <p class="text-sm text-amber-800 mt-1 font-semibold">"{{ $aluno->nome }}"</p>
            @if ($config->texto_instrucao_pix)
                <p class="text-xs text-amber-700 mt-2">{{ $config->texto_instrucao_pix }}</p>
            @endif
        </div>

        @if ($config->aviso_legal)
            <p class="text-xs text-gray-500">{{ $config->aviso_legal }}</p>
        @endif

        <a
            href="{{ route('aluno.comprovante', $aluno) }}"
            wire:navigate
            class="block w-full text-center py-3 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition"
        >
            Enviar comprovante do PIX
        </a>

        <p class="text-xs text-center text-gray-500">
            Sem comprovante, o pagamento não tem validade para o colégio.
        </p>
    </div>
</div>
