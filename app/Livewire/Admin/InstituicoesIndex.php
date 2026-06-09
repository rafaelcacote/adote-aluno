<?php

namespace App\Livewire\Admin;

use App\Models\Instituicao;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class InstituicoesIndex extends Component
{
    use WithPagination;

    public function toggleAtivo(int $id): void
    {
        $instituicao = Instituicao::query()->findOrFail($id);
        $instituicao->update(['ativo' => ! $instituicao->ativo]);
        session()->flash('success', 'Status da instituição atualizado.');
    }

    public function render()
    {
        return view('livewire.admin.instituicoes-index', [
            'instituicoes' => Instituicao::query()->orderBy('nome')->paginate(20),
        ]);
    }
}
