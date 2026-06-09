<?php

namespace Database\Seeders;

use App\Enums\StatusMensalidade;
use App\Enums\TipoAluno;
use App\Models\Aluno;
use App\Models\Configuracao;
use App\Models\Instituicao;
use App\Models\Mensalidade;
use App\Models\User;
use Illuminate\Database\Seeder;

class DomainSeeder extends Seeder
{
    public function run(): void
    {
        Configuracao::query()->create([
            'titulo' => 'Adote um Estudante',
            'subtitulo' => 'Ajude com a mensalidade de um estudante. O PIX vai direto para a instituição onde ele estuda.',
            'texto_instrucao_pix' => 'Na descrição do PIX, escreva o nome do aluno. Sem isso, o pagamento pode não ser identificado.',
            'texto_form_comprovante' => 'Após efetuar o PIX, envie o comprovante aqui. Sem comprovante, o pagamento não tem validade para o colégio.',
            'aviso_legal' => 'Este aplicativo não trabalha com PIX pessoal. Somente PIX da instituição onde o aluno está matriculado.',
            'max_upload_mb' => 5,
        ]);

        $iap = Instituicao::query()->create([
            'nome' => 'Instituto Adventista Paranaense',
            'cnpj' => '76.993.456/0001-30',
            'chave_pix' => 'financeiro@iap.edu.br',
            'nome_pix' => 'IAP - Instituto Adventista Paranaense',
            'ativo' => true,
        ]);

        $fap = Instituicao::query()->create([
            'nome' => 'Faculdade Adventista Paranaense',
            'cnpj' => '76.993.456/0002-11',
            'chave_pix' => '76.993.456/0002-11',
            'nome_pix' => 'FAP - Faculdade Adventista Paranaense',
            'ativo' => true,
        ]);

        $alunos = [
            [
                'instituicao_id' => $fap->id,
                'nome' => 'João Paulo da Silva',
                'idade' => 18,
                'tipo' => TipoAluno::Faculdade,
                'serie_ou_curso' => 'Contabilidade',
                'valor_mensal' => 1200.00,
                'meses_pagos' => 7,
            ],
            [
                'instituicao_id' => $iap->id,
                'nome' => 'Maria Santos Oliveira',
                'idade' => 15,
                'tipo' => TipoAluno::Colegio,
                'serie_ou_curso' => '2º ano EM',
                'valor_mensal' => 980.00,
                'meses_pagos' => 4,
            ],
            [
                'instituicao_id' => $iap->id,
                'nome' => 'Pedro Henrique Lima',
                'idade' => 16,
                'tipo' => TipoAluno::Colegio,
                'serie_ou_curso' => '3º ano EM',
                'valor_mensal' => 980.00,
                'meses_pagos' => 6,
            ],
            [
                'instituicao_id' => $fap->id,
                'nome' => 'Ana Carolina Mendes',
                'idade' => 20,
                'tipo' => TipoAluno::Faculdade,
                'serie_ou_curso' => 'Pedagogia',
                'valor_mensal' => 1150.00,
                'meses_pagos' => 3,
            ],
            [
                'instituicao_id' => $fap->id,
                'nome' => 'Lucas Ferreira Souza',
                'idade' => 19,
                'tipo' => TipoAluno::Faculdade,
                'serie_ou_curso' => 'Administração',
                'valor_mensal' => 1100.00,
                'meses_pagos' => 0,
            ],
        ];

        $anoLetivo = (int) now()->year;

        foreach ($alunos as $dados) {
            $mesesPagos = $dados['meses_pagos'];
            unset($dados['meses_pagos']);

            $aluno = Aluno::query()->create([
                ...$dados,
                'ano_letivo' => $anoLetivo,
                'ativo' => true,
            ]);

            if ($mesesPagos > 0) {
                Mensalidade::query()
                    ->where('aluno_id', $aluno->id)
                    ->where('ano', $anoLetivo)
                    ->where('mes', '<=', $mesesPagos)
                    ->update([
                        'status' => StatusMensalidade::Pago,
                        'pago_em' => now(),
                    ]);
            }
        }

        User::query()->updateOrCreate(
            ['email' => 'admin@adotealuno.test'],
            [
                'name' => 'Administrador',
                'password' => 'senha123',
            ],
        );
    }
}
