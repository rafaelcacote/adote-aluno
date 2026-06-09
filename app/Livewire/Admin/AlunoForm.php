<?php

namespace App\Livewire\Admin;

use App\Enums\TipoAluno;
use App\Models\Aluno;
use App\Models\Instituicao;
use App\Services\ImageService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class AlunoForm extends Component
{
    use WithFileUploads;

    public ?Aluno $aluno = null;

    public ?int $instituicao_id = null;

    public string $nome = '';

    public $foto;

    public int $idade = 18;

    public string $tipo = 'colegio';

    public string $serie_ou_curso = '';

    public string $valor_mensal = '';

    public int $ano_letivo;

    public bool $ativo = true;

    public function mount(?Aluno $aluno = null): void
    {
        $this->ano_letivo = (int) now()->year;

        if ($aluno?->exists) {
            $this->aluno = $aluno;
            $this->instituicao_id = $aluno->instituicao_id;
            $this->nome = $aluno->nome;
            $this->idade = $aluno->idade;
            $this->tipo = $aluno->tipo->value;
            $this->serie_ou_curso = $aluno->serie_ou_curso;
            $this->valor_mensal = (string) $aluno->valor_mensal;
            $this->ano_letivo = $aluno->ano_letivo;
            $this->ativo = $aluno->ativo;
        }
    }

    public function rules(): array
    {
        return [
            'instituicao_id' => ['required', 'exists:instituicoes,id'],
            'nome' => ['required', 'string', 'max:200'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'idade' => ['required', 'integer', 'min:5', 'max:99'],
            'tipo' => ['required', Rule::enum(TipoAluno::class)],
            'serie_ou_curso' => ['required', 'string', 'max:100'],
            'valor_mensal' => ['required', 'numeric', 'min:0'],
            'ano_letivo' => ['required', 'integer', 'min:2020', 'max:2100'],
            'ativo' => ['boolean'],
        ];
    }

    public function salvar(ImageService $images): void
    {
        $dados = $this->validate();
        unset($dados['foto']);

        if ($this->foto) {
            $dados['foto'] = $images->storeAlunoFoto($this->foto);
        }

        if ($this->aluno) {
            $this->aluno->update($dados);
            session()->flash('success', 'Aluno atualizado.');
            $this->redirect(route('admin.alunos.show', $this->aluno), navigate: true);
        } else {
            $aluno = Aluno::query()->create($dados);
            session()->flash('success', 'Aluno cadastrado com 12 mensalidades geradas.');
            $this->redirect(route('admin.alunos.show', $aluno), navigate: true);
        }
    }

    public function render()
    {
        return view('livewire.admin.aluno-form', [
            'instituicoes' => Instituicao::query()->where('ativo', true)->orderBy('nome')->get(),
        ]);
    }
}
