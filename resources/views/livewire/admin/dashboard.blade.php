<div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Painel</h1>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl border border-brand-100 p-5">
            <p class="text-sm text-gray-500">Alunos ativos</p>
            <p class="text-3xl font-bold text-brand-700 mt-1">{{ $totalAlunos }}</p>
        </div>
        <div class="bg-white rounded-xl border border-brand-100 p-5">
            <p class="text-sm text-gray-500">Mensalidades pagas este mês</p>
            <p class="text-3xl font-bold text-brand-700 mt-1">{{ $mensalidadesPagasMes }}</p>
        </div>
        <div class="bg-white rounded-xl border border-brand-100 p-5">
            <p class="text-sm text-gray-500">Comprovantes pendentes</p>
            <p class="text-3xl font-bold {{ $comprovantesPendentes > 0 ? 'text-amber-600' : 'text-brand-700' }} mt-1">
                {{ $comprovantesPendentes }}
            </p>
        </div>
    </div>

    @if ($ultimosComprovantes->isNotEmpty())
        <div class="bg-white rounded-xl border border-brand-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-gray-900">Comprovantes aguardando análise</h2>
                <a href="{{ route('admin.comprovantes.index') }}" wire:navigate class="text-sm text-brand-700 font-medium">Ver todos</a>
            </div>
            <ul class="divide-y divide-gray-100">
                @foreach ($ultimosComprovantes as $comprovante)
                    <li class="px-5 py-3 flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-900">{{ $comprovante->aluno->nome }}</p>
                            <p class="text-xs text-gray-500">{{ $comprovante->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('admin.comprovantes.index') }}" wire:navigate class="text-sm text-brand-600 font-medium">Analisar</a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
