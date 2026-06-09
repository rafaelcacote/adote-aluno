<?php

namespace App\Services;

use App\Enums\StatusComprovante;
use App\Enums\StatusMensalidade;
use App\Models\Comprovante;
use App\Models\Mensalidade;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ComprovanteService
{
    public function __construct(
        private MensalidadeService $mensalidadeService,
    ) {}

    public function analisar(Comprovante $comprovante, Mensalidade $mensalidade): void
    {
        if ($comprovante->status === StatusComprovante::Analisado) {
            throw new InvalidArgumentException('Comprovante já foi analisado.');
        }

        if ($mensalidade->aluno_id !== $comprovante->aluno_id) {
            throw new InvalidArgumentException('A mensalidade não pertence ao aluno do comprovante.');
        }

        if ($mensalidade->status === StatusMensalidade::Pago) {
            throw new InvalidArgumentException('Esta mensalidade já está paga.');
        }

        DB::transaction(function () use ($comprovante, $mensalidade) {
            $this->mensalidadeService->marcarComoPago(
                $mensalidade,
                'Quitado via comprovante #'.$comprovante->id,
            );

            $comprovante->update([
                'status' => StatusComprovante::Analisado,
                'mensalidade_id' => $mensalidade->id,
                'analisado_em' => now(),
            ]);
        });
    }
}
