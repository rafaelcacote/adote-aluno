<div class="max-w-xl">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Editar aluno</h1>

    <form wire:submit="salvar" class="bg-white rounded-xl border border-brand-100 p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Instituição</label>
            <select wire:model="instituicao_id" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500">
                <option value="">Selecione...</option>
                @foreach ($instituicoes as $inst)
                    <option value="{{ $inst->id }}">{{ $inst->nome }}</option>
                @endforeach
            </select>
            @error('instituicao_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Nome completo</label>
            <input wire:model="nome" type="text" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500">
            @error('nome') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Foto</label>
            @if ($aluno?->foto_url)
                <img src="{{ $aluno->foto_url }}" alt="" class="w-16 h-16 rounded-full object-cover mb-2">
            @endif
            <input wire:model="foto" type="file" accept="image/*" class="w-full text-sm">
            @error('foto') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Idade</label>
                <input wire:model="idade" type="number" min="5" max="99" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500">
                @error('idade') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Ano letivo</label>
                <input wire:model="ano_letivo" type="number" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500" @if($aluno) readonly @endif>
                @error('ano_letivo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tipo</label>
            <select wire:model="tipo" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500">
                <option value="colegio">Colégio</option>
                <option value="faculdade">Faculdade</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Série ou curso</label>
            <input wire:model="serie_ou_curso" type="text" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500">
            @error('serie_ou_curso') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Valor mensal (R$)</label>
            <input wire:model="valor_mensal" type="number" step="0.01" min="0" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500">
            @error('valor_mensal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <label class="flex items-center gap-2">
            <input wire:model="ativo" type="checkbox" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
            <span class="text-sm text-gray-700">Ativo (visível no site)</span>
        </label>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-lg hover:bg-brand-700">Salvar</button>
            <a href="{{ route('admin.alunos.index') }}" wire:navigate class="px-4 py-2 text-sm text-gray-600">Cancelar</a>
        </div>
    </form>
</div>
