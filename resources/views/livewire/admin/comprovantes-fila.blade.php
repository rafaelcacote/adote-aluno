<div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Comprovantes pendentes</h1>

    <div class="bg-white rounded-xl border border-brand-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aluno</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden sm:table-cell">Enviado</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Mês ref.</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($comprovantes as $comprovante)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $comprovante->aluno->nome }}</p>
                            <p class="text-xs text-gray-500">{{ $comprovante->aluno->instituicao->nome }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden sm:table-cell">{{ $comprovante->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600 hidden md:table-cell">
                            {{ $comprovante->mes_referencia ? \App\Models\Mensalidade::nomeMes($comprovante->mes_referencia) : '—' }}
                        </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ $comprovante->arquivo_url }}" target="_blank" class="text-sm text-gray-600">Ver arquivo</a>
                            <button wire:click="iniciarAnalise({{ $comprovante->id }})" class="text-sm text-brand-700 font-medium">Analisar</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Nenhum comprovante pendente.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $comprovantes->links() }}</div>

    @if ($comprovanteAnalise)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
            <div class="bg-white rounded-xl p-6 max-w-md w-full shadow-xl">
                <h2 class="font-semibold text-gray-900">Analisar comprovante</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $comprovanteAnalise->aluno->nome }}</p>
                @if ($comprovanteAnalise->observacao)
                    <p class="text-sm text-gray-500 mt-2 italic">"{{ $comprovanteAnalise->observacao }}"</p>
                @endif
                <a href="{{ $comprovanteAnalise->arquivo_url }}" target="_blank" class="inline-block mt-3 text-sm text-brand-700 font-medium underline">Abrir comprovante</a>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Quitar mensalidade</label>
                    <select wire:model="mensalidade_id" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500">
                        <option value="">Selecione o mês...</option>
                        @foreach ($mensalidadesPendentes as $mensalidade)
                            <option value="{{ $mensalidade->id }}">
                                {{ \App\Models\Mensalidade::nomeMes($mensalidade->mes) }} — {{ number_format($mensalidade->valor, 2, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    @error('mensalidade_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    @if ($mensalidadesPendentes->isEmpty())
                        <p class="text-sm text-amber-700 mt-2">Todas as mensalidades deste aluno já estão pagas.</p>
                    @endif
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="confirmarAnalise" @disabled($mensalidadesPendentes->isEmpty()) class="flex-1 py-2 bg-brand-600 text-white text-sm font-semibold rounded-lg disabled:opacity-50">Confirmar e quitar</button>
                    <button wire:click="cancelarAnalise" class="flex-1 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg">Cancelar</button>
                </div>
            </div>
        </div>
    @endif
</div>
