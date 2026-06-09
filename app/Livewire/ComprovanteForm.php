<?php

namespace App\Livewire;

use App\Enums\StatusComprovante;
use App\Models\Aluno;
use App\Models\Comprovante;
use App\Models\Configuracao;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.public')]
class ComprovanteForm extends Component
{
    use WithFileUploads;

    public Aluno $aluno;

    public $arquivo;

    public ?int $mes_referencia = null;

    public string $observacao = '';

    public bool $enviado = false;

    public function mount(Aluno $aluno): void
    {
        abort_unless($aluno->ativo, 404);

        $this->aluno = $aluno->load('instituicao');
    }

    public function rules(): array
    {
        $maxKb = Configuracao::atual()->max_upload_mb * 1024;

        return [
            'arquivo' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:'.$maxKb],
            'mes_referencia' => ['nullable', 'integer', 'between:1,12'],
            'observacao' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function enviar(): void
    {
        $this->validate();

        $path = $this->arquivo->store('comprovantes/'.$this->aluno->id, 'public');

        Comprovante::query()->create([
            'aluno_id' => $this->aluno->id,
            'arquivo' => $path,
            'mes_referencia' => $this->mes_referencia,
            'observacao' => $this->observacao ?: null,
            'status' => StatusComprovante::Pendente,
        ]);

        $this->reset(['arquivo', 'mes_referencia', 'observacao']);
        $this->enviado = true;
    }

    public function render()
    {
        return view('livewire.comprovante-form', [
            'config' => Configuracao::atual(),
        ]);
    }
}
