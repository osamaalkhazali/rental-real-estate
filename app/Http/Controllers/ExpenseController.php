<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Expense;
use App\Models\Lease;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Expense::with(['apartment', 'lease']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('vendor_name', 'like', "%{$search}%")
                    ->orWhereHas('apartment', function ($aq) use ($search) {
                        $aq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by apartment
        if ($request->filled('apartment_id')) {
            $query->where('apartment_id', $request->apartment_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by lease
        if ($request->filled('lease_id')) {
            $query->where('lease_id', $request->lease_id);
        }

        // Filter by amount range
        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('expense_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('expense_date', '<=', $request->to_date);
        }

        // Sorting
        $sortField = $request->get('sort', 'expense_date');
        $sortDirection = $request->get('direction', 'desc');
        $allowedSorts = ['apartment', 'lease', 'title', 'type', 'expense_date', 'amount'];
        
        if (in_array($sortField, $allowedSorts)) {
            if ($sortField === 'apartment') {
                $query->orderBy(
                    Apartment::select('name')->whereColumn('apartments.id', 'expenses.apartment_id'),
                    $sortDirection === 'asc' ? 'asc' : 'desc'
                );
            } elseif ($sortField === 'lease') {
                $query->orderBy(
                    Lease::select('tenant_name')->whereColumn('leases.id', 'expenses.lease_id'),
                    $sortDirection === 'asc' ? 'asc' : 'desc'
                );
            } else {
                $query->orderBy($sortField, $sortDirection === 'asc' ? 'asc' : 'desc');
            }
        } else {
            $query->latest('expense_date');
        }

        $expenses = $query->paginate(10)->withQueryString();
        $apartments = Apartment::orderBy('name')->get();
        $leases = Lease::with('apartment')->orderBy('tenant_name')->get();
        $types = ['maintenance', 'lighting', 'furniture', 'roofing', 'fees', 'cleaning', 'painting', 'plumbing', 'electrical', 'other'];

        return view('expenses.index', compact('expenses', 'apartments', 'types', 'leases'));
    }

    public function create(): View
    {
        return view('expenses.create', $this->formSelections());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateExpense($request);

        if ($request->hasFile('receipt_file')) {
            $data['receipt_file'] = $request->file('receipt_file')->store('expenses/receipts', 'private');
        }

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            $attachmentPaths = [];
            foreach ($request->file('attachments') as $file) {
                $attachmentPaths[] = $file->store('expenses/attachments', 'private');
            }
            $data['attachments'] = $attachmentPaths;
        }

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function show(Expense $expense): View
    {
        $expense->load(['apartment', 'lease']);

        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense): View
    {
        return view('expenses.edit', array_merge(
            ['expense' => $expense],
            $this->formSelections()
        ));
    }

    public function update(Request $request, Expense $expense): RedirectResponse
    {
        $data = $this->validateExpense($request);

        if ($request->hasFile('receipt_file')) {
            if ($expense->receipt_file) {
                Storage::disk('private')->delete($expense->receipt_file);
            }

            $data['receipt_file'] = $request->file('receipt_file')->store('expenses/receipts', 'private');
        } else {
            unset($data['receipt_file']);
        }

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            // Delete old attachments
            if (!empty($expense->attachments)) {
                foreach ($expense->attachments as $oldAttachment) {
                    Storage::disk('private')->delete($oldAttachment);
                }
            }

            $attachmentPaths = [];
            foreach ($request->file('attachments') as $file) {
                $attachmentPaths[] = $file->store('expenses/attachments', 'private');
            }
            $data['attachments'] = $attachmentPaths;
        } else {
            unset($data['attachments']);
        }

        $expense->update($data);

        return redirect()->route('expenses.show', $expense)->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        if ($expense->receipt_file) {
            Storage::disk('private')->delete($expense->receipt_file);
        }

        // Delete attachments
        if (!empty($expense->attachments)) {
            foreach ($expense->attachments as $attachment) {
                Storage::disk('private')->delete($attachment);
            }
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }

    private function validateExpense(Request $request): array
    {
        return $request->validate([
            'apartment_id' => 'required|exists:apartments,id',
            'lease_id' => 'nullable|exists:leases,id',
            'type' => 'required|in:maintenance,lighting,furniture,roofing,fees,cleaning,painting,plumbing,electrical,other',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'vendor_name' => 'nullable|string|max:255',
            'receipt_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240',
            'notes' => 'nullable|string',
        ]);
    }

    private function normalizeAttachments(?string $value): ?array
    {
        if (blank($value)) {
            return null;
        }

        return collect(preg_split('/[\n,]+/', $value))
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->values()
            ->all();
    }

    private function formSelections(): array
    {
        return [
            'apartments' => Apartment::orderBy('name')->get(),
            'leases' => Lease::with('apartment')->orderBy('tenant_name')->get(),
        ];
    }
}
