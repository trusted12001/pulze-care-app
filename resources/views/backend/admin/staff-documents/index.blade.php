@extends('layouts.admin')

@section('title', 'Documents')

@section('content')
    <div class="min-h-screen p-0 rounded-lg">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-black">Documents — {{ $staffProfile->user->full_name ?? '—' }}</h2>
            <a href="{{ route('backend.admin.staff-profiles.show', $staffProfile) }}"
                class="text-blue-600 hover:underline">← Back to Staff</a>
        </div>

        @include('backend.admin.staff-profiles._tabs', ['staffProfile' => $staffProfile])

        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-md">{{ session('success') }}
            </div>
        @endif

        <div class="mb-4 flex items-center justify-between gap-3">
            <a href="{{ route('backend.admin.staff-profiles.documents.create', $staffProfile) }}"
                class="bg-green-600 text-white px-6 py-2 rounded shadow hover:bg-green-700 transition">Upload Document</a>

            <form method="GET" class="flex items-center gap-2">
                <select name="category" class="border border-gray-300 px-3 py-2 rounded bg-gray-50">
                    <option value="">All categories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c }}" {{ ($category ?? '') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search filename/type/category…"
                    class="border border-gray-300 px-3 py-2 rounded bg-gray-50" />
                <button class="px-3 py-2 border rounded hover:bg-gray-50">Go</button>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase tracking-wider text-xs">
                        <tr>
                            <th class="px-4 py-2">Category</th>
                            <th class="px-4 py-2">Filename</th>
                            <th class="px-4 py-2">Type</th>
                            <th class="px-4 py-2">Uploaded</th>
                            <th class="px-4 py-2">By</th>
                            <th class="px-4 py-2 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($docs as $d)
                                        <tr class="hover:bg-gray-50 border-t">
                                            <td class="px-4 py-2">{{ $d->category }}</td>
                                            <td class="px-4 py-2">
                                                <a href="{{ $d->url }}" target="_blank"
                                                    class="text-blue-600 hover:underline">{{ $d->filename }}</a>
                                            </td>
                                            <td class="px-4 py-2">{{ $d->mime }}</td>
                                            <td class="px-4 py-2">{{ optional($d->created_at)->format('d M Y H:i') }}</td>
                                            <td class="px-4 py-2">
                                                {{ optional($d->uploadedBy)->full_name
                            ?? optional($d->uploadedBy)->name
                            ?? ($d->uploaded_by ? 'User #' . $d->uploaded_by : '—') }}
                                            </td>

                                            <td class="px-4 py-2 text-right space-x-2">
                                                <a href="{{ route('backend.admin.staff-profiles.documents.edit', [$staffProfile, $d]) }}"
                                                    class="text-blue-600 hover:underline">Edit</a>
                                                <form
                                                    action="{{ route('backend.admin.staff-profiles.documents.destroy', [$staffProfile, $d]) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Delete this document? The file will be removed.')">
                                                    @csrf @method('DELETE')
                                                    <button class="text-red-600 hover:underline">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">No documents uploaded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if(method_exists($docs, 'hasPages') && $docs->hasPages())
                    <div class="mt-4">{{ $docs->links('vendor.pagination.tailwind') }}</div>
                @endif
            </div>
        </div>
    </div>
@endsection