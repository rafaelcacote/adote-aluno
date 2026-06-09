<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Configuracao extends Model
{
    protected $table = 'configuracoes';

    protected $fillable = [
        'titulo',
        'subtitulo',
        'texto_instrucao_pix',
        'texto_form_comprovante',
        'aviso_legal',
        'max_upload_mb',
    ];

    public static function atual(): self
    {
        return static::query()->firstOrCreate([], [
            'titulo' => config('app.name', 'Adote um Estudante'),
            'subtitulo' => 'Ajude com a mensalidade de um estudante. PIX direto para a instituição.',
            'texto_instrucao_pix' => 'Na descrição do PIX, escreva o nome do aluno.',
            'texto_form_comprovante' => 'Envie o comprovante do PIX após efetuar o pagamento.',
            'aviso_legal' => 'Este aplicativo não trabalha com PIX pessoal, somente com o PIX da instituição.',
            'max_upload_mb' => 5,
        ]);
    }
}
