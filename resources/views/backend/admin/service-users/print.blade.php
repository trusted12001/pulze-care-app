{{-- resources/views/backend/admin/service-users/print.blade.php --}}

@php
    use Illuminate\Support\Carbon;

    $fmtDate = fn($d) => $d ? Carbon::parse($d)->format('d M Y') : '—';
    $fmtMoney = fn($n) => is_null($n) ? '—' : '£' . number_format($n, 2);
    $yesNo = fn($b) => $b ? 'Yes' : 'No';

    // Tags: handle JSON array or CSV
    $tags = [];
    if (is_string($su->tags) && trim($su->tags) !== '') {
        $trim = trim($su->tags);
        if (str_starts_with($trim, '[')) {
            $decoded = json_decode($trim, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $tags = $decoded;
            }
        } else {
            $tags = array_filter(array_map('trim', explode(',', $trim)));
        }
    }

    $statusClass = match ($su->status) {
        'active' => 'background: #dcfce7; color:#166534; border-color:#86efac;',
        'discharged' => 'background: #fef3c7; color:#92400e; border-color:#fed7aa;',
        'on_leave' => 'background: #e0f2fe; color:#1d4ed8; border-color:#bfdbfe;',
        default => 'background: #f3f4f6; color:#374151; border-color:#e5e7eb;',
    };

    $addressParts = array_filter([
        $su->address_line1,
        $su->address_line2,
        $su->city,
        $su->county,
        $su->postcode,
        $su->country,
    ]);
    $fullAddress = $addressParts ? implode(', ', $addressParts) : '—';

    $photoUrl = $su->passport_photo_url ?? null;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Service User Summary – {{ $su->full_name }}</title>
    <style>
        @page {
            size: A4;
            margin: 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            font-size: 11px;
            color: #111827;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .page {
            width: 100%;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
            margin-bottom: 10px;
        }

        .header-left h1 {
            margin: 0 0 4px;
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }

        .header-left p {
            margin: 0;
            font-size: 11px;
            color: #6b7280;
        }

        .header-right {
            text-align: right;
            font-size: 10px;
            color: #6b7280;
        }

        .photo {
            width: 70px;
            height: 70px;
            border-radius: 999px;
            object-fit: cover;
            border: 1px solid #e5e7eb;
        }

        .section-title {
            font-size: 12px;
            font-weight: 600;
            margin: 14px 0 4px;
            padding-bottom: 3px;
            border-bottom: 1px solid #e5e7eb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6px;
        }

        th,
        td {
            padding: 4px 6px;
            vertical-align: top;
        }

        th {
            width: 30%;
            font-weight: 600;
            text-align: left;
            font-size: 10px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }

        td {
            font-size: 11px;
            border: 1px solid #e5e7eb;
        }

        .muted {
            color: #6b7280;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 999px;
            border-width: 1px;
            border-style: solid;
            font-size: 10px;
            font-weight: 600;
        }

        .tag {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 999px;
            border: 1px solid #d1d5db;
            font-size: 10px;
            margin: 2px 4px 2px 0;
        }

        .two-col {
            display: flex;
            gap: 10px;
            margin-top: 4px;
        }

        .col {
            flex: 1 1 0;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="page">

        {{-- Header --}}
        <div class="header">
            <div class="header-left">
                <h1>{{ $su->full_name }}</h1>
                <p>
                    Service User Summary • ID #{{ $su->id }}
                    @if($su->nhs_number)
                        • NHS: {{ $su->nhs_number }}
                    @endif
                </p>
                <p class="muted">
                    @if($su->date_of_birth)
                        DOB: {{ $fmtDate($su->date_of_birth) }}
                    @endif
                    @if($su->location)
                        • Location: {{ $su->location->name }}
                    @endif
                </p>
            </div>

            <div class="header-right">
                @if($photoUrl)
                    <img src="{{ $photoUrl }}" alt="Passport Photo" class="photo">
                @endif
                <div style="margin-top: 6px;">
                    <div class="badge" style="{{ $statusClass }}">
                        Status: {{ ucfirst($su->status) }}
                    </div>
                </div>
                <div style="margin-top: 6px;">
                    Printed: {{ now()->format('d M Y, H:i') }}
                </div>
            </div>
        </div>

        {{-- Personal & Contact (two columns) --}}
        <div class="two-col">
            <div class="col">
                <div class="section-title">Personal Details</div>
                <table>
                    <tr>
                        <th>Preferred Name</th>
                        <td>{{ $su->preferred_name ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Sex at Birth</th>
                        <td>{{ $su->sex_at_birth ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Gender Identity</th>
                        <td>{{ $su->gender_identity ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Pronouns</th>
                        <td>{{ $su->pronouns ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Ethnicity</th>
                        <td>{{ $su->ethnicity ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Religion</th>
                        <td>{{ $su->religion ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Primary Language</th>
                        <td>{{ $su->primary_language ?: '—' }}</td>
                    </tr>
                </table>
            </div>

            <div class="col">
                <div class="section-title">Contact & Address</div>
                <table>
                    <tr>
                        <th>Primary Phone</th>
                        <td>{{ $su->primary_phone ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Secondary Phone</th>
                        <td>{{ $su->secondary_phone ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $su->email ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Full Address</th>
                        <td>{{ $fullAddress }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Placement & Funding --}}
        <div class="section-title">Placement & Funding</div>
        <table>
            <tr>
                <th>Placement Type</th>
                <td>{{ $su->placement_type ?: '—' }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ optional($su->location)->name ?: '—' }}</td>
            </tr>
            <tr>
                <th>Room / Identifier</th>
                <td>{{ $su->room_number ?: '—' }}</td>
            </tr>
            <tr>
                <th>Admission Date</th>
                <td>{{ $fmtDate($su->admission_date) }}</td>
            </tr>
            <tr>
                <th>Expected Discharge</th>
                <td>{{ $fmtDate($su->expected_discharge_date) }}</td>
            </tr>
            <tr>
                <th>Discharge Date</th>
                <td>{{ $fmtDate($su->discharge_date) }}</td>
            </tr>
            <tr>
                <th>Funding Type</th>
                <td>{{ $su->funding_type ?: '—' }}</td>
            </tr>
            <tr>
                <th>Funding Authority</th>
                <td>{{ $su->funding_authority ?: '—' }}</td>
            </tr>
            <tr>
                <th>Weekly Rate</th>
                <td>{{ $fmtMoney($su->weekly_rate) }}</td>
            </tr>
        </table>

        {{-- Clinical & Risks (two columns) --}}
        <div class="two-col">
            <div class="col">
                <div class="section-title">Health & Clinical</div>
                <table>
                    <tr>
                        <th>Primary Diagnosis</th>
                        <td>{{ $su->primary_diagnosis ?: '—' }}</td>
                    </tr>
                    @if($su->other_diagnoses)
                        <tr>
                            <th>Other Diagnoses</th>
                            <td>{{ $su->other_diagnoses }}</td>
                        </tr>
                    @endif
                    @if($su->allergies_summary)
                        <tr>
                            <th>Allergies</th>
                            <td>{{ $su->allergies_summary }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Diet Type</th>
                        <td>{{ $su->diet_type ?: '—' }}</td>
                    </tr>
                    @if($su->intolerances)
                        <tr>
                            <th>Intolerances</th>
                            <td>{{ $su->intolerances }}</td>
                        </tr>
                    @endif
                    <tr>
                        <th>Mobility Status</th>
                        <td>{{ $su->mobility_status ?: '—' }}</td>
                    </tr>
                    @if($su->communication_needs)
                        <tr>
                            <th>Communication Needs</th>
                            <td>{{ $su->communication_needs }}</td>
                        </tr>
                    @endif
                </table>
            </div>

            <div class="col">
                <div class="section-title">Risks & Flags</div>
                <table>
                    <tr>
                        <th>Fall Risk</th>
                        <td>{{ $su->fall_risk ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Choking Risk</th>
                        <td>{{ $su->choking_risk ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Pressure Ulcer Risk</th>
                        <td>{{ $su->pressure_ulcer_risk ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Wander / Elopement</th>
                        <td>{{ $yesNo($su->wander_elopement_risk) }}</td>
                    </tr>
                    <tr>
                        <th>Safeguarding Flag</th>
                        <td>{{ $yesNo($su->safeguarding_flag) }}</td>
                    </tr>
                    <tr>
                        <th>Infection Control Flag</th>
                        <td>{{ $yesNo($su->infection_control_flag) }}</td>
                    </tr>
                    <tr>
                        <th>Smoking Status</th>
                        <td>{{ $su->smoking_status ?: '—' }}</td>
                    </tr>
                    <tr>
                        <th>Capacity Status</th>
                        <td>{{ $su->capacity_status ?: '—' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Legal & Consent --}}
        <div class="section-title">Legal & Consent</div>
        <table>
            <tr>
                <th>Consent Obtained</th>
                <td>{{ $su->consent_obtained_at ? $fmtDate($su->consent_obtained_at) : '—' }}</td>
            </tr>
            <tr>
                <th>DNACPR Status</th>
                <td>{{ $su->dnacpr_status ?: '—' }}</td>
            </tr>
            <tr>
                <th>DNACPR Review Date</th>
                <td>{{ $fmtDate($su->dnacpr_review_date) }}</td>
            </tr>
            <tr>
                <th>DoLS in Place</th>
                <td>{{ $yesNo($su->dols_in_place) }}</td>
            </tr>
            <tr>
                <th>DoLS Approval Date</th>
                <td>{{ $fmtDate($su->dols_approval_date) }}</td>
            </tr>
            <tr>
                <th>LPA – Health & Welfare</th>
                <td>{{ $yesNo($su->lpa_health_welfare) }}</td>
            </tr>
            <tr>
                <th>LPA – Finance & Property</th>
                <td>{{ $yesNo($su->lpa_finance_property) }}</td>
            </tr>
            @if($su->advanced_decision_note)
                <tr>
                    <th>Advanced Decision</th>
                    <td>{{ $su->advanced_decision_note }}</td>
                </tr>
            @endif
        </table>

        {{-- GP & Pharmacy --}}
        @if($su->gp_practice_name || $su->gp_phone || $su->pharmacy_name)
            <div class="section-title">GP & Pharmacy</div>
            <table>
                @if($su->gp_practice_name)
                    <tr>
                        <th>GP Practice</th>
                        <td>{{ $su->gp_practice_name }}</td>
                    </tr>
                @endif
                @if($su->gp_contact_name)
                    <tr>
                        <th>GP Contact</th>
                        <td>{{ $su->gp_contact_name }}</td>
                    </tr>
                @endif
                @if($su->gp_phone)
                    <tr>
                        <th>GP Phone</th>
                        <td>{{ $su->gp_phone }}</td>
                    </tr>
                @endif
                @if($su->gp_email)
                    <tr>
                        <th>GP Email</th>
                        <td>{{ $su->gp_email }}</td>
                    </tr>
                @endif
                @if($su->gp_address)
                    <tr>
                        <th>GP Address</th>
                        <td>{{ $su->gp_address }}</td>
                    </tr>
                @endif
                @if($su->pharmacy_name)
                    <tr>
                        <th>Pharmacy</th>
                        <td>{{ $su->pharmacy_name }}</td>
                    </tr>
                @endif
                @if($su->pharmacy_phone)
                    <tr>
                        <th>Pharmacy Phone</th>
                        <td>{{ $su->pharmacy_phone }}</td>
                    </tr>
                @endif
            </table>
        @endif

        {{-- Tags --}}
        @if(!empty($tags))
            <div class="section-title">Tags</div>
            <div>
                @foreach($tags as $tag)
                    <span class="tag">{{ $tag }}</span>
                @endforeach
            </div>
        @endif

    </div>
</body>

</html>