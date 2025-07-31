@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('Você está logado!') }}
                </div>

                <div class="card-links">
                
                </div>
            </div>
            <ol class=" list-none flex flex-col gap-3 my-4">
                <li><a href="/books" class="text-lg text-black">Acessar Livros</a></li>
                <li><a href="/"></a></li>
</ol>
            
        </div>
    </div>
</div>
@endsection
