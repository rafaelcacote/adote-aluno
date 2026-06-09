<?php

namespace App\Livewire;

use App\Enums\StatusMensalidade;
use App\Models\Aluno;
use App\Models\Configuracao;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.public')]
class AlunoLista extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public string $busca = '';

    public function updatedBusca(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $config = Configuracao::atual();

        $alunos = Aluno::query()
            ->ativos()
            ->with('instituicao')
            ->with(['mensalidades' => fn ($q) => $q->orderBy('mes')])
            ->withCount([
                'mensalidades as mensalidades_pagas_count' => fn ($q) => $q
                    ->where('status', StatusMensalidade::Pago)
                    ->whereRaw('mensalidades.ano = alunos.ano_letivo'),
            ])
            ->buscarPorNome($this->busca)
            ->orderBy('nome')
            ->paginate(20);

        return view('livewire.aluno-lista', [
            'alunos' => $alunos,
            'config' => $config,
        ]);
    }
}
