@extends('layouts.sidebar')

@section('title', 'Add Expense')

@section('content')
    <div class="py-6">
        <div class="max-w-5xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100">Record New Expense</h2>
                        <a href="{{ route('expenses.index') }}"
                            class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400">Back to list</a>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 text-red-700 px-4 py-2 rounded">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('expenses.store') }}" method="POST" enctype="multipart/form-data">
                        @include('expenses.partials.form', ['submitLabel' => 'Save Expense'])
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
