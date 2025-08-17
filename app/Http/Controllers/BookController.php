<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    // Formulário com input de ID
    public function createWithId()
    {
        return view('books.create-id');
    }

    // Salvar livro com input de ID
    public function storeWithId(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    // Formulário com input select
    public function createWithSelect()
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.create-select', compact('publishers', 'authors', 'categories'));
    }

    // Salvar livro com input select
    public function storeWithSelect(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    public function edit(Book $book)
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.edit', compact('book', 'publishers', 'authors', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('cover_image')) {
            // Apaga imagem antiga se existir
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            // Salva nova imagem
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Livro atualizado com sucesso.');
    }

    public function destroy(Book $book)
    {
        // Apaga imagem do storage se existir
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livro deletado com sucesso.');
    }

    public function show(Book $book)
    {
        // Carregando autor, editora e categoria do livro com eager loading
        $book->load(['author', 'publisher', 'category']);

        // Carregar todos os usuários para o formulário de empréstimo (se aplicável)
        $users = User::all();

        return view('books.show', compact('book', 'users'));
    }

    public function index()
    {
        // Carregar os livros com autores usando eager loading e paginação
        $books = Book::with('author')->paginate(20);

        return view('books.index', compact('books'));
    }
}
