@php
  use Illuminate\Database\Eloquent\Model;

  // Safe flags & value helpers (work for create or edit)
  $availability = $availability ?? null;
  $isEdit = $availability instanceof Model && $availability->exists;

  $days  = $days ?? ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
  $prefs = ['prefer'=>'Prefer', 'okay'=>'Okay', 'avoid'=>'Avoid'];

  // Use data_get to avoid "trying to access property of non-object"
  $valDay   = old('day_of_week', data_get($availability, 'day_of_week', 1));
  $valFrom  = old('available_from', data_get($availability, 'available_from'));
  $valTo    = old('available_to', data_get($availability, 'available_to'));
  $valPref  = old('preference', data_get($availability, 'preference', 'okay'));
@endphp

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
  {{-- Day of Week --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Day of Week</label>
    <select name="day_of_week" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($days as $i => $label)
        <option value="{{ $i }}" @selected((int)$valDay === (int)$i)>{{ $label }}</option>
      @endforeach
    </select>
    @error('day_of_week') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Available From --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Available From</label>
    <input type="time" name="available_from"
           value="{{ $valFrom }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('available_from') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Available To --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Available To</label>
    <input type="time" name="available_to"
           value="{{ $valTo }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('available_to') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  {{-- Preference --}}
  <div>
    <label class="block mb-1 font-medium text-gray-800">Preference</label>
    <select name="preference" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach($prefs as $k => $label)
        <option value="{{ $k }}" @selected($valPref === $k)>{{ $label }}</option>
      @endforeach
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
