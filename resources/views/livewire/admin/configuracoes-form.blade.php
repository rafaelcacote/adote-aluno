<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Configurações</h1>

    <form wire:submit="salvar" class="bg-white rounded-xl border border-brand-100 p-6 space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Título do site</label>
            <input wire:model="titulo" type="text" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500">
            @error('titulo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Subtítulo da home</label>
            <textarea wire:model="subtitulo" rows="2" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Instruções na tela do PIX</label>
            <textarea wire:model="texto_instrucao_pix" rows="3" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Instruções no formulário de comprovante</label>
            <textarea wire:model="texto_form_comprovante" rows="3" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Aviso legal</label>
            <textarea wire:model="aviso_legal" rows="2" class="mt-1 w-full rounded-lg border-gray-300 focus:ring-brand-500"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Tamanho máximo de upload (MB)</label>
            <input wire:model="max_upload_mb" type="number" min="1" max="20" class="mt-1 w-32 rounded-lg border-gray-300 focus:ring-brand-500">
            @error('max_upload_mb') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <button type="submit" class="px-4 py-2 bg-brand-600 text-white text-sm font-semibold rounded-lg hover:bg-brand-700">Salvar configurações</button>
    </form>
</div>
