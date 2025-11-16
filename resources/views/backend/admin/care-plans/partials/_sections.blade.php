<div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-4 sm:mb-6">
  <div class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
        <i class="ph ph-stack text-purple-600 flex-shrink-0"></i>
        <span>Sections (Domains)</span>
      </h2>
    </div>
  </div>

  <div class="p-4 sm:p-5 lg:p-6">
    {{-- Add Section Form --}}
    <div class="mb-5 sm:mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
      <form action="{{ route('backend.admin.care-plans.sections.store', $plan) }}" method="POST" class="space-y-3">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
          <div class="md:col-span-1">
            <label class="block text-xs font-medium text-gray-700 mb-1.5">Section Name <span
                class="text-red-500">*</span></label>
            <input name="name" placeholder="e.g., Personal Care"
              class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
              required>
          </div>
          <div class="md:col-span-2">
            <label class="block text-xs font-medium text-gray-700 mb-1.5">Description</label>
            <input name="description" placeholder="Optional description..."
              class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
          </div>
          <div class="md:col-span-1 flex items-end">
            <button type="submit"
              class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 active:bg-purple-800 transition-colors duration-200 text-sm font-medium shadow-sm hover:shadow-md">
              <i class="ph ph-plus"></i> Add Section
            </button>
          </div>
        </div>
      </form>
    </div>

    @forelse($plan->sections as $section)
      <div class="mb-4 sm:mb-5 last:mb-0 bg-white border border-gray-200 rounded-lg overflow-hidden shadow-sm">
        {{-- Section Header --}}
        <div class="px-4 sm:px-5 py-3 sm:py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b border-gray-200">
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-3">
            <div class="flex-1">
              <h3 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i class="ph ph-folder text-indigo-600"></i>
                {{ $section->name }}
              </h3>
              @if($section->description)
                <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $section->description }}</p>
              @endif
            </div>
            <form action="{{ route('backend.admin.sections.destroy', $section) }}" method="POST"
              onsubmit="return confirm('Are you sure you want to remove this section? This will also delete all goals and interventions within it.')">
              @csrf @method('DELETE')
              <button type="submit"
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors duration-200">
                <i class="ph ph-trash"></i>
                <span>Delete</span>
              </button>
            </form>
          </div>
        </div>

        <div class="p-4 sm:p-5">
          {{-- Add Goal Form --}}
          <div class="mb-4 p-3 sm:p-4 bg-gray-50 rounded-lg border border-gray-200">
            <form action="{{ route('backend.admin.sections.goals.store', $section) }}" method="POST" class="space-y-3">
              @csrf
              <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div class="md:col-span-2">
                  <label class="block text-xs font-medium text-gray-700 mb-1.5">Goal Title <span
                      class="text-red-500">*</span></label>
                  <input name="title" placeholder="e.g., Improve mobility"
                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    required>
                </div>
                <div class="md:col-span-1">
                  <label class="block text-xs font-medium text-gray-700 mb-1.5">Target Date</label>
                  <input type="date" name="target_date"
                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>
                <div class="md:col-span-2">
                  <label class="block text-xs font-medium text-gray-700 mb-1.5">Success Criteria</label>
                  <input name="success_criteria" placeholder="How will we measure success?"
                    class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>
              </div>
              <div class="flex justify-end">
                <button type="submit"
                  class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 active:bg-indigo-800 transition-colors duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                  <i class="ph ph-plus"></i> Add Goal
                </button>
              </div>
            </form>
          </div>

          {{-- Goals List --}}
          @forelse($section->goals as $goal)
            <div class="mb-4 last:mb-0 border border-gray-200 rounded-lg overflow-hidden bg-white">
              <div class="px-4 py-3 bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                  <div class="flex-1">
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-1">{{ $goal->title }}</h4>
                    @if($goal->success_criteria)
                      <p class="text-xs sm:text-sm text-gray-600">
                        <i class="ph ph-target"></i> Success: {{ $goal->success_criteria }}
                      </p>
                    @endif
                  </div>
                  <div class="flex items-center gap-2">
                    @if($goal->target_date)
                      <span
                        class="inline-flex items-center gap-1 px-2 py-1 bg-white rounded text-xs font-medium text-gray-700">
                        <i class="ph ph-calendar"></i>
                        {{ $goal->target_date->format('d M Y') }}
                      </span>
                    @endif
                    <span
                      class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium 
                          {{ $goal->status === 'achieved' ? 'bg-green-100 text-green-800' : ($goal->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                      {{ ucfirst($goal->status) }}
                    </span>
                    <form action="{{ route('backend.admin.goals.destroy', $goal) }}" method="POST"
                      onsubmit="return confirm('Remove this goal?')" class="inline">
                      @csrf @method('DELETE')
                      <button type="submit" class="text-red-600 hover:text-red-700 text-xs">
                        <i class="ph ph-trash"></i>
                      </button>
                    </form>
                  </div>
                </div>
              </div>

              <div class="p-4">
                {{-- Add Intervention Form --}}
                <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                  <form action="{{ route('backend.admin.goals.interventions.store', $goal) }}" method="POST"
                    class="space-y-2">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-2">
                      <div class="md:col-span-2">
                        <input name="description" placeholder="Intervention description..."
                          class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                          required>
                      </div>
                      <div>
                        <input name="frequency" placeholder="Frequency"
                          class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                      </div>
                      <div>
                        <input name="assigned_to_role" placeholder="Role"
                          class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                      </div>
                      <div class="flex items-center">
                        <label class="inline-flex items-center gap-1.5 text-xs text-gray-700 cursor-pointer">
                          <input type="checkbox" name="link_to_assignment" value="1"
                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                          <span>Link to Assignment</span>
                        </label>
                      </div>
                    </div>
                    <div class="flex justify-end">
                      <button type="submit"
                        class="px-3 py-1.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 active:bg-emerald-800 transition-colors duration-200 text-xs sm:text-sm font-medium">
                        <i class="ph ph-plus"></i> Add Intervention
                      </button>
                    </div>
                  </form>
                </div>

                {{-- Interventions List --}}
                @forelse($goal->interventions as $iv)
                  <div class="mb-2 last:mb-0 p-3 bg-emerald-50 rounded-lg border border-emerald-200">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                      <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 mb-1">{{ $iv->description }}</p>
                        <div class="flex flex-wrap items-center gap-2 text-xs text-gray-600">
                          @if($iv->frequency)
                            <span class="inline-flex items-center gap-1">
                              <i class="ph ph-clock"></i>
                              {{ $iv->frequency }}
                            </span>
                          @endif
                          @if($iv->assigned_to_role)
                            <span class="inline-flex items-center gap-1">
                              <i class="ph ph-user"></i>
                              {{ $iv->assigned_to_role }}
                            </span>
                          @endif
                          @if($iv->assignee)
                            <span class="inline-flex items-center gap-1">
                              <i class="ph ph-user-circle"></i>
                              {{ $iv->assignee->name }}
                            </span>
                          @endif
                          @if($iv->link_to_assignment)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-800 rounded">
                              <i class="ph ph-link"></i>
                              Linked
                            </span>
                          @endif
                        </div>
                      </div>
                      <form action="{{ route('backend.admin.interventions.destroy', $iv) }}" method="POST"
                        onsubmit="return confirm('Remove this intervention?')" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-700 text-xs">
                          <i class="ph ph-trash"></i>
                        </button>
                      </form>
                    </div>
                  </div>
                @empty
                  <div class="text-center py-4 text-sm text-gray-500">
                    <i class="ph ph-info text-gray-400"></i> No interventions yet.
                  </div>
                @endforelse
              </div>
            </div>
          @empty
            <div class="text-center py-6 text-sm text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
              <i class="ph ph-target text-gray-400 text-2xl mb-2"></i>
              <p>No goals yet. Add a goal to get started.</p>
            </div>
          @endforelse
        </div>
      </div>
    @empty
      <div class="text-center py-8 text-sm text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
        <i class="ph ph-folder-open text-gray-400 text-3xl mb-3"></i>
        <p>No sections yet. Add a section to organize care plan goals.</p>
      </div>
    @endforelse
  </div>
</div>