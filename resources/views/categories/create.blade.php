<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Nouvelle Catégorie</span>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </x-slot>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nom de la catégorie <span style="color: red;">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                @error('name')
                    <div class="alert alert-error" style="margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                @error('description')
                    <div class="alert alert-error" style="margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary">Créer la catégorie</button>
            </div>
        </form>
    </div>
</x-app-layout>
