<?php

namespace App\Livewire\Admin;

use App\Enums\StatusMensalidade;
use App\Models\Aluno;
use App\Models\Mensalidade;
use App\Services\MensalidadeService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class AlunoDetalhe extends Component
{
    public Aluno $aluno;

    public ?int $confirmarMensalidadeId = null;

    public string $acao = '';

    public function mount(Aluno $aluno): void
    {
        $this->aluno = $aluno->load(['instituicao', 'mensalidades' => fn ($q) => $q->orderBy('mes')]);
    }

    public function solicitarAcao(int $mensalidadeId, string $acao): void
    {
        $this->confirmarMensalidadeId = $mensalidadeId;
        $this->acao = $acao;
    }

    public function cancelarAcao(): void
    {
        $this->confirmarMensalidadeId = null;
        $this->acao = '';
    }

    public function confirmarAcao(MensalidadeService $service): void
    {
        $mensalidade = Mensalidade::query()
            ->where('aluno_id', $this->aluno->id)
            ->findOrFail($this->confirmarMensalidadeId);

        if ($this->acao === 'pagar') {
            $service->marcarComoPago($mensalidade);
            session()->flash('success', Mensalidade::nomeMes($mensalidade->mes).' marcado como pago.');
        } elseif ($this->acao === 'reverter') {
            $service->marcarComoPendente($mensalidade);
            session()->flash('success', Mensalidade::nomeMes($mensalidade->mes).' revertido para pendente.');
        }

        $this->cancelarAcao();
        $this->aluno->load(['mensalidades' => fn ($q) => $q->orderBy('mes')]);
    }

    public function render()
    {
        $mensalidades = $this->aluno->mensalidadesDoAno();

        return view('livewire.admin.aluno-detalhe', [
            'mensalidades' => $mensalidades,
            'pagas' => $mensalidades->where('status', StatusMensalidade::Pago)->count(),
        ]);
    }
}
