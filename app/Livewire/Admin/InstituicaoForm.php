<?php

namespace App\Livewire\Admin;

use App\Models\Instituicao;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class InstituicaoForm extends Component
{
    public ?Instituicao $instituicao = null;

    public string $nome = '';

    public string $cnpj = '';

    public string $chave_pix = '';

    public string $nome_pix = '';

    public bool $ativo = true;

    public function mount(?Instituicao $instituicao = null): void
    {
        if ($instituicao?->exists) {
            $this->instituicao = $instituicao;
            $this->nome = $instituicao->nome;
            $this->cnpj = $instituicao->cnpj;
            $this->chave_pix = $instituicao->chave_pix;
            $this->nome_pix = $instituicao->nome_pix;
            $this->ativo = $instituicao->ativo;
        }
    }

    public function rules(): array
    {
        return [
            'nome' => ['required', 'string', 'max:200'],
            'cnpj' => ['required', 'string', 'max:18'],
            'chave_pix' => ['required', 'string', 'max:255'],
            'nome_pix' => ['required', 'string', 'max:200'],
            'ativo' => ['boolean'],
        ];
    }

    public function salvar(): void
    {
        $dados = $this->validate();

        if ($this->instituicao) {
            $this->instituicao->update($dados);
            session()->flash('success', 'Instituição atualizada.');
        } else {
            Instituicao::query()->create($dados);
            session()->flash('success', 'Instituição cadastrada.');
        }

        $this->redirect(route('admin.instituicoes.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.instituicao-form');
    }
}
