<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ConviteAluno extends Model
{
    protected $table = 'convites_aluno';

    protected $fillable = [
        'token',
        'instituicao_id',
        'created_by',
        'ano_letivo',
        'expires_at',
        'used_at',
        'aluno_id',
    ];

    protected function casts(): array
    {
        return [
            'ano_letivo' => 'integer',
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function instituicao(): BelongsTo
    {
        return $this->belongsTo(Instituicao::class);
    }

    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public static function gerar(int $instituicaoId, int $userId, ?int $anoLetivo = null): self
    {
        return self::query()->create([
            'token' => Str::uuid()->toString(),
            'instituicao_id' => $instituicaoId,
            'created_by' => $userId,
            'ano_letivo' => $anoLetivo ?? (int) now()->year,
            'expires_at' => now()->addDays(30),
        ]);
    }

    public function isValid(): bool
    {
        return $this->used_at === null && $this->expires_at->isFuture();
    }

    public function url(): string
    {
        return route('aluno.cadastro', $this->token);
    }

    public function marcarComoUsado(Aluno $aluno): void
    {
        $this->update([
            'used_at' => now(),
            'aluno_id' => $aluno->id,
        ]);
    }
}
