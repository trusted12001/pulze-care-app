@php $isEdit = isset($registration) && $registration->exists; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
  <div>
    <label class="block mb-1 font-medium text-gray-800">Body</label>
    @php $body = old('body', $registration->body ?? 'Other'); @endphp
    <select name="body" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach(['NMC','HCPC','GMC','GPhC','SWE','Other'] as $opt)
        <option value="{{ $opt }}" @selected($body===$opt)>{{ $opt }}</option>
      @endforeach
    </select>
    @error('body') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">PIN Number</label>
    <input type="text" name="pin_number" value="{{ old('pin_number', $registration->pin_number ?? '') }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('pin_number') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Status</label>
    @php $status = old('status', $registration->status ?? 'active'); @endphp
    <select name="status" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2" required>
      @foreach(['active','lapsed','suspended','pending'] as $opt)
        <option value="{{ $opt }}" @selected($status===$opt)>{{ ucfirst($opt) }}</option>
      @endforeach
    </select>
    @error('status') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">First Registered</label>
    <input type="date" name="first_registered_at"
           value="{{ old('first_registered_at', optional($registration->first_registered_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('first_registered_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Expires At</label>
    <input type="date" name="expires_at"
           value="{{ old('expires_at', optional($registration->expires_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('expires_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div>
    <label class="block mb-1 font-medium text-gray-800">Revalidation Due</label>
    <input type="date" name="revalidation_due_at"
           value="{{ old('revalidation_due_at', optional($registration->revalidation_due_at ?? null)->format('Y-m-d')) }}"
           class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">
    @error('revalidation_due_at') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>

  <div class="md:col-span-2">
    <label class="block mb-1 font-medium text-gray-800">Notes</label>
    <textarea name="notes" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded px-4 py-2">{{ old('notes', $registration->notes ?? '') }}</textarea>
    @error('notes') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
  </div>
</div>

<div class="mt-6 text-right">
  <button class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
    {{ $isEdit ? 'Update' : 'Save' }}
  </button>
  <a href="{{ route('backend.admin.staff-profiles.registrations.index', $staffProfile) }}"
     class="ml-2 px-6 py-2 border border-gray-300 rounded hover:bg-gray-50">Cancel</a>
</div>
