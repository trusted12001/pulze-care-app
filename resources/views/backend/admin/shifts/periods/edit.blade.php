@extends('layouts.admin')

@section('title', 'Edit Rota Period')

@section('content')
    <div class="max-w-3xl mx-auto p-4 sm:p-6">

        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-semibold">Edit Rota Period</h1>

            <a href="{{ route('backend.admin.rota-periods.index') }}"
                class="px-3 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                Back
            </a>
        </div>

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-800 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
            <form method="POST" action="{{ route('backend.admin.rota-periods.update', $rota_period) }}" class="p-4 sm:p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div class="sm:col-span-2">
                        <label class="block mb-1 text-sm font-medium text-gray-700">Location</label>
                        <select name="location_id" class="w-full border rounded px-3 py-2" required>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" @selected(old('location_id', $rota_period->location_id) == $loc->id)>
                                    {{ $loc->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" name="start_date"
                            value="{{ old('start_date', optional($rota_period->start_date)->format('Y-m-d')) }}"
                            class="w-full border rounded px-3 py-2" required>
                        @error('start_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" name="end_date"
                            value="{{ old('end_date', optional($rota_period->end_date)->format('Y-m-d')) }}"
                            class="w-full border rounded px-3 py-2" required>
                        @error('end_date') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                </div>

                <div class="mt-5 flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded hover:bg-teal-700">
                        Save Changes
                    </button>
                    <a href="{{ route('backend.admin.rota-periods.index') }}"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </a>
                </div>
            </form>
        </div>

    </div>
@endsection