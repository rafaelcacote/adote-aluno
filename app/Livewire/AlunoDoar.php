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

    public bool $modalMensalidadesAberto = false;

    public function mount(Aluno $aluno): void
    {
        abort_unless($aluno->ativo, 404);

        $this->aluno = $aluno->load([
            'instituicao',
            'mensalidades' => fn ($q) => $q
                ->where('ano', $aluno->ano_letivo)
                ->orderBy('mes'),
        ]);
    }

    public function abrirMensalidades(): void
    {
        $this->modalMensalidadesAberto = true;
    }

    public function fecharMensalidades(): void
    {
        $this->modalMensalidadesAberto = false;
    }

    public function render()
    {
        return view('livewire.aluno-doar', [
            'config' => Configuracao::atual(),
        ]);
    }
}
