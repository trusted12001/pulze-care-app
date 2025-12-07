@extends('layouts.admin')

@section('title', 'Update Passport Photo')

@section('content')
    <div class="max-w-3xl mx-auto py-6 px-3 sm:px-4 lg:px-6">
        <div class="mb-6 flex items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Update Passport Photo — {{ $su->full_name }}
                </h1>
                <p class="text-sm text-gray-600 mt-1">
                    Upload a clear head-and-shoulders photo for this service user.
                </p>
            </div>
            <a href="{{ route('backend.admin.service-users.show', $su->id) }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                <i class="ph ph-arrow-left"></i>
                <span>Back to Profile</span>
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg">
                <div class="flex items-center gap-2 text-sm">
                    <i class="ph ph-check-circle text-green-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg">
                <div class="flex items-start gap-2 text-sm">
                    <i class="ph ph-warning-circle text-red-600 mt-0.5"></i>
                    <div>
                        <strong class="block mb-1">Please fix the following:</strong>
                        <ul class="list-disc ml-5 space-y-1">
                            @foreach($errors->all() as $error)
                                <li class="break-words">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row gap-6">
                @php
                    $photoUrl = $su->passport_photo_url;
                    $initials = collect(explode(' ', $su->full_name))
                        ->filter()
                        ->map(fn($part) => mb_substr($part, 0, 1))
                        ->take(2)
                        ->implode('');
                @endphp

                <div class="flex flex-col items-center gap-3 sm:w-1/3">
                    <div
                        class="relative inline-flex items-center justify-center w-28 h-28 rounded-full bg-gray-100 text-gray-600 text-2xl font-semibold overflow-hidden">
                        @if($photoUrl)
                            <img src="{{ $photoUrl }}" alt="{{ $su->full_name }}" class="w-full h-full object-cover">
                        @else
                            <span>{{ $initials }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500 text-center">
                        This is the photo that will appear on reports and key screens.
                    </p>
                </div>

                <div class="flex-1">
                    <form method="POST" action="{{ route('backend.admin.service-users.photo.update', $su->id) }}"
                        enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                New Passport Photo
                            </label>
                            <input type="file" name="photo" accept="image/*"
                                class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 text-sm" required>
                            <p class="mt-1 text-xs text-gray-500">
                                JPG or PNG, max 4MB. Ideally a clear headshot on a plain background.
                            </p>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                                <i class="ph ph-upload-simple"></i>
                                <span>Upload & Save</span>
                            </button>
                            <a href="{{ route('backend.admin.service-users.show', $su->id) }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm">
                                <i class="ph ph-x-circle"></i>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection