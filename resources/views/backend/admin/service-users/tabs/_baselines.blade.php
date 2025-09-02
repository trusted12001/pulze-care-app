<form method="POST"
      action="{{ route('backend.admin.service-users.update-section', [$su, 'baselines']) }}"
      class="bg-white p-4 rounded shadow">
  @csrf
  @method('PATCH')

  <div class="grid md:grid-cols-2 gap-4">
    {{-- Baseline Blood Pressure --}}
    <div>
      <label class="block text-sm mb-1">Baseline BP</label>
      <input type="text"
             name="baseline_bp"
             class="w-full border rounded px-3 py-2"
             placeholder="e.g., 120/80"
             value="{{ old('baseline_bp', $su->baseline_bp) }}">
      @error('baseline_bp')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      <p class="text-xs text-gray-500 mt-1">Format tip: systolic/diastolic (e.g., 120/80).</p>
    </div>

    {{-- Baseline Heart Rate --}}
    <div>
      <label class="block text-sm mb-1">Baseline HR (bpm)</label>
      <input type="number"
             name="baseline_hr"
             class="w-full border rounded px-3 py-2"
             min="30" max="220" step="1"
             placeholder="e.g., 72"
             value="{{ old('baseline_hr', $su->baseline_hr) }}">
      @error('baseline_hr')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>

    {{-- Baseline SpO₂ --}}
    <div>
      <label class="block text-sm mb-1">Baseline SpO₂ (%)</label>
      <input type="number"
             name="baseline_spo2"
             class="w-full border rounded px-3 py-2"
             min="50" max="100" step="1"
             placeholder="e.g., 98"
             value="{{ old('baseline_spo2', $su->baseline_spo2) }}">
      @error('baseline_spo2')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      <p class="text-xs text-gray-500 mt-1">Room air unless otherwise noted.</p>
    </div>

    {{-- Baseline Temperature --}}
    <div>
      <label class="block text-sm mb-1">Baseline Temp (°C)</label>
      <input type="number"
             name="baseline_temp"
             class="w-full border rounded px-3 py-2"
             min="30" max="45" step="0.1"
             placeholder="e.g., 36.6"
             value="{{ old('baseline_temp', $su->baseline_temp) }}">
      @error('baseline_temp')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
    </div>
  </div>

  <div class="mt-4">
    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
      Save Baselines
    </button>
  </div>
</form>
