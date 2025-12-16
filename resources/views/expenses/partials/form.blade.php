@csrf
@if (isset($expense))
    @method('PUT')
@endif

<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Apartment<span
                    class="text-red-500">*</span></label>
            <select name="apartment_id" required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                <option value="">Select apartment</option>
                @foreach ($apartments as $apartment)
                    <option value="{{ $apartment->id }}" @selected(old('apartment_id', $expense->apartment_id ?? '') == $apartment->id)>
                        {{ $apartment->display_name }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-500 mt-1">Expenses can always be tied to the apartment even if no lease exists.
            </p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Lease (optional)</label>
            <select name="lease_id"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                <option value="">No lease</option>
                @foreach ($leases as $lease)
                    <option value="{{ $lease->id }}" @selected(old('lease_id', $expense->lease_id ?? '') == $lease->id)>
                        {{ $lease->tenant_name }} •
                        {{ $lease->apartment->display_name ?? 'Apartment #' . $lease->apartment_id }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type<span
                    class="text-red-500">*</span></label>
            <select name="type" required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                @php
                    $types = [
                        'maintenance',
                        'lighting',
                        'furniture',
                        'roofing',
                        'fees',
                        'cleaning',
                        'painting',
                        'plumbing',
                        'electrical',
                        'other',
                    ];
                @endphp
                @foreach ($types as $type)
                    <option value="{{ $type }}" @selected(old('type', $expense->type ?? '') == $type)>
                        {{ ucfirst($type) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title (العنوان)<span
                    class="text-red-500">*</span></label>
            <input type="text" name="title" value="{{ old('title', $expense->title ?? '') }}" required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Expense Date (تاريخ المصروف)<span
                    class="text-red-500">*</span></label>
            <input type="date" name="expense_date"
                value="{{ old('expense_date', optional($expense->expense_date ?? null)->format('Y-m-d')) }}" required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount (JOD) (المبلغ)<span
                    class="text-red-500">*</span></label>
            <input type="number" step="0.01" min="0" name="amount"
                value="{{ old('amount', $expense->amount ?? '') }}" required
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vendor Name (اسم المورد)</label>
            <input type="text" name="vendor_name" value="{{ old('vendor_name', $expense->vendor_name ?? '') }}"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Receipt File (ملف الإيصال)</label>
            <input type="file" name="receipt_file"
                class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
            @if (!empty($expense?->receipt_file))
                <p class="text-xs text-gray-500 mt-1">Current: <a
                        href="{{ route('private.file', $expense->receipt_file) }}" target="_blank"
                        class="text-blue-600 dark:text-blue-400 underline">Download</a></p>
            @endif
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description (الوصف)</label>
        <textarea name="description" rows="3"
            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">{{ old('description', $expense->description ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Attachments (المرفقات)</label>
        <input type="file" name="attachments[]" multiple
            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100" />
        <p class="text-xs text-gray-500 mt-1">You can select multiple files (images, PDFs, etc.)</p>
        @if (!empty($expense?->attachments))
            <div class="mt-2">
                <p class="text-xs text-gray-500 mb-1">Current attachments:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($expense->attachments as $attachment)
                        <li>
                            <a href="{{ route('private.file', $attachment) }}" target="_blank"
                                class="text-blue-600 dark:text-blue-400 underline">{{ basename($attachment) }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes (ملاحظات)</label>
        <textarea name="notes" rows="3"
            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">{{ old('notes', $expense->notes ?? '') }}</textarea>
    </div>

    <div class="flex justify-end gap-3">
        <a href="{{ route('expenses.index') }}"
            class="px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200">Cancel</a>
        <button type="submit"
            class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">{{ $submitLabel ?? 'Save Expense' }}</button>
    </div>
</div>
