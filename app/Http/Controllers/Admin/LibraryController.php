<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCategory;
use App\Models\BookLoan;
use App\Services\TenantManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LibraryController extends Controller
{
    public function __construct(protected TenantManager $tenantManager) {}

    public function index(): View
    {
        $mosque = $this->tenantManager->getMosque();

        $books = Book::where('mosque_id', $mosque->id)
            ->with(['category', 'loans'])
            ->orderBy('title')
            ->paginate(15);

        $activeLoans = BookLoan::whereHas('book', fn($q) => $q->where('mosque_id', $mosque->id))
            ->where('status', 'BORROWED')
            ->with('book')
            ->orderBy('due_date')
            ->get();

        $categories = BookCategory::where('mosque_id', $mosque->id)->get();

        return view('admin.library.index', compact('mosque', 'books', 'activeLoans', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $mosque = $this->tenantManager->getMosque();

        $validated = $request->validate([
            'category_id' => 'nullable|exists:book_categories,id',
            'book_code' => 'required|string|max:50',
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'year_published' => 'nullable|integer',
            'language' => 'required|string|max:50',
            'copies_total' => 'required|integer|min:1',
            'shelf_location' => 'nullable|string|max:100',
        ]);

        $validated['mosque_id'] = $mosque->id;
        $validated['copies_available'] = $validated['copies_total'];

        Book::create($validated);

        return back()->with('success', 'Kitab / Buku baru berhasil ditambahkan ke katalog perpustakaan.');
    }

    public function storeLoan(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'borrower_name' => 'required|string|max:255',
            'borrower_phone' => 'required|string|max:30',
            'loan_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:loan_date',
            'notes' => 'nullable|string',
        ]);

        $validated['processed_by_id'] = Auth::id();
        $validated['status'] = 'BORROWED';

        BookLoan::create($validated);

        $book = Book::find($validated['book_id']);
        if ($book && $book->copies_available > 0) {
            $book->decrement('copies_available');
        }

        return back()->with('success', 'Peminjaman buku berhasil dicatat.');
    }

    public function returnLoan(BookLoan $loan): RedirectResponse
    {
        $loan->update([
            'status' => 'RETURNED',
            'return_date' => now()->toDateString(),
        ]);

        if ($loan->book) {
            $loan->book->increment('copies_available');
        }

        return back()->with('success', 'Pengembalian buku berhasil diproses.');
    }
}
