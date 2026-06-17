<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Alunos</h1>
            <p class="text-sm text-gray-600 mt-1">Gerencie alunos e envie convites para novos cadastros.</p>
        </div>
        <button
            wire:click="abrirConvite"
            class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-lg hover:bg-brand-700"
        >
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
            </svg>
            Convidar novo aluno
        </button>
    </div>

    <input wire:model.live.debounce.300ms="busca" type="search" placeholder="Buscar por nome..." class="w-full sm:max-w-sm mb-4 rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500">

    <div class="bg-white rounded-xl border border-brand-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aluno</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden sm:table-cell">Instituição</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Mensalidade</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($alunos as $aluno)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $aluno->nome }}</p>
                            <p class="text-xs text-gray-500">{{ $aluno->tipo->label() }} · {{ $aluno->serie_ou_curso }}</p>
                            @unless ($aluno->ativo)
                                <span class="text-xs text-red-600">Inativo</span>
                            @endunless
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell">{{ $aluno->instituicao->nome }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden md:table-cell">{{ $aluno->valorMensalFormatado() }}</td>
                        <td class="px-4 py-3 text-right space-x-2 whitespace-nowrap">
                            <a href="{{ route('admin.alunos.show', $aluno) }}" wire:navigate class="text-sm text-brand-700 font-medium">Mensalidades</a>
                            <a href="{{ route('admin.alunos.edit', $aluno) }}" wire:navigate class="text-sm text-gray-600">Editar</a>
                            <button wire:click="toggleAtivo({{ $aluno->id }})" class="text-sm text-gray-500">{{ $aluno->ativo ? 'Desativar' : 'Ativar' }}</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Nenhum aluno encontrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $alunos->links() }}</div>

    <div class="mt-10">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Convites de cadastro</h2>
        <div class="bg-white rounded-xl border border-brand-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Instituição</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden sm:table-cell">Ano</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Criado em</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($convites as $convite)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-900">{{ $convite->instituicao->nome }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell">{{ $convite->ano_letivo }}</td>
                            <td class="px-4 py-3">
                                @if ($convite->used_at)
                                    <span class="text-xs font-medium text-green-700 bg-green-50 px-2 py-1 rounded-full">Usado</span>
                                    @if ($convite->aluno)
                                        <p class="text-xs text-gray-500 mt-1">{{ $convite->aluno->nome }}</p>
                                    @endif
                                @elseif ($convite->expires_at->isPast())
                                    <span class="text-xs font-medium text-red-700 bg-red-50 px-2 py-1 rounded-full">Expirado</span>
                                @else
                                    <span class="text-xs font-medium text-amber-700 bg-amber-50 px-2 py-1 rounded-full">Pendente</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 hidden md:table-cell">{{ $convite->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-right">
                                @if ($convite->isValid())
                                    <div x-data="{ copied: false }" class="inline-flex items-center gap-2">
                                        <button
                                            type="button"
                                            @click="navigator.clipboard.writeText(@js($convite->url())); copied = true; setTimeout(() => copied = false, 2000)"
                                            class="text-sm text-brand-700 font-medium"
                                        >
                                            <span x-show="!copied">Copiar link</span>
                                            <span x-show="copied" x-cloak>Copiado!</span>
                                        </button>
                                    </div>
                                @elseif ($convite->aluno)
                                    <a href="{{ route('admin.alunos.show', $convite->aluno) }}" wire:navigate class="text-sm text-brand-700 font-medium">Ver aluno</a>
                                @else
                                    <span class="text-sm text-gray-400">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Nenhum convite gerado ainda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $convites->links() }}</div>
    </div>

    @if ($modalConviteAberto)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
                <h3 class="text-lg font-bold text-gray-900">Convidar novo aluno</h3>
                <p class="text-sm text-gray-600 mt-1">Gere um link para o aluno preencher o próprio cadastro.</p>

                @if ($linkGerado)
                    <div class="mt-4 rounded-lg bg-brand-50 border border-brand-200 p-4" x-data="{ copied: false }">
                        <p class="text-sm font-medium text-brand-800 mb-2">Link gerado — copie e envie ao aluno:</p>
                        <div class="flex gap-2">
                            <input
                                type="text"
                                readonly
                                value="{{ $linkGerado }}"
                                class="flex-1 text-sm rounded-lg border-brand-200 bg-white text-gray-700"
                            >
                            <button
                                type="button"
                                @click="navigator.clipboard.writeText(@js($linkGerado)); copied = true; setTimeout(() => copied = false, 2000)"
                                class="px-3 py-2 bg-brand-600 text-white text-sm font-semibold rounded-lg hover:bg-brand-700 whitespace-nowrap"
                            >
                                <span x-show="!copied">Copiar</span>
                                <span x-show="copied" x-cloak>Copiado!</span>
                            </button>
                        </div>
                        <p class="text-xs text-brand-700 mt-2">Válido por 30 dias. Uso único.</p>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button wire:click="fecharConvite" class="px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-lg hover:bg-brand-700">
                            Fechar
                        </button>
                    </div>
                @else
                    <form wire:submit="gerarConvite" class="mt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Instituição</label>
                            <select wire:model="instituicao_id" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500">
                                <option value="">Selecione...</option>
                                @foreach ($instituicoes as $inst)
                                    <option value="{{ $inst->id }}">{{ $inst->nome }}</option>
                                @endforeach
                            </select>
                            @error('instituicao_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Ano letivo</label>
                            <input wire:model="ano_letivo" type="number" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500">
                            @error('ano_letivo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="flex gap-3 justify-end pt-2">
                            <button type="button" wire:click="fecharConvite" class="px-4 py-2 text-sm text-gray-600">Cancelar</button>
                            <button type="submit" class="px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-lg hover:bg-brand-700">
                                Gerar link
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>
