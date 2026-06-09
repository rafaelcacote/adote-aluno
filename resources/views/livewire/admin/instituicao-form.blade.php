<div class="max-w-xl">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">
        {{ $instituicao ? 'Editar instituição' : 'Nova instituição' }}
    </h1>

    <form wire:submit="salvar" class="bg-white rounded-xl border border-brand-100 p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Nome</label>
            <input wire:model="nome" type="text" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500">
            @error('nome') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">CNPJ</label>
            <input wire:model="cnpj" type="text" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500">
            @error('cnpj') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Chave PIX</label>
            <input wire:model="chave_pix" type="text" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500">
            @error('chave_pix') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Nome exibido no PIX</label>
            <input wire:model="nome_pix" type="text" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500 focus:border-brand-500">
            @error('nome_pix') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <label class="flex items-center gap-2">
            <input wire:model="ativo" type="checkbox" class="rounded border-gray-300 text-brand-600 focus:ring-brand-500">
            <span class="text-sm text-gray-700">Ativa</span>
        </label>
        <div class="flex gap-3 pt-2">
            <button type="submit" class="px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-lg hover:bg-brand-700">Salvar</button>
            <a href="{{ route('admin.instituicoes.index') }}" wire:navigate class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancelar</a>
        </div>
    </form>
</div>
