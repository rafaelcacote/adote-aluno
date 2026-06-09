<?php

namespace App\Livewire\Admin;

use App\Models\Aluno;
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

    public function updatedBusca(): void
    {
        $this->resetPage();
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
        ]);
    }
}
