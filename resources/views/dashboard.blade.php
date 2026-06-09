<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-brand-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-brand-100">
                <div class="p-6 text-gray-900">
                    <p class="font-medium">Painel administrativo</p>
                    <p class="mt-2 text-sm text-gray-600">
                        Login funcionando. Na Fase 1 cadastramos instituições, alunos e mensalidades.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
