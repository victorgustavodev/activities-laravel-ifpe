@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Editar Livro</h1>

    <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $book->title) }}" required>
            @error('title')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="publisher_id" class="form-label">Editora</label>
            <select class="form-select @error('publisher_id') is-invalid @enderror" id="publisher_id" name="publisher_id" required>
                <option value="" disabled>Selecione uma editora</option>
                @foreach($publishers as $publisher)
                    <option value="{{ $publisher->id }}" {{ $publisher->id == $book->publisher_id ? 'selected' : '' }}>
                        {{ $publisher->name }}
                    </option>
                @endforeach
            </select>
            @error('publisher_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="author_id" class="form-label">Autor</label>
            <select class="form-select @error('author_id') is-invalid @enderror" id="author_id" name="author_id" required>
                <option value="" disabled>Selecione um autor</option>
                @foreach($authors as $author)
                    <option value="{{ $author->id }}" {{ $author->id == $book->author_id ? 'selected' : '' }}>
                        {{ $author->name }}
                    </option>
                @endforeach
            </select>
            @error('author_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Categoria</label>
            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                <option value="" disabled>Selecione uma categoria</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $category->id == $book->category_id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Campo para upload da nova imagem de capa -->
        <div class="mb-3">
            <label for="cover_image" class="form-label">Alterar Imagem de Capa (opcional)</label>
            <input type="file" class="form-control @error('cover_image') is-invalid @enderror" id="cover_image" name="cover_image" accept="image/*">
            @error('cover_image')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Exibir imagem atual, se existir -->
        @if($book->cover_image)
            <div class="mb-3">
                <label class="form-label">Imagem Atual</label>
                <div>
                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="Capa do livro" style="max-width: 150px; border: 1px solid #ddd; padding: 5px;">
                </div>
            </div>
        @endif

        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="{{ route('books.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
