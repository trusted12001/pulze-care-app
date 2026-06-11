@extends('layouts.carer')

@section('content')

    <div class="container-fluid px-3 py-3">

        <div class="d-flex align-items-center mb-3">
            <a href="{{ route('frontend.carer.index') }}" class="btn btn-sm btn-light me-2">
                ← Back
            </a>

            <div class="d-flex justify-content-between align-items-center">

                <h4 class="mb-0 fw-bold">
                    Notifications
                </h4>

                @if(auth()->user()->unreadNotifications()->count() > 0)

                    <form method="POST" action="{{ route('frontend.carer.notifications.mark-all-read') }}">
                        @csrf

                        <button type="submit" class="btn btn-link p-0 text-decoration-none small fw-semibold">
                            Mark all read
                        </button>
                    </form>

                @endif

            </div>

        </div>

        @forelse($notifications as $notification)

            <a href="{{ route('frontend.carer.notifications.open', $notification->id) }}"
                class="text-decoration-none text-reset">

                <div class="card border-0 shadow-sm mb-3">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <div>

                                    @php
                                        $icon = match ($notification->data['type'] ?? '') {
                                            'rota' => '📅',
                                            'assignment' => '📋',
                                            'incident' => '⚠️',
                                            'medication' => '💊',
                                            'handover' => '📖',
                                            'training' => '🎓',
                                            default => '🔔',
                                        };
                                    @endphp

                                    <h6 class="mb-1 fw-bold">
                                        {{ $icon }} {{ $notification->data['title'] ?? 'Notification' }}
                                    </h6>

                                    @if(!empty($notification->data['location_name']))
                                        <p class="mb-1 fw-semibold text-dark">
                                            {{ $notification->data['location_name'] }}
                                        </p>
                                    @endif

                                    @if(
                                            !empty($notification->data['start_date']) &&
                                            !empty($notification->data['end_date'])
                                        )
                                        <p class="mb-1 small text-primary">
                                            {{ $notification->data['start_date'] }}
                                            –
                                            {{ $notification->data['end_date'] }}
                                        </p>
                                    @endif

                                    <p class="mb-1 text-muted small">
                                        {{ $notification->data['message'] ?? '' }}
                                    </p>

                                    <small class="text-muted">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </small>

                                </div>

                            </div>

                            @if(is_null($notification->read_at))
                                <span class="badge bg-primary">
                                    New
                                </span>
                            @endif

                        </div>

                    </div>

                </div>

            </a>

        @empty

            <div class="alert alert-info">
                No notifications found.
            </div>

        @endforelse

        <div class="mt-3">
            {{ $notifications->links() }}
        </div>

    </div>

@endsection