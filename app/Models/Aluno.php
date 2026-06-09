<?php

namespace App\Models;

use App\Enums\StatusMensalidade;
use App\Enums\TipoAluno;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Aluno extends Model
{
    protected $fillable = [
        'instituicao_id',
        'nome',
        'foto',
        'idade',
        'tipo',
        'serie_ou_curso',
        'valor_mensal',
        'ano_letivo',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'tipo' => TipoAluno::class,
            'valor_mensal' => 'decimal:2',
            'ativo' => 'boolean',
        ];
    }

    public function instituicao(): BelongsTo
    {
        return $this->belongsTo(Instituicao::class);
    }

    public function mensalidades(): HasMany
    {
        return $this->hasMany(Mensalidade::class);
    }

    public function comprovantes(): HasMany
    {
        return $this->hasMany(Comprovante::class);
    }

    public function scopeAtivos(Builder $query): Builder
    {
        return $query->where('ativo', true);
    }

    public function scopeBuscarPorNome(Builder $query, ?string $termo): Builder
    {
        if (blank($termo)) {
            return $query;
        }

        return $query->where('nome', 'like', '%'.$termo.'%');
    }

    public function mensalidadesPagasCount(): int
    {
        if (isset($this->mensalidades_pagas_count)) {
            return (int) $this->mensalidades_pagas_count;
        }

        return $this->mensalidades()
            ->where('ano', $this->ano_letivo)
            ->where('status', StatusMensalidade::Pago)
            ->count();
    }

    public function mensalidadesDoAno()
    {
        if ($this->relationLoaded('mensalidades')) {
            return $this->mensalidades
                ->where('ano', $this->ano_letivo)
                ->sortBy('mes')
                ->values();
        }

        return $this->mensalidades()
            ->where('ano', $this->ano_letivo)
            ->orderBy('mes')
            ->get();
    }

    protected function fotoUrl(): Attribute
    {
        return Attribute::get(fn () => $this->foto
            ? '/storage/'.ltrim($this->foto, '/')
            : null);
    }

    protected function iniciais(): Attribute
    {
        return Attribute::get(function () {
            $partes = Str::of($this->nome)->explode(' ')->filter();

            if ($partes->count() >= 2) {
                return strtoupper($partes->first()[0].$partes->last()[0]);
            }

            return strtoupper(Str::substr($this->nome, 0, 2));
        });
    }

    public function valorMensalFormatado(): string
    {
        return 'R$ '.number_format((float) $this->valor_mensal, 2, ',', '.');
    }
}
