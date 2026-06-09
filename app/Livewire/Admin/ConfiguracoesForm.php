<?php

namespace App\Livewire\Admin;

use App\Models\Configuracao;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class ConfiguracoesForm extends Component
{
    public string $titulo = '';

    public string $subtitulo = '';

    public string $texto_instrucao_pix = '';

    public string $texto_form_comprovante = '';

    public string $aviso_legal = '';

    public int $max_upload_mb = 5;

    public function mount(): void
    {
        $config = Configuracao::atual();
        $this->titulo = $config->titulo;
        $this->subtitulo = $config->subtitulo ?? '';
        $this->texto_instrucao_pix = $config->texto_instrucao_pix ?? '';
        $this->texto_form_comprovante = $config->texto_form_comprovante ?? '';
        $this->aviso_legal = $config->aviso_legal ?? '';
        $this->max_upload_mb = $config->max_upload_mb;
    }

    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:100'],
            'subtitulo' => ['nullable', 'string', 'max:1000'],
            'texto_instrucao_pix' => ['nullable', 'string', 'max:2000'],
            'texto_form_comprovante' => ['nullable', 'string', 'max:2000'],
            'aviso_legal' => ['nullable', 'string', 'max:2000'],
            'max_upload_mb' => ['required', 'integer', 'min:1', 'max:20'],
        ];
    }

    public function salvar(): void
    {
        Configuracao::atual()->update($this->validate());
        session()->flash('success', 'Configurações salvas.');
    }

    public function render()
    {
        return view('livewire.admin.configuracoes-form');
    }
}
