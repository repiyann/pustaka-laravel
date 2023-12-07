<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Book;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class BooksController extends Controller
{
    public function index(): View
    {
        return view('books.index');
    }

    public function addBooks(): View
    {
        return view('books.addBooks');
    }

    public function create(array $data)
    {
        $imagePath = $data['image']->store('books_images', 'public');

        // Create a new book record in the database
        return Book::create([
            'title' => $data['title'],
            'genre' => $data['genre'],
            'published_year' => $data['published_year'],
            'writer' => $data['writer'],
            'synopsis' => $data['synopsis'],
            'image' => $imagePath,
        ]);
    }

    public function postAddBook(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'published_year' => 'required|integer|min:1900|max:' . date('Y'),
            'writer' => 'required|string|max:255',
            'synopsis' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->all();
        $check = $this->create($data);

        return redirect("books")->withSuccess('Great! You have Successfully registered');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'published_year' => 'required|integer|min:1900|max:' . date('Y'),
            'writer' => 'required|string|max:255',
            'synopsis' => 'required|string',
            // Add any other validation rules for your fields
        ]);

        $book->update($request->all());

        return redirect()->route('books')->with('success', 'Book updated successfully!');
    }

    public function showBooks()
    {
        $books = Book::all();

        return view('books.index', ['books' => $books]);
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('books')->with('success', 'Book deleted successfully!');
    }
}
