<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Alunos</h1>
        <a href="{{ route('admin.alunos.create') }}" wire:navigate class="inline-flex justify-center px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-lg hover:bg-brand-700">
            Novo aluno
        </a>
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
</div>
