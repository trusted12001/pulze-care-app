@extends('layouts.carer')

@section('content')

    <div class="container-fluid px-3 py-3">

        <div class="d-flex align-items-center mb-3">
            <a href="{{ route('frontend.carer.index') }}" class="btn btn-sm btn-light me-2">
                ← Back
            </a>

            <h4 class="mb-0">
                Notifications
            </h4>
        </div>

        @forelse($notifications as $notification)

            <a href="{{ route('frontend.carer.notifications.open', $notification->id) }}"
                class="text-decoration-none text-reset">

                <div class="card border-0 shadow-sm mb-3">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>

                                <h6 class="mb-1 fw-bold">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </h6>

                                <p class="mb-1 text-muted">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>

                                @if(isset($notification->data['location_name']))
                                    <small class="text-secondary">
                                        📍 {{ $notification->data['location_name'] }}
                                    </small>
                                @endif

                                <br>

                                <small class="text-muted">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>

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