@php
  $isEdit = isset($availability) && $availability->exists;
  $days = $days ?? ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
  $prefs = ['prefer'=>'Prefer', 'okay'=>'Okay', 'avoid'=>'Avoid'];
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Day of Week</label>
    @php $dow = old('day_of_week', $availability->day_of_week ?? 1); @endphp
    <select name="day_of_week" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($days as $i => $label)
        <option value="{{ $i }}" @selected((int)$dow===(int)$i)>{{ $label }}</option>
      @endforeach
    </select>
    @error('day_of_week') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Available From</label>
    <input type="time" name="available_from"
           value="{{ old('available_from', $availability->available_from ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('available_from') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Available To</label>
    <input type="time" name="available_to"
           value="{{ old('available_to', $availability->available_to ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('available_to') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-3">
    <label class="block mb-1 font-medium text-gray-800">Preference</label>
    @php $pref = old('preference', $availability->preference ?? 'okay'); @endphp
    <select name="preference" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($prefs as $v=>$l) <option value="{{ $v }}" @selected($pref===$v)>{{ $l }}</option> @endforeach
    </select>
    @error('preference') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.availability.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
