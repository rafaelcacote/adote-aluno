<?php

namespace App\Livewire\Admin;

use App\Enums\StatusMensalidade;
use App\Models\Comprovante;
use App\Models\Mensalidade;
use App\Services\ComprovanteService;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class ComprovantesFila extends Component
{
    use WithPagination;

    public ?int $analisandoId = null;

    public ?int $mensalidade_id = null;

    public function iniciarAnalise(int $id): void
    {
        $this->analisandoId = $id;
        $this->mensalidade_id = null;
    }

    public function cancelarAnalise(): void
    {
        $this->analisandoId = null;
        $this->mensalidade_id = null;
    }

    public function confirmarAnalise(ComprovanteService $service): void
    {
        $this->validate([
            'mensalidade_id' => ['required', 'exists:mensalidades,id'],
        ]);

        $comprovante = Comprovante::pendentes()->findOrFail($this->analisandoId);
        $mensalidade = Mensalidade::query()->findOrFail($this->mensalidade_id);

        try {
            $service->analisar($comprovante, $mensalidade);
            session()->flash('success', 'Comprovante analisado e mensalidade quitada.');
        } catch (\InvalidArgumentException $e) {
            $this->addError('mensalidade_id', $e->getMessage());
            return;
        }

        $this->cancelarAnalise();
    }

    public function render()
    {
        $comprovanteAnalise = $this->analisandoId
            ? Comprovante::with('aluno')->find($this->analisandoId)
            : null;

        $mensalidadesPendentes = $comprovanteAnalise
            ? Mensalidade::query()
                ->where('aluno_id', $comprovanteAnalise->aluno_id)
                ->where('status', StatusMensalidade::Pendente)
                ->where('ano', $comprovanteAnalise->aluno->ano_letivo)
                ->orderBy('mes')
                ->get()
            : collect();

        return view('livewire.admin.comprovantes-fila', [
            'comprovantes' => Comprovante::pendentes()
                ->with('aluno.instituicao')
                ->latest()
                ->paginate(20),
            'comprovanteAnalise' => $comprovanteAnalise,
            'mensalidadesPendentes' => $mensalidadesPendentes,
        ]);
    }
}
