<?php

use App\Livewire\Actions\Logout;
use App\Models\Comprovante;
use Livewire\Volt\Component;

new class extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }

    public function comprovantesPendentes(): int
    {
        return Comprovante::pendentes()->count();
    }
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-brand-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-14">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-2">
                    <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brand-600 text-white text-xs font-bold">AE</span>
                    <span class="font-bold text-brand-800 hidden sm:inline">Admin</span>
                </a>
                <div class="hidden sm:flex items-center gap-1">
                    @foreach ([
                        ['admin.dashboard', 'Painel'],
                        ['admin.alunos.index', 'Alunos'],
                        ['admin.instituicoes.index', 'Instituições'],
                        ['admin.comprovantes.index', 'Comprovantes'],
                        ['admin.configuracoes', 'Configurações'],
                    ] as [$route, $label])
                        <a
                            href="{{ route($route) }}"
                            wire:navigate
                            @class([
                                'px-3 py-2 rounded-lg text-sm font-medium transition',
                                'bg-brand-50 text-brand-800' => request()->routeIs($route.'*') || ($route === 'admin.alunos.index' && request()->routeIs('admin.alunos.*')),
                                'text-gray-600 hover:bg-gray-50' => ! (request()->routeIs($route.'*') || ($route === 'admin.alunos.index' && request()->routeIs('admin.alunos.*'))),
                            ])
                        >
                            {{ $label }}
                            @if ($route === 'admin.comprovantes.index' && $this->comprovantesPendentes() > 0)
                                <span class="ml-1 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold bg-amber-500 text-white rounded-full">
                                    {{ $this->comprovantesPendentes() }}
                                </span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" wire:navigate class="text-sm text-gray-500 hover:text-brand-700 hidden sm:inline">Ver site</a>
                <button wire:click="logout" class="text-sm text-gray-600 hover:text-red-600 font-medium">Sair</button>
                <button @click="open = !open" class="sm:hidden p-2 text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>
    <div x-show="open" x-cloak class="sm:hidden border-t border-gray-100 px-4 py-2 space-y-1">
        @foreach ([
            ['admin.dashboard', 'Painel'],
            ['admin.alunos.index', 'Alunos'],
            ['admin.instituicoes.index', 'Instituições'],
            ['admin.comprovantes.index', 'Comprovantes'],
            ['admin.configuracoes', 'Configurações'],
        ] as [$route, $label])
            <a href="{{ route($route) }}" wire:navigate class="block px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">{{ $label }}</a>
        @endforeach
        <a href="{{ route('home') }}" wire:navigate class="block px-3 py-2 text-sm text-gray-500">Ver site público</a>
    </div>
</nav>
