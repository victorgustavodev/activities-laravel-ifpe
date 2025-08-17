<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;

class BorrowingController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->debit > 0) {
            return redirect()->route('books.show', $book)
                             ->with('error', 'Usuário possui débito pendente e não pode realizar novos empréstimos.');
        }

        $openBorrowing = Borrowing::where('book_id', $book->id)
                                  ->whereNull('returned_at')
                                  ->first();

        if ($openBorrowing) {
            return redirect()->route('books.show', $book)
                             ->with('error', 'Este livro já está emprestado e não foi devolvido.');
        }

        $openBorrowingsCount = Borrowing::where('user_id', $user->id)
                                       ->whereNull('returned_at')
                                       ->count();

        if ($openBorrowingsCount >= 5) {
            return redirect()->route('books.show', $book)
                             ->with('error', 'O usuário já possui 5 livros emprestados. Limite atingido.');
        }

        Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
        ]);

        return redirect()->route('books.show', $book)->with('success', 'Empréstimo registrado com sucesso.');
    }

    public function returnBook(Borrowing $borrowing)
    {
        $now = now();
        $borrowedAt = $borrowing->borrowed_at;
        $dueDate = $borrowedAt->copy()->addDays(15);

        $lateDays = $now->diffInDays($dueDate, false);

        if ($lateDays < 0) {
            $fine = abs($lateDays) * 0.50;

            $user = $borrowing->user;
            $user->debit += $fine;
            $user->save();
        }

        $borrowing->update([
            'returned_at' => $now,
        ]);

        $message = 'Devolução registrada com sucesso.';
        if (isset($fine) && $fine > 0) {
            $message .= " Multa de R$ " . number_format($fine, 2) . " aplicada.";
        }

        return redirect()->route('books.show', $borrowing->book_id)
                         ->with('success', $message);
    }

    public function userBorrowings(User $user)
    {
        $borrowings = $user->books()->withPivot('borrowed_at', 'returned_at')->get();

        return view('users.borrowings', compact('user', 'borrowings'));
    }
}
