@php
  // Convert stored JSON (if present) to a comma-separated string for the input
  $tagsValue = old('tags');
  if ($tagsValue === null) {
      $stored = $su->tags;
      if (is_string($stored)) {
          $trim = trim($stored);
          if (str_starts_with($trim, '[')) {
              $decoded = json_decode($trim, true);
              if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                  $tagsValue = implode(', ', $decoded);
              } else {
                  $tagsValue = $stored; // fallback
              }
          } else {
              $tagsValue = $stored; // if no JSON constraint, this will still show
          }
      }
  }
@endphp

<form method="POST"
      action="{{ route('backend.admin.service-users.update-section', ['service_user' => $su->getRouteKey(), 'section' => 'tags']) }}"
      class="bg-white p-4 rounded shadow"
      x-data="{
        raw: @js($tagsValue ?? ''),
        normalize() {
          const out = Array.from(new Set(
            this.raw.split(',').map(s => s.trim()).filter(Boolean)
          ));
          this.raw = out.join(', ');
        }
      }"
      x-init="normalize()">
  @csrf
  @method('PATCH')

  <div class="grid gap-3">
    <div>
      <label class="block text-sm mb-1">Tags</label>
      <input type="text"
             name="tags"
             x-model="raw"
             @blur="normalize()"
             class="w-full border rounded px-3 py-2"
             placeholder="e.g., Diabetes, 1:1 Support, Falls Risk, Vegetarian">
      @error('tags')<p class="text-red-600 text-sm">{{ $message }}</p>@enderror
      <p class="text-xs text-gray-500 mt-1">
        Enter tags separated by commas. Examples: <em>Diabetes</em>, <em>1:1 Support</em>, <em>Falls Risk</em>, <em>Vegetarian</em>.
      </p>
    </div>

    <div class="flex flex-wrap gap-2 text-sm">
      @php
        $suggestions = ['Diabetes','Epilepsy','1:1 Support','2:1 Support','Falls Risk','Vegetarian','Halal','Wheelchair User','Non-verbal','DoLS','DNACPR'];
      @endphp
      @foreach($suggestions as $tag)
        <button type="button"
                class="px-2 py-1 border rounded hover:bg-gray-50"
                @click="
                  if (raw.trim() === '') { raw = '{{ $tag }}'; }
                  else { raw = raw + ', {{ $tag }}'; }
                  normalize();
                ">
          + {{ $tag }}
        </button>
      @endforeach
    </div>
  </div>

  <div class="mt-4">
    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
      Save Tags
    </button>
  </div>
</form>
