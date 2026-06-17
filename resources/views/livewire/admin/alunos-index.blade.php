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

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($alunos as $aluno)
            <div @class([
                'bg-white rounded-xl border p-5 shadow-sm flex flex-col',
                'border-brand-100' => $aluno->ativo,
                'border-gray-200 opacity-75' => ! $aluno->ativo,
            ])>
                <div class="flex items-start gap-4">
                    @if ($aluno->foto_url)
                        <img
                            src="{{ $aluno->foto_url }}"
                            alt=""
                            class="w-14 h-14 rounded-full object-cover shrink-0 bg-gray-100"
                        >
                    @else
                        <div @class([
                            'flex h-14 w-14 shrink-0 items-center justify-center rounded-full font-bold text-lg',
                            'bg-brand-100 text-brand-700' => $aluno->ativo,
                            'bg-gray-100 text-gray-400' => ! $aluno->ativo,
                        ])>
                            {{ $aluno->iniciais }}
                        </div>
                    @endif

                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 leading-tight truncate">{{ $aluno->nome }}</h3>
                        @unless ($aluno->ativo)
                            <span class="inline-flex mt-1.5 text-xs font-medium text-red-700 bg-red-50 px-2 py-0.5 rounded-full">Inativo</span>
                        @endunless

                        <div class="mt-2 space-y-1">
                            <p class="text-sm text-gray-500">{{ $aluno->idade }} anos · {{ $aluno->tipo->label() }}</p>
                            <p class="text-sm text-gray-600 truncate">{{ $aluno->serie_ou_curso }}</p>
                            <p class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                </svg>
                                <span class="truncate">{{ $aluno->instituicao->nome }}</span>
                            </p>
                            <p class="text-sm font-medium text-brand-700">{{ $aluno->valorMensalFormatado() }}/mês</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-end gap-1">
                    <a
                        href="{{ route('admin.alunos.show', $aluno) }}"
                        wire:navigate
                        title="Mensalidades"
                        class="p-2.5 rounded-lg text-brand-700 hover:bg-brand-50 transition"
                    >
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                        </svg>
                    </a>

                    <a
                        href="{{ route('admin.alunos.edit', $aluno) }}"
                        wire:navigate
                        title="Editar"
                        class="p-2.5 rounded-lg text-gray-600 hover:bg-gray-50 transition"
                    >
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </a>

                    <button
                        wire:click="toggleAtivo({{ $aluno->id }})"
                        title="{{ $aluno->ativo ? 'Desativar' : 'Ativar' }}"
                        @class([
                            'p-2.5 rounded-lg transition',
                            'text-amber-600 hover:bg-amber-50' => $aluno->ativo,
                            'text-green-600 hover:bg-green-50' => ! $aluno->ativo,
                        ])
                    >
                        @if ($aluno->ativo)
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                            </svg>
                        @else
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 0 1 12.728 0M12 3v9m0 9v-9m0 9h9m-9 0H3" />
                            </svg>
                        @endif
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-xl border border-brand-100 p-12 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-400 mb-4">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Nenhum aluno encontrado</p>
                <p class="text-sm text-gray-500 mt-1">
                    @if (filled($busca))
                        Não há resultados para "{{ $busca }}".
                    @else
                        Convide um novo aluno para começar.
                    @endif
                </p>
            </div>
        @endforelse
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
