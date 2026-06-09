<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instituicoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 200);
            $table->string('cnpj', 18);
            $table->string('chave_pix', 255);
            $table->string('nome_pix', 200);
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instituicao_id')->constrained('instituicoes')->cascadeOnDelete();
            $table->string('nome', 200);
            $table->string('foto')->nullable();
            $table->unsignedTinyInteger('idade');
            $table->enum('tipo', ['colegio', 'faculdade']);
            $table->string('serie_ou_curso', 100);
            $table->decimal('valor_mensal', 10, 2);
            $table->unsignedSmallInteger('ano_letivo');
            $table->boolean('ativo')->default(true);
            $table->timestamps();

            $table->index('nome');
            $table->index('ativo');
        });

        Schema::create('mensalidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->cascadeOnDelete();
            $table->unsignedTinyInteger('mes');
            $table->unsignedSmallInteger('ano');
            $table->decimal('valor', 10, 2);
            $table->enum('status', ['pendente', 'pago'])->default('pendente');
            $table->timestamp('pago_em')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->unique(['aluno_id', 'mes', 'ano']);
            $table->index(['aluno_id', 'ano']);
        });

        Schema::create('comprovantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->cascadeOnDelete();
            $table->string('arquivo', 255);
            $table->unsignedTinyInteger('mes_referencia')->nullable();
            $table->text('observacao')->nullable();
            $table->enum('status', ['pendente', 'analisado'])->default('pendente');
            $table->foreignId('mensalidade_id')->nullable()->constrained('mensalidades')->nullOnDelete();
            $table->timestamp('analisado_em')->nullable();
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 100);
            $table->text('subtitulo')->nullable();
            $table->text('texto_instrucao_pix')->nullable();
            $table->text('texto_form_comprovante')->nullable();
            $table->text('aviso_legal')->nullable();
            $table->unsignedTinyInteger('max_upload_mb')->default(5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comprovantes');
        Schema::dropIfExists('mensalidades');
        Schema::dropIfExists('alunos');
        Schema::dropIfExists('instituicoes');
        Schema::dropIfExists('configuracoes');
    }
};
