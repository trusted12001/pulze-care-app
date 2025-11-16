<div class="bg-white rounded-lg sm:rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-4 sm:mb-6">
    <div
        class="px-4 sm:px-5 lg:px-6 py-3 sm:py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
        <h2 class="text-base sm:text-lg font-semibold text-gray-900 flex items-center gap-2">
            <i class="ph ph-signature text-green-600 flex-shrink-0"></i>
            <span>Digital Sign-offs</span>
        </h2>
    </div>

    <div class="p-4 sm:p-5 lg:p-6">
        {{-- Sign-off Form --}}
        <div class="mb-5 sm:mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <form action="{{ route('backend.admin.care-plans.signoffs.store', $care_plan) }}" method="POST"
                class="space-y-3">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Role</label>
                        <input name="role_label" placeholder="e.g., Key Worker"
                            class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">PIN Last 4</label>
                        <input name="pin_last4" placeholder="Optional" maxlength="4"
                            class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    </div>
                    <div class="md:col-span-2 flex items-end">
                        <button type="submit"
                            class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 active:bg-green-800 transition-colors duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                            <i class="ph ph-signature"></i> Sign Off Current Version
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Sign-offs List --}}
        @forelse($care_plan->signoffs as $s)
            <div class="mb-3 last:mb-0 p-4 bg-white border border-gray-200 rounded-lg hover:shadow-sm transition-shadow">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 sm:gap-3">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2">
                            @if($s->user)
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-100 text-blue-800 rounded text-xs sm:text-sm font-medium">
                                    <i class="ph ph-user"></i>
                                    {{ $s->user->first_name ?? '—' }}
                                </span>
                            @endif
                            @if($s->role_label)
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-100 text-purple-800 rounded text-xs font-medium">
                                    <i class="ph ph-briefcase"></i>
                                    {{ $s->role_label }}
                                </span>
                            @endif
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-800 rounded text-xs font-medium">
                                <i class="ph ph-file-text"></i>
                                Version {{ $s->version_at_sign }}
                            </span>
                        </div>
                        @if($s->signed_at)
                            <div class="flex items-center gap-1 text-xs text-gray-500">
                                <i class="ph ph-calendar-check"></i>
                                <span>Signed on {{ $s->signed_at->format('d M Y, h:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-6 text-sm text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
                <i class="ph ph-signature text-gray-400 text-2xl mb-2"></i>
                <p>No sign-offs yet.</p>
            </div>
        @endforelse
    </div>
</div>