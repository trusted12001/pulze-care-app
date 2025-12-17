@extends('layouts.carer')

@section('title', 'Resident Details')

@section('content')
    <main class="home-screen px-4 pt-3 pb-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Resident</h3>
            <a href="{{ route('frontend.residents.index') }}" class="btn btn-sm btn-light">Back</a>
        </div>

        <div class="bg-white rounded-3 shadow-sm p-3">
            <h5 class="mb-2">
                {{ $resident->full_name ?? $resident->name ?? ('Resident #' . $resident->id) }}
            </h5>

            <p class="small text-muted mb-1">ID: {{ $resident->id }}</p>

            @if(isset($resident->date_of_birth))
                <p class="small text-muted mb-1">DOB: {{ optional($resident->date_of_birth)->format('d/m/Y') }}</p>
            @endif
        </div>
    </main>
@endsection