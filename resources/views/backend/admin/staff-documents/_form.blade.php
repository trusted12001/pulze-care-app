@php
  $isEdit = isset($document) && $document->exists;
  $categories = $categories ?? ['DBS','Visa','Training Cert','Contract','Payroll','Reference','OH','ID','Other'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Category</label>
    @php $cat = old('category', $document->category ?? 'Other'); @endphp
    <select name="category" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($categories as $c)
        <option value="{{ $c }}" @selected($cat===$c)>{{ $c }}</option>
      @endforeach
    </select>
    @error('category') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">File {{ $isEdit ? '(leave blank to keep existing)' : '' }}</label>
    <input type="file" name="file" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" {{ $isEdit ? '' : 'required' }}>
    @error('file') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror

    @if($isEdit && !empty($document->path))
      <p class="text-xs text-gray-500 mt-1">Current: <a href="{{ $document->url }}" class="text-blue-600 underline" target="_blank">{{ $document->filename }}</a></p>
    @endif
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Upload' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.documents.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
