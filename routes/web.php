<?php

use App\Livewire\Admin\AlunoDetalhe;
use App\Livewire\Admin\AlunoForm;
use App\Livewire\Admin\AlunosIndex;
use App\Livewire\Admin\ComprovantesFila;
use App\Livewire\Admin\ConfiguracoesForm;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\InstituicaoForm;
use App\Livewire\Admin\InstituicoesIndex;
use App\Livewire\AlunoDoar;
use App\Livewire\AlunoLista;
use App\Livewire\ComprovanteForm;
use Illuminate\Support\Facades\Route;

Route::get('/', AlunoLista::class)->name('home');

Route::get('/aluno/{aluno}/doar', AlunoDoar::class)->name('aluno.doar');
Route::get('/aluno/{aluno}/comprovante', ComprovanteForm::class)
    ->middleware('throttle:30,1')
    ->name('aluno.comprovante');

Route::redirect('/admin/login', '/login')->name('admin.login');
Route::redirect('/dashboard', '/admin')->name('dashboard');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/instituicoes', InstituicoesIndex::class)->name('instituicoes.index');
    Route::get('/instituicoes/criar', InstituicaoForm::class)->name('instituicoes.create');
    Route::get('/instituicoes/{instituicao}/editar', InstituicaoForm::class)->name('instituicoes.edit');
    Route::get('/alunos', AlunosIndex::class)->name('alunos.index');
    Route::get('/alunos/criar', AlunoForm::class)->name('alunos.create');
    Route::get('/alunos/{aluno}/editar', AlunoForm::class)->name('alunos.edit');
    Route::get('/alunos/{aluno}', AlunoDetalhe::class)->name('alunos.show');
    Route::get('/comprovantes', ComprovantesFila::class)->name('comprovantes.index');
    Route::get('/configuracoes', ConfiguracoesForm::class)->name('configuracoes');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
