@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                @if (session('status'))
                <div class="card-body">

                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>

                </div>
                @endif
                <div>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="/books">Livros</a></li>
                        <li class="list-group-item"><a href="/authors">Autores (Acesso Admin)</a></li>
                        <li class="list-group-item"><a href="/publishers">Editoras (Acesso Admin)</a></li>
                        <li class="list-group-item"><a href="/categories">Categorias (Acesso Admin)</a></li>
                        <li class="list-group-item"><a href="/users">Usuarios (Acesso Admin)</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
