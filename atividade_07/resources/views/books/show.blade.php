@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Detalhes do Livro</h1>

    {{-- Verifica primeiro se a coluna image_url não é nula ou vazia --}}
@if($book->image_url)
    <div class="mb-4">
        {{-- Agora, verifica se é a imagem padrão ou uma imagem de upload --}}
        @if($book->image_url == 'images/default.jpg')
            {{-- CASO 1: É a imagem padrão. O caminho já é relativo à pasta 'public'. --}}
            <img src="{{ asset($book->image_url) }}" alt="Capa Padrão" class="img-fluid" style="width: 200px;">
        @else
            {{-- CASO 2: É uma imagem de upload. Precisamos adicionar o prefixo 'storage/'. --}}
            <img src="{{ asset('storage/' . $book->image_url) }}" alt="Capa do Livro" class="img-fluid" style="width: 200px;">
        @endif
    </div>
@endif

    <!-- Formulário para Empréstimos -->
<div class="card mb-4">
    <div class="card-header">Registrar Empréstimo</div>
    <div class="card-body">
        <form action="{{ route('books.borrow', $book) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="user_id" class="form-label">Usuário</label>
                <select class="form-select" id="user_id" name="user_id" required>
                    <option value="" selected>Selecione um usuário</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-success">Registrar Empréstimo</button>
        </form>
    </div>
</div>

<!-- Histórico de Empréstimos -->
<div class="card">
    <div class="card-header">Histórico de Empréstimos</div>
    <div class="card-body">
        @if($book->users->isEmpty())
            <p>Nenhum empréstimo registrado.</p>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Data de Empréstimo</th>
                        <th>Data de Devolução</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
    @foreach($book->users as $user)
        <tr>
            <td>
                <a href="{{ route('users.show', $user->id) }}">
                    {{ $user->name }}
                </a>
            </td>
            <td>{{ $user->pivot->borrowed_at }}</td>
            <td>{{ $user->pivot->returned_at ?? 'Em Aberto' }}</td>
            <td>
                @if(is_null($user->pivot->returned_at))
                    <form action="{{ route('borrowings.return', $user->pivot->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-warning btn-sm">Devolver</button>
                    </form>
                @endif
            </td>
        </tr>
    @endforeach
</tbody>
            </table>
        @endif
    </div>
</div>

</div>
@endsection
