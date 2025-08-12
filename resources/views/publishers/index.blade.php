@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="my-4">Lista de Editoras</h1>

    @can('create', App\Models\Publisher::class)
    <a href="{{ route('publishers.create') }}" class="btn btn-success mb-3">
        <i class="bi bi-plus"></i> Adicionar Editora
    </a>
@else
    <button onclick="showUnauthorized()" class="btn btn-success mb-3">
        <i class="bi bi-plus"></i> Adicionar Editora
    </button>

    <script>
        function showUnauthorized() {
            alert('Acesso não autorizado.');
        }
    </script>
@endcan

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($publishers as $publisher)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $publisher->name }}</td>
                    <td>
                        <!-- Botão de Visualizar -->
                        <a href="{{ route('publishers.show', $publisher) }}" class="btn btn-info btn-sm">
                            <i class="bi bi-eye"></i> Visualizar
                        </a>

                        <!-- Botão de Editar -->
                        @can('update', App\Models\Publisher::class)
                        <a href="{{ route('publishers.edit', $publisher) }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        @endcan

                        <!-- Botão de Excluir -->
                        @can('delete', App\Models\Publisher::class)
                        <form action="{{ route('publishers.destroy', $publisher) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Deseja excluir esta Editora?')">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Nenhuma editora encontrada.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection