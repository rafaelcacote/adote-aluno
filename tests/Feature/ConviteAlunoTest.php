<?php

namespace Tests\Feature;

use App\Models\ConviteAluno;
use App\Models\Instituicao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ConviteAlunoTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_generate_invitation_link(): void
    {
        $user = User::factory()->create();
        $instituicao = Instituicao::query()->create([
            'nome' => 'Escola Teste',
            'cnpj' => '00.000.000/0001-00',
            'chave_pix' => 'teste@escola.com',
            'nome_pix' => 'Escola Teste',
            'ativo' => true,
        ]);

        $this->actingAs($user)
            ->get(route('admin.alunos.index'))
            ->assertOk();

        Livewire::actingAs($user)
            ->test(\App\Livewire\Admin\AlunosIndex::class)
            ->call('abrirConvite')
            ->set('instituicao_id', $instituicao->id)
            ->set('ano_letivo', 2026)
            ->call('gerarConvite')
            ->assertSet('linkGerado', fn ($link) => str_contains($link, '/cadastro/'));

        $this->assertDatabaseCount('convites_aluno', 1);
    }

    public function test_student_can_register_via_invitation_link(): void
    {
        $user = User::factory()->create();
        $instituicao = Instituicao::query()->create([
            'nome' => 'Escola Teste',
            'cnpj' => '00.000.000/0001-00',
            'chave_pix' => 'teste@escola.com',
            'nome_pix' => 'Escola Teste',
            'ativo' => true,
        ]);

        $convite = ConviteAluno::gerar($instituicao->id, $user->id, 2026);

        $this->get(route('aluno.cadastro', $convite->token))
            ->assertOk();

        Livewire::test(\App\Livewire\AlunoCadastroConvite::class, ['token' => $convite->token])
            ->set('nome', 'Carlos Aluno')
            ->set('idade', 17)
            ->set('tipo', 'colegio')
            ->set('serie_ou_curso', '1º ano EM')
            ->set('valor_mensal', '850.00')
            ->call('cadastrar')
            ->assertSet('cadastrado', true);

        $this->assertDatabaseHas('alunos', [
            'nome' => 'Carlos Aluno',
            'instituicao_id' => $instituicao->id,
            'ativo' => false,
        ]);

        $convite->refresh();
        $this->assertNotNull($convite->used_at);
        $this->assertNotNull($convite->aluno_id);
    }

    public function test_used_invitation_shows_error(): void
    {
        $user = User::factory()->create();
        $instituicao = Instituicao::query()->create([
            'nome' => 'Escola Teste',
            'cnpj' => '00.000.000/0001-00',
            'chave_pix' => 'teste@escola.com',
            'nome_pix' => 'Escola Teste',
            'ativo' => true,
        ]);

        $convite = ConviteAluno::gerar($instituicao->id, $user->id);
        $convite->update(['used_at' => now()]);

        Livewire::test(\App\Livewire\AlunoCadastroConvite::class, ['token' => $convite->token])
            ->assertSet('conviteInvalido', true);
    }
}
