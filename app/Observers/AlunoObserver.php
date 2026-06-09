<?php

namespace App\Observers;

use App\Models\Aluno;
use App\Services\MensalidadeService;

class AlunoObserver
{
    public function __construct(
        private MensalidadeService $mensalidadeService,
    ) {}

    public function created(Aluno $aluno): void
    {
        $this->mensalidadeService->gerarParaAluno($aluno);
    }
}
