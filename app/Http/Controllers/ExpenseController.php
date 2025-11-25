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
    public function index(): View
    {
        $expenses = Expense::with(['apartment', 'lease'])
            ->latest('expense_date')
            ->paginate(10);

        return view('expenses.index', compact('expenses'));
    }

    public function create(): View
    {
        return view('expenses.create', $this->formSelections());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateExpense($request);
        $data['attachments'] = $this->normalizeAttachments($request->string('attachments')->toString());

        if ($request->hasFile('receipt_file')) {
            $data['receipt_file'] = $request->file('receipt_file')->store('expenses/receipts', 'public');
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
        $data['attachments'] = $this->normalizeAttachments($request->string('attachments')->toString());

        if ($request->hasFile('receipt_file')) {
            if ($expense->receipt_file) {
                Storage::disk('public')->delete($expense->receipt_file);
            }

            $data['receipt_file'] = $request->file('receipt_file')->store('expenses/receipts', 'public');
        } else {
            unset($data['receipt_file']);
        }

        $expense->update($data);

        return redirect()->route('expenses.show', $expense)->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense): RedirectResponse
    {
        if ($expense->receipt_file) {
            Storage::disk('public')->delete($expense->receipt_file);
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
            'attachments' => 'nullable|string',
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
