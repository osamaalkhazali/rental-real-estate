@extends('layouts.sidebar')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold">Edit Lease - {{ $lease->tenant_name }} (تعديل العقد)</h2>
                        <a href="{{ route('leases.index') }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to List (العودة للقائمة)
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('leases.update', $lease) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="apartment_id" class="block text-sm font-medium mb-2">Apartment (الشقة) *</label>
                                <select name="apartment_id" id="apartment_id" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Select Apartment</option>
                                    @foreach ($apartments as $apartment)
                                        <option value="{{ $apartment->id }}"
                                            {{ old('apartment_id', $lease->apartment_id) == $apartment->id ? 'selected' : '' }}>
                                            {{ $apartment->display_name }} -
                                            {{ $apartment->is_available ? 'Available' : 'Occupied' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="tenant_name" class="block text-sm font-medium mb-2">Tenant Name (اسم المستأجر) *</label>
                                <input type="text" name="tenant_name" id="tenant_name"
                                    value="{{ old('tenant_name', $lease->tenant_name) }}" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="tenant_email" class="block text-sm font-medium mb-2">Tenant Email (البريد الإلكتروني)</label>
                                <input type="email" name="tenant_email" id="tenant_email"
                                    value="{{ old('tenant_email', $lease->tenant_email) }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="tenant_phone" class="block text-sm font-medium mb-2">Tenant Phone (هاتف المستأجر) *</label>
                                <input type="text" name="tenant_phone" id="tenant_phone"
                                    value="{{ old('tenant_phone', $lease->tenant_phone) }}" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="national_id" class="block text-sm font-medium mb-2">National ID (الرقم الوطني)</label>
                                <input type="text" name="national_id" id="national_id"
                                    value="{{ old('national_id', $lease->national_id) }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="start_date" class="block text-sm font-medium mb-2">Start Date (تاريخ البدء) *</label>
                                <input type="date" name="start_date" id="start_date"
                                    value="{{ old('start_date', $lease->start_date) }}" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium mb-2">End Date (تاريخ الانتهاء) *</label>
                                <input type="date" name="end_date" id="end_date"
                                    value="{{ old('end_date', $lease->end_date) }}" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="deposit_amount" class="block text-sm font-medium mb-2">Deposit Amount (مبلغ التأمين)</label>
                                <input type="number" step="0.01" name="deposit_amount" id="deposit_amount"
                                    value="{{ old('deposit_amount', $lease->deposit_amount) }}"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            @if ($lease->tenant_image)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium mb-2">Current Tenant Photo (صورة المستأجر الحالية)</label>
                                    <img src="{{ route('private.file.view', $lease->tenant_image) }}" alt="Tenant Photo"
                                        class="w-32 h-32 object-cover rounded">
                                </div>
                            @endif

                            <div>
                                <label for="tenant_image" class="block text-sm font-medium mb-2">Tenant Photo
                                    (صورة المستأجر) {{ $lease->tenant_image ? '(Replace)' : '' }}</label>
                                <input type="file" name="tenant_image" id="tenant_image" accept="image/*"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                    <label for="national_id_image" class="block text-sm font-medium mb-2">National ID Image
                                    (صورة الهوية) {{ $lease->national_id_image ? '(Replace)' : '' }}</label>
                                <input type="file" name="national_id_image" id="national_id_image" accept="image/*"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="md:col-span-2">
                                <label for="lease_document" class="block text-sm font-medium mb-2">Lease Document
                                    (PDF/Image) (ملف العقد) {{ $lease->lease_document ? '(Replace)' : '' }}</label>
                                <input type="file" name="lease_document" id="lease_document" accept="image/*,.pdf"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium mb-2">Notes (ملاحظات)</label>
                                <textarea name="notes" id="notes" rows="3"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('notes', $lease->notes) }}</textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <a href="{{ route('leases.index') }}"
                                class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Cancel (إلغاء)
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Lease (تحديث العقد)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
