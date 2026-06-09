<?php

namespace App\Models;

use App\Enums\StatusComprovante;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comprovante extends Model
{
    protected $fillable = [
        'aluno_id',
        'arquivo',
        'mes_referencia',
        'observacao',
        'status',
        'mensalidade_id',
        'analisado_em',
    ];

    protected function casts(): array
    {
        return [
            'status' => StatusComprovante::class,
            'analisado_em' => 'datetime',
        ];
    }

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function mensalidade(): BelongsTo
    {
        return $this->belongsTo(Mensalidade::class);
    }

    public function scopePendentes(Builder $query): Builder
    {
        return $query->where('status', StatusComprovante::Pendente);
    }

    protected function arquivoUrl(): Attribute
    {
        return Attribute::get(fn () => '/storage/'.ltrim($this->arquivo, '/'));
    }
}
