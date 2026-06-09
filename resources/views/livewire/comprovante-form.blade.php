<div class="max-w-lg mx-auto px-4 py-6">
    <a href="{{ route('aluno.doar', $aluno) }}" wire:navigate class="inline-flex items-center gap-1 text-sm text-brand-700 font-medium mb-6">
        ← Voltar
    </a>

    <div class="bg-white rounded-xl border border-brand-100 p-5 shadow-sm">
        <h1 class="font-bold text-gray-900 text-lg">Enviar comprovante</h1>
        <p class="text-sm text-gray-600 mt-1">
            Aluno: <span class="font-medium">{{ $aluno->nome }}</span>
        </p>

        @if ($enviado)
            <div class="mt-6 rounded-lg bg-brand-50 border border-brand-200 p-4 text-center">
                <p class="font-semibold text-brand-800">Comprovante enviado!</p>
                <p class="text-sm text-brand-700 mt-1">
                    O administrador irá analisar em breve.
                </p>
                <a
                    href="{{ route('home') }}"
                    wire:navigate
                    class="inline-block mt-4 text-sm font-medium text-brand-700 underline"
                >
                    Voltar para a lista
                </a>
            </div>
        @else
            @if ($config->texto_form_comprovante)
                <p class="text-sm text-gray-600 mt-3">{{ $config->texto_form_comprovante }}</p>
            @endif

            <form wire:submit="enviar" class="mt-6 space-y-5">
                <div>
                    <label for="mes_referencia" class="block text-sm font-medium text-gray-700">
                        Mês de referência (opcional)
                    </label>
                    <select
                        wire:model="mes_referencia"
                        id="mes_referencia"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-400 focus:ring-brand-400 text-sm"
                    >
                        <option value="">Selecione...</option>
                        @foreach (range(1, 12) as $mes)
                            <option value="{{ $mes }}">{{ \App\Models\Mensalidade::nomeMes($mes) }}</option>
                        @endforeach
                    </select>
                    @error('mes_referencia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="arquivo" class="block text-sm font-medium text-gray-700">
                        Arquivo do comprovante *
                    </label>
                    <input
                        wire:model="arquivo"
                        id="arquivo"
                        type="file"
                        accept=".jpg,.jpeg,.png,.webp,.pdf,image/*,application/pdf"
                        class="mt-1 w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:font-semibold hover:file:bg-brand-100"
                    >
                    <p class="mt-1 text-xs text-gray-500">
                        JPG, PNG, WEBP ou PDF — máx. {{ $config->max_upload_mb }} MB
                    </p>
                    @error('arquivo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div wire:loading wire:target="arquivo" class="text-xs text-gray-500 mt-1">
                        Carregando arquivo...
                    </div>
                </div>

                <div>
                    <label for="observacao" class="block text-sm font-medium text-gray-700">
                        Observação (opcional)
                    </label>
                    <textarea
                        wire:model="observacao"
                        id="observacao"
                        rows="3"
                        maxlength="500"
                        placeholder="Alguma informação adicional..."
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-400 focus:ring-brand-400 text-sm"
                    ></textarea>
                    @error('observacao')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="enviar,arquivo"
                    class="w-full py-3 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="enviar">Enviar comprovante</span>
                    <span wire:loading wire:target="enviar">Enviando...</span>
                </button>
            </form>
        @endif
    </div>
</div>
