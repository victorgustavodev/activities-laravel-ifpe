@extends('layouts.app')

@section('content')
    <h1>Usuários com Débito</h1>

    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif

    @if($users->isEmpty())
        <p>Nenhum usuário com débito pendente.</p>
    @else
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Débito (R$)</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ number_format($user->debit, 2, ',', '.') }}</td>
                        <td>
                            <form action="{{ route('debitos.clear', $user) }}" method="POST" onsubmit="return confirm('Confirma zerar o débito deste usuário?');">
                                @csrf
                                <button type="submit">Zerar Débito</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
