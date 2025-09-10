@php
  $isEdit = isset($equality) && $equality->exists;
  $dataSources = ['self_declared'=>'Self-declared','not_provided'=>'Not provided'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Ethnicity (optional)</label>
    <input type="text" name="ethnicity" value="{{ old('ethnicity', $equality->ethnicity ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('ethnicity') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Religion (optional)</label>
    <input type="text" name="religion" value="{{ old('religion', $equality->religion ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('religion') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="flex items-center gap-2">
    <input id="disability" type="checkbox" name="disability" value="1"
           class="rounded" {{ old('disability', $equality->disability ?? false) ? 'checked' : '' }}>
    <label for="disability" class="font-medium text-gray-800">Disability</label>
    @error('disability') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Gender Identity (optional)</label>
    <input type="text" name="gender_identity" value="{{ old('gender_identity', $equality->gender_identity ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('gender_identity') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Sexual Orientation (optional)</label>
    <input type="text" name="sexual_orientation" value="{{ old('sexual_orientation', $equality->sexual_orientation ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('sexual_orientation') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Data Source</label>
    @php $ds = old('data_source', $equality->data_source ?? 'self_declared'); @endphp
    <select name="data_source" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($dataSources as $v=>$l) <option value="{{ $v }}" @selected($ds===$v)>{{ $l }}</option> @endforeach
    </select>
    @error('data_source') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.equality.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
