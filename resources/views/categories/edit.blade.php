<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Éditer la catégorie : {{ $category->name }}</span>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </x-slot>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">Nom de la catégorie <span style="color: red;">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="alert alert-error" style="margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <div class="alert alert-error" style="margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: space-between;">
                <!-- Delete Button -->
                <button type="button" class="btn btn-danger" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')) document.getElementById('delete-form').submit();">
                    Supprimer
                </button>

                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>

        <form id="delete-form" action="{{ route('categories.destroy', $category) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</x-app-layout>
