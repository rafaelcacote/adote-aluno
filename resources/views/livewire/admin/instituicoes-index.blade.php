<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Instituições</h1>
            <p class="text-sm text-gray-600 mt-1">Gerencie as instituições parceiras do programa.</p>
        </div>
        <a href="{{ route('admin.instituicoes.create') }}" wire:navigate class="inline-flex justify-center px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-lg hover:bg-brand-700">
            Nova instituição
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @forelse ($instituicoes as $instituicao)
            <div @class([
                'bg-white rounded-xl border p-5 shadow-sm flex flex-col',
                'border-brand-100' => $instituicao->ativo,
                'border-gray-200 opacity-75' => ! $instituicao->ativo,
            ])>
                <div class="flex items-start gap-4">
                    <div @class([
                        'flex h-12 w-12 shrink-0 items-center justify-center rounded-xl',
                        'bg-brand-100 text-brand-700' => $instituicao->ativo,
                        'bg-gray-100 text-gray-400' => ! $instituicao->ativo,
                    ])>
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                        </svg>
                    </div>

                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 leading-tight">{{ $instituicao->nome }}</h3>
                        @unless ($instituicao->ativo)
                            <span class="inline-flex mt-1.5 text-xs font-medium text-red-700 bg-red-50 px-2 py-0.5 rounded-full">Inativa</span>
                        @endunless

                        <div class="mt-3 space-y-1.5">
                            <p class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Zm6-10.125a1.875 1.875 0 1 1-3.75 0 1.875 1.875 0 0 1 3.75 0Zm1.294 6.336a6.721 6.721 0 0 1-3.17.789 6.721 6.721 0 0 1-3.168-.789 3.376 3.376 0 0 0 6.338 0Z" />
                                </svg>
                                <span class="truncate">{{ $instituicao->cnpj }}</span>
                            </p>
                            <p class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                </svg>
                                <span class="truncate" title="{{ $instituicao->chave_pix }}">{{ $instituicao->chave_pix }}</span>
                            </p>
                            <p class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 shrink-0 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                                <span class="truncate" title="{{ $instituicao->nome_pix }}">{{ $instituicao->nome_pix }}</span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-end gap-1">
                    <a
                        href="{{ route('admin.instituicoes.edit', $instituicao) }}"
                        wire:navigate
                        title="Editar"
                        class="p-2.5 rounded-lg text-gray-600 hover:bg-gray-50 transition"
                    >
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                    </a>

                    <button
                        wire:click="toggleAtivo({{ $instituicao->id }})"
                        title="{{ $instituicao->ativo ? 'Desativar' : 'Ativar' }}"
                        @class([
                            'p-2.5 rounded-lg transition',
                            'text-amber-600 hover:bg-amber-50' => $instituicao->ativo,
                            'text-green-600 hover:bg-green-50' => ! $instituicao->ativo,
                        ])
                    >
                        @if ($instituicao->ativo)
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Nenhuma instituição cadastrada</p>
                <p class="text-sm text-gray-500 mt-1">Cadastre a primeira instituição para começar.</p>
            </div>
        @endforelse
    </div>
    <div class="mt-4">{{ $instituicoes->links() }}</div>
</div>
