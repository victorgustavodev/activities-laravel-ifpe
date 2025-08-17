<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DebitController extends Controller
{
    public function index()
    {
        // Pega todos os usuários com débito maior que zero
        $users = User::where('debit', '>', 0)->get();

        return view('debitos.index', compact('users'));
    }

    public function clear(User $user)
    {
        $user->debit = 0;
        $user->save();

        return redirect()->route('debitos.index')->with('success', 'Débito zerado com sucesso.');
    }
}
