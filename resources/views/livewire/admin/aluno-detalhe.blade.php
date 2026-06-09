<div>
    <div class="mb-6">
        <a href="{{ route('admin.alunos.index') }}" wire:navigate class="text-sm text-brand-700 font-medium">← Voltar</a>
        <h1 class="text-2xl font-bold text-gray-900 mt-2">{{ $aluno->nome }}</h1>
        <p class="text-sm text-gray-600">{{ $aluno->instituicao->nome }} · {{ $aluno->valorMensalFormatado() }}/mês · {{ $pagas }}/12 pagas</p>
    </div>

    <div class="bg-white rounded-xl border border-brand-100 p-6">
        <h2 class="font-semibold text-gray-900 mb-4">Mensalidades {{ $aluno->ano_letivo }}</h2>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
            @foreach ($mensalidades as $mensalidade)
                @php $pago = $mensalidade->status === \App\Enums\StatusMensalidade::Pago; @endphp
                <button
                    type="button"
                    wire:click="solicitarAcao({{ $mensalidade->id }}, '{{ $pago ? 'reverter' : 'pagar' }}')"
                    @class([
                        'rounded-xl p-3 text-center border-2 transition',
                        'border-brand-500 bg-brand-50' => $pago,
                        'border-gray-200 bg-white hover:border-brand-300' => ! $pago,
                    ])
                >
                    <p class="text-xs text-gray-500">{{ \App\Models\Mensalidade::nomeMes($mensalidade->mes) }}</p>
                    <p @class(['text-lg font-bold mt-1', 'text-brand-700' => $pago, 'text-gray-400' => ! $pago])>
                        {{ $pago ? '✓' : '○' }}
                    </p>
                </button>
            @endforeach
        </div>
        <p class="text-xs text-gray-500 mt-4">Clique em um mês pendente para marcar como pago, ou em um pago para reverter.</p>
    </div>

    @if ($confirmarMensalidadeId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40" wire:click.self="cancelarAcao">
            <div class="bg-white rounded-xl p-6 max-w-sm w-full shadow-xl">
                <p class="font-semibold text-gray-900">
                    {{ $acao === 'pagar' ? 'Marcar como pago?' : 'Reverter para pendente?' }}
                </p>
                <p class="text-sm text-gray-600 mt-2">
                    {{ $acao === 'pagar' ? 'Confirma o lançamento desta mensalidade como quitada?' : 'A mensalidade voltará a aparecer como pendente no site.' }}
                </p>
                <div class="flex gap-3 mt-6">
                    <button wire:click="confirmarAcao" class="flex-1 py-2 bg-brand-600 text-white text-sm font-semibold rounded-lg">Confirmar</button>
                    <button wire:click="cancelarAcao" class="flex-1 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg">Cancelar</button>
                </div>
            </div>
        </div>
    @endif
</div>
