<div class="max-w-lg mx-auto px-4 py-6">
    <div class="bg-white rounded-xl border border-brand-100 p-5 shadow-sm">
        @if ($conviteInvalido)
            <div class="text-center py-4">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600 mb-4">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <h1 class="font-bold text-gray-900 text-lg">Link indisponível</h1>
                <p class="text-sm text-gray-600 mt-2">{{ $mensagemInvalido }}</p>
            </div>
        @elseif ($cadastrado)
            <div class="text-center py-4">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-100 text-brand-600 mb-4">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="font-bold text-gray-900 text-lg">Cadastro enviado!</h1>
                <p class="text-sm text-gray-600 mt-2">
                    Seu cadastro foi recebido e será analisado pelo administrador.
                    Você aparecerá no site assim que for aprovado.
                </p>
            </div>
        @else
            <h1 class="font-bold text-gray-900 text-lg">Cadastro de aluno</h1>
            <p class="text-sm text-gray-600 mt-1">
                Preencha seus dados para participar do programa de apoio.
            </p>

            <div class="mt-4 rounded-lg bg-brand-50 border border-brand-100 px-4 py-3 text-sm">
                <p class="text-gray-700">
                    <span class="font-medium">Instituição:</span> {{ $convite->instituicao->nome }}
                </p>
                <p class="text-gray-700 mt-1">
                    <span class="font-medium">Ano letivo:</span> {{ $convite->ano_letivo }}
                </p>
            </div>

            <form wire:submit="cadastrar" class="mt-6 space-y-5">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700">Nome completo *</label>
                    <input
                        wire:model="nome"
                        id="nome"
                        type="text"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-400 focus:ring-brand-400 text-sm"
                    >
                    @error('nome') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="foto" class="block text-sm font-medium text-gray-700">Foto (opcional)</label>
                    <input
                        wire:model="foto"
                        id="foto"
                        type="file"
                        accept="image/*"
                        class="mt-1 w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brand-50 file:text-brand-700 file:font-semibold hover:file:bg-brand-100"
                    >
                    @error('foto') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="idade" class="block text-sm font-medium text-gray-700">Idade *</label>
                        <input
                            wire:model="idade"
                            id="idade"
                            type="number"
                            min="5"
                            max="99"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-400 focus:ring-brand-400 text-sm"
                        >
                        @error('idade') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo *</label>
                        <select
                            wire:model="tipo"
                            id="tipo"
                            class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-400 focus:ring-brand-400 text-sm"
                        >
                            <option value="colegio">Colégio</option>
                            <option value="faculdade">Faculdade</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="serie_ou_curso" class="block text-sm font-medium text-gray-700">Série ou curso *</label>
                    <input
                        wire:model="serie_ou_curso"
                        id="serie_ou_curso"
                        type="text"
                        placeholder="Ex: 3º ano do Ensino Médio"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-400 focus:ring-brand-400 text-sm"
                    >
                    @error('serie_ou_curso') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="valor_mensal" class="block text-sm font-medium text-gray-700">Valor da mensalidade (R$) *</label>
                    <input
                        wire:model="valor_mensal"
                        id="valor_mensal"
                        type="number"
                        step="0.01"
                        min="0"
                        class="mt-1 w-full rounded-lg border-gray-300 focus:border-brand-400 focus:ring-brand-400 text-sm"
                    >
                    <p class="mt-1 text-xs text-gray-500">Informe o valor mensal da sua instituição.</p>
                    @error('valor_mensal') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    wire:target="cadastrar,foto"
                    class="w-full py-3 rounded-xl bg-brand-600 text-white font-semibold hover:bg-brand-700 transition disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="cadastrar">Enviar cadastro</span>
                    <span wire:loading wire:target="cadastrar">Enviando...</span>
                </button>
            </form>
        @endif
    </div>
</div>
