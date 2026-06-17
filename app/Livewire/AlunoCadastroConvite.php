<?php

namespace App\Livewire;

use App\Enums\TipoAluno;
use App\Models\Aluno;
use App\Models\ConviteAluno;
use App\Services\ImageService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.public')]
class AlunoCadastroConvite extends Component
{
    use WithFileUploads;

    public ?ConviteAluno $convite = null;

    public string $nome = '';

    public $foto;

    public int $idade = 18;

    public string $tipo = 'colegio';

    public string $serie_ou_curso = '';

    public string $valor_mensal = '';

    public bool $cadastrado = false;

    public bool $conviteInvalido = false;

    public ?string $mensagemInvalido = null;

    public function mount(string $token): void
    {
        $convite = ConviteAluno::query()
            ->with('instituicao')
            ->where('token', $token)
            ->first();

        if (! $convite) {
            $this->conviteInvalido = true;
            $this->mensagemInvalido = 'Este link de convite não existe ou é inválido.';

            return;
        }

        if ($convite->used_at) {
            $this->conviteInvalido = true;
            $this->mensagemInvalido = 'Este link já foi utilizado para um cadastro.';

            return;
        }

        if ($convite->expires_at->isPast()) {
            $this->conviteInvalido = true;
            $this->mensagemInvalido = 'Este link de convite expirou. Solicite um novo link ao administrador.';

            return;
        }

        $this->convite = $convite;
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:200'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'idade' => ['required', 'integer', 'min:5', 'max:99'],
            'tipo' => ['required', Rule::enum(TipoAluno::class)],
            'serie_ou_curso' => ['required', 'string', 'max:100'],
            'valor_mensal' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function cadastrar(ImageService $images): void
    {
        if ($this->conviteInvalido || ! $this->convite) {
            return;
        }

        $dados = $this->validate();
        unset($dados['foto']);

        $dados['instituicao_id'] = $this->convite->instituicao_id;
        $dados['ano_letivo'] = $this->convite->ano_letivo;
        $dados['ativo'] = false;

        if ($this->foto) {
            $dados['foto'] = $images->storeAlunoFoto($this->foto);
        }

        $aluno = Aluno::query()->create($dados);
        $this->convite->marcarComoUsado($aluno);

        $this->cadastrado = true;
    }

    public function render()
    {
        return view('livewire.aluno-cadastro-convite');
    }
}
