<?php

namespace App\Models;

use App\Enums\StatusMensalidade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mensalidade extends Model
{
    protected $fillable = [
        'aluno_id',
        'mes',
        'ano',
        'valor',
        'status',
        'pago_em',
        'observacao',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusMensalidade::class,
            'valor' => 'decimal:2',
            'pago_em' => 'datetime',
        ];
    }

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function comprovante(): HasOne
    {
        return $this->hasOne(Comprovante::class);
    }

    public static function nomeMes(int $mes): string
    {
        return match ($mes) {
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
            default => (string) $mes,
        };
    }
}
