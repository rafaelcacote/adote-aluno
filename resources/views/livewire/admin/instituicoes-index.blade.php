<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Instituições</h1>
        <a href="{{ route('admin.instituicoes.create') }}" wire:navigate class="inline-flex justify-center px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-lg hover:bg-brand-700">
            Nova instituição
        </a>
    </div>

    <div class="bg-white rounded-xl border border-brand-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nome</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden sm:table-cell">CNPJ</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">PIX</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($instituicoes as $instituicao)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $instituicao->nome }}</p>
                            @unless ($instituicao->ativo)
                                <span class="text-xs text-red-600">Inativa</span>
                            @endunless
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell">{{ $instituicao->cnpj }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden md:table-cell truncate max-w-[200px]">{{ $instituicao->chave_pix }}</td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('admin.instituicoes.edit', $instituicao) }}" wire:navigate class="text-sm text-brand-700 font-medium">Editar</a>
                            <button wire:click="toggleAtivo({{ $instituicao->id }})" class="text-sm text-gray-500 hover:text-gray-800">
                                {{ $instituicao->ativo ? 'Desativar' : 'Ativar' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Nenhuma instituição cadastrada.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $instituicoes->links() }}</div>
</div>
