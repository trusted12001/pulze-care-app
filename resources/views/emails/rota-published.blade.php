<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rota Published</title>
</head>

<body style="font-family: Arial, sans-serif; line-height:1.6; color:#333;">

    @if($settings && $settings->logo_path)
        <div style="margin-bottom:20px;">
            @if($settings?->logo_url)
                <img src="{{ $settings->logo_url }}">
            @endif

        </div>
    @endif

    <h2>{{ $tenant?->name ?? 'Pulze' }}</h2>

    <p>Hello {{ $user->first_name }},</p>

    <p>
        A new rota has been published for your location.
    </p>

    <p>
        <strong>Location:</strong>
        {{ $rotaPeriod->location->name }}
    </p>

    <p>
        <strong>Period:</strong>
        {{ $rotaPeriod->start_date->format('d M Y') }}
        –
        {{ $rotaPeriod->end_date->format('d M Y') }}
    </p>

    <p>
        <a href="{{ route('frontend.rota.show', $rotaPeriod) }}">
            View Rota
        </a>
    </p>

    @if($settings && $settings->office_address)
        <hr>

        <p>
            {{ $settings->office_address }}
        </p>
    @endif

    <p>
        Powered by <strong>Pulze</strong>
    </p>

</body>

</html>