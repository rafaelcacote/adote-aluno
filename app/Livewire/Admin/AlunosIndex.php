<?php

namespace App\Livewire\Admin;

use App\Models\Aluno;
use App\Models\ConviteAluno;
use App\Models\Instituicao;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class AlunosIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $busca = '';

    public bool $modalConviteAberto = false;

    public ?int $instituicao_id = null;

    public int $ano_letivo;

    public ?string $linkGerado = null;

    public function mount(): void
    {
        $this->ano_letivo = (int) now()->year;
    }

    public function updatedBusca(): void
    {
        $this->resetPage();
    }

    public function abrirConvite(): void
    {
        $this->modalConviteAberto = true;
        $this->instituicao_id = null;
        $this->linkGerado = null;
        $this->ano_letivo = (int) now()->year;
    }

    public function fecharConvite(): void
    {
        $this->modalConviteAberto = false;
        $this->instituicao_id = null;
        $this->linkGerado = null;
    }

    public function gerarConvite(): void
    {
        $this->validate([
            'instituicao_id' => ['required', 'exists:instituicoes,id'],
            'ano_letivo' => ['required', 'integer', 'min:2020', 'max:2100'],
        ]);

        $convite = ConviteAluno::gerar(
            $this->instituicao_id,
            auth()->id(),
            $this->ano_letivo,
        );

        $this->linkGerado = $convite->url();
        $this->resetPage('convitesPage');
        session()->flash('success', 'Convite gerado! Envie o link para o aluno se cadastrar.');
    }

    public function toggleAtivo(int $id): void
    {
        $aluno = Aluno::query()->findOrFail($id);
        $aluno->update(['ativo' => ! $aluno->ativo]);
        session()->flash('success', 'Status do aluno atualizado.');
    }

    public function render()
    {
        return view('livewire.admin.alunos-index', [
            'alunos' => Aluno::query()
                ->with('instituicao')
                ->buscarPorNome($this->busca)
                ->orderBy('nome')
                ->paginate(20),
            'instituicoes' => Instituicao::query()->where('ativo', true)->orderBy('nome')->get(),
            'convites' => ConviteAluno::query()
                ->with(['instituicao', 'aluno'])
                ->latest()
                ->paginate(15, pageName: 'convitesPage'),
        ]);
    }
}
