<div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-4 sm:mb-6">
    <div class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-200">
        <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
            <i class="ph ph-clipboard-text text-amber-600 flex-shrink-0"></i>
            <span>Reviews</span>
        </h2>
    </div>

    <div class="p-4 sm:p-5 lg:p-6">
        {{-- Add Review Form --}}
        <div class="mb-5 sm:mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <form action="{{ route('backend.admin.care-plans.reviews.store', $care_plan) }}" method="POST"
                class="space-y-3">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Review Date</label>
                        <input type="date" name="review_date"
                            class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Reason</label>
                        <input type="text" name="reason" placeholder="scheduled|change_in_needs|incident"
                            class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Review Frequency</label>
                        <input type="text" name="review_frequency_suggested" placeholder="e.g., 24 weeks"
                            class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Next Review Date</label>
                        <input type="date" name="next_review_date_suggested"
                            class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 cursor-pointer">
                            <input type="checkbox" name="bump_version" value="1"
                                class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                            <span>Bump Version</span>
                        </label>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Change Note</label>
                        <input type="text" name="change_note" placeholder="Optional change note"
                            class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1.5">Summary / Notes</label>
                    <textarea name="summary" rows="3" placeholder="Review summary and notes..."
                        class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit"
                        class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 active:bg-amber-800 transition-colors duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                        <i class="ph ph-plus"></i> Log Review
                    </button>
                </div>
            </form>
        </div>

        {{-- Reviews Table --}}
        @forelse($care_plan->reviews as $r)
            <div class="mb-3 last:mb-0 p-4 bg-white border border-gray-200 rounded-lg hover:shadow-sm transition-shadow">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2">
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-100 text-amber-800 rounded text-xs font-medium">
                                <i class="ph ph-calendar"></i>
                                {{ $r->review_date ? $r->review_date->format('d M Y') : $r->created_at->format('d M Y') }}
                            </span>
                            @if($r->reason)
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">
                                    {{ ucwords(str_replace('_', ' ', $r->reason)) }}
                                </span>
                            @endif
                        </div>
                        @if($r->summary)
                            <p class="text-sm text-gray-700 mb-2 whitespace-pre-line">{{ $r->summary }}</p>
                        @endif
                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                            @if($r->next_review_date_suggested)
                                <span class="flex items-center gap-1">
                                    <i class="ph ph-calendar-check"></i>
                                    Next: {{ $r->next_review_date_suggested->format('d M Y') }}
                                    @if($r->review_frequency_suggested)
                                        ({{ $r->review_frequency_suggested }})
                                    @endif
                                </span>
                            @endif
                            @if($r->reviewer)
                                <span class="flex items-center gap-1">
                                    <i class="ph ph-user"></i>
                                    {{ $r->reviewer->first_name ?? '—' }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-6 text-sm text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
                <i class="ph ph-clipboard text-gray-400 text-2xl mb-2"></i>
                <p>No reviews yet.</p>
            </div>
        @endforelse
    </div>
</div>