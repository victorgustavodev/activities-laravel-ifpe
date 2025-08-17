<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe a lista paginada de usuários
     */
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Exibe os detalhes de um usuário
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Exibe o formulário de edição de um usuário
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Atualiza os dados do usuário, incluindo papel (role)
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,bibliotecario,cliente',
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->role  = $validated['role'];
        $user->save();

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }
}
