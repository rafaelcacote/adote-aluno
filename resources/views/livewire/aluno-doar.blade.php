<div class="max-w-lg mx-auto px-4 py-6">
    <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-1 text-sm text-brand-700 font-medium mb-6">
        ← Voltar
    </a>

    <div class="bg-white rounded-xl border border-brand-100 p-5 shadow-sm mb-4">
        <div class="flex items-center gap-4">
            @if ($aluno->foto_url)
                <img src="{{ $aluno->foto_url }}" alt="" width="56" height="56" loading="lazy" decoding="async" class="w-14 h-14 rounded-full object-cover">
            @else
                <div class="w-14 h-14 rounded-full bg-brand-100 text-brand-700 flex items-center justify-center font-bold">
                    {{ $aluno->iniciais }}
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <h1 class="font-bold text-gray-900">{{ $aluno->nome }}</h1>
                <p class="text-sm text-gray-600">{{ $aluno->instituicao->nome }}</p>
                <p class="text-sm text-gray-500 mt-0.5">{{ $aluno->serie_ou_curso }}</p>
            </div>
        </div>
    </div>

    <div class="relative overflow-hidden rounded-2xl border border-amber-300 bg-gradient-to-b from-amber-50 to-orange-50 p-5 sm:p-6 mb-6">
        <div class="absolute inset-x-0 top-0 h-1.5 bg-amber-400"></div>

        <div class="flex items-start gap-3">
            <span class="flex-shrink-0 flex h-11 w-11 items-center justify-center rounded-full bg-amber-400 text-amber-950 shadow-sm">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
            </span>
            <div class="min-w-0">
                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-amber-800">Atenção — leia com cuidado</p>
                <p class="mt-1 text-lg sm:text-xl font-bold text-amber-950 leading-snug">
                    Na descrição do PIX, escreva exatamente este nome:
                </p>
            </div>
        </div>

        <div
            x-data="{ copied: false }"
            class="mt-4 rounded-xl bg-white border border-amber-200 px-4 py-4 shadow-sm"
        >
            <div class="flex items-center justify-between gap-3">
                <p class="text-xs font-medium uppercase tracking-wide text-amber-700">Nome do aluno</p>
                <button
                    type="button"
                    @click="navigator.clipboard.writeText(@js($aluno->nome)); copied = true; setTimeout(() => copied = false, 2000)"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-amber-500 text-amber-950 text-sm font-bold hover:bg-amber-400 transition"
                >
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                    </svg>
                    <span x-show="!copied">Copiar nome</span>
                    <span x-show="copied" x-cloak>Copiado!</span>
                </button>
            </div>
            <p class="mt-2 text-2xl sm:text-3xl font-extrabold text-gray-900 leading-tight tracking-tight break-words">
                {{ $aluno->nome }}
            </p>
        </div>

        @if ($config->texto_instrucao_pix)
            <p class="mt-4 text-sm sm:text-base font-medium text-amber-950 leading-relaxed">
                {{ $config->texto_instrucao_pix }}
            </p>
        @endif
    </div>

    <div class="bg-white rounded-xl border border-brand-100 p-5 shadow-sm space-y-4">
        <h2 class="font-semibold text-gray-900">Dados para doação</h2>
        <p class="text-sm text-gray-600">Você pode doar o valor que desejar via PIX.</p>

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
