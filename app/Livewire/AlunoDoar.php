<?php

namespace App\Livewire;

use App\Models\Aluno;
use App\Models\Configuracao;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class AlunoDoar extends Component
{
    public Aluno $aluno;

    public function mount(Aluno $aluno): void
    {
        abort_unless($aluno->ativo, 404);

        $this->aluno = $aluno->load('instituicao');
    }

    public function render()
    {
        return view('livewire.aluno-doar', [
            'config' => Configuracao::atual(),
        ]);
    }
}
