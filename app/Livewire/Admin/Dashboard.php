<?php

namespace App\Livewire\Admin;

use App\Enums\StatusMensalidade;
use App\Models\Aluno;
use App\Models\Comprovante;
use App\Models\Mensalidade;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    public function render()
    {
        $mesAtual = (int) now()->month;
        $anoAtual = (int) now()->year;

        return view('livewire.admin.dashboard', [
            'totalAlunos' => Aluno::ativos()->count(),
            'mensalidadesPagasMes' => Mensalidade::query()
                ->where('status', StatusMensalidade::Pago)
                ->where('mes', $mesAtual)
                ->where('ano', $anoAtual)
                ->count(),
            'comprovantesPendentes' => Comprovante::pendentes()->count(),
            'ultimosComprovantes' => Comprovante::pendentes()
                ->with('aluno')
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
