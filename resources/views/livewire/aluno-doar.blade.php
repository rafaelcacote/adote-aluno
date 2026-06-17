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
            <div class="flex-1 min-w-0">
                <h1 class="font-bold text-gray-900">{{ $aluno->nome }}</h1>
                <p class="text-sm text-gray-600">{{ $aluno->instituicao->nome }}</p>
                <p class="text-sm text-gray-500 mt-0.5">{{ $aluno->serie_ou_curso }}</p>
            </div>
        </div>

        <button
            type="button"
            wire:click="abrirMensalidades"
            class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-brand-200 bg-brand-50 text-brand-800 text-sm font-medium hover:bg-brand-100 transition"
        >
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
            </svg>
            Ver situação das mensalidades
        </button>
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

    @if ($modalMensalidadesAberto)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 bg-black/50" wire:click.self="fecharMensalidades">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-start justify-between gap-4 mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Mensalidades {{ $aluno->ano_letivo }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $aluno->nome }}</p>
                    </div>
                    <button
                        type="button"
                        wire:click="fecharMensalidades"
                        class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100"
                        title="Fechar"
                    >
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <x-aluno.progresso-mensalidades
                    :mensalidades="$aluno->mensalidadesDoAno()"
                    :compact="false"
                />

                <p class="text-xs text-gray-500 mt-4 text-center">
                    As doações ajudam o estudante ao longo do ano — qualquer valor é bem-vindo.
                </p>

                <button
                    type="button"
                    wire:click="fecharMensalidades"
                    class="mt-4 w-full py-2.5 rounded-lg bg-brand-600 text-white text-sm font-semibold hover:bg-brand-700"
                >
                    Fechar
                </button>
            </div>
        </div>
    @endif
</div>
