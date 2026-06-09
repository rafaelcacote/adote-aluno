<?php

namespace App\Services;

use App\Enums\StatusMensalidade;
use App\Models\Aluno;
use App\Models\Mensalidade;

class MensalidadeService
{
    public const MESES_POR_ANO = 12;

    public function gerarParaAluno(Aluno $aluno): void
    {
        $existentes = Mensalidade::query()
            ->where('aluno_id', $aluno->id)
            ->where('ano', $aluno->ano_letivo)
            ->count();

        if ($existentes >= self::MESES_POR_ANO) {
            return;
        }

        for ($mes = 1; $mes <= self::MESES_POR_ANO; $mes++) {
            Mensalidade::query()->firstOrCreate(
                [
                    'aluno_id' => $aluno->id,
                    'mes' => $mes,
                    'ano' => $aluno->ano_letivo,
                ],
                [
                    'valor' => $aluno->valor_mensal,
                    'status' => StatusMensalidade::Pendente,
                ],
            );
        }
    }

    public function marcarComoPago(Mensalidade $mensalidade, ?string $observacao = null): void
    {
        if ($mensalidade->status === StatusMensalidade::Pago) {
            return;
        }

        $mensalidade->update([
            'status' => StatusMensalidade::Pago,
            'pago_em' => now(),
            'observacao' => $observacao,
        ]);
    }

    public function marcarComoPendente(Mensalidade $mensalidade): void
    {
        $mensalidade->update([
            'status' => StatusMensalidade::Pendente,
            'pago_em' => null,
            'observacao' => null,
        ]);
    }
}
