<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Staff Profile – {{ $staff->user->full_name ?? 'Staff Profile' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Basic neutral styling for A4 print --}}
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: #f3f4f6;
            color: #111827;
        }

        .page {
            max-width: 800px;
            margin: 16px auto;
            background: #ffffff;
            padding: 24px 28px;
            border-radius: 8px;
            box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.06), 0 10px 25px rgba(15, 23, 42, 0.08);
        }

        h1,
        h2,
        h3,
        h4 {
            margin: 0;
            font-weight: 700;
            color: #111827;
        }

        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 12px;
        }

        .org-name {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .staff-name {
            font-size: 22px;
            line-height: 1.2;
        }

        .staff-subtitle {
            font-size: 14px;
            color: #4b5563;
            margin-top: 4px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 999px;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .status-leaver {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .status-default {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .photo-box {
            width: 96px;
            height: 96px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-box span {
            font-size: 11px;
            color: #6b7280;
            text-align: center;
            padding: 4px;
        }

        .meta-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 24px;
            font-size: 12px;
            color: #4b5563;
            margin-top: 8px;
        }

        .meta-item span {
            font-weight: 600;
            color: #374151;
        }

        .section {
            margin-top: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .section-header {
            padding: 8px 12px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-body {
            padding: 12px 12px 8px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px 24px;
        }

        .field-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-bottom: 2px;
        }

        .field-value {
            font-size: 13px;
            font-weight: 500;
            color: #111827;
        }

        .field-value.muted {
            color: #6b7280;
            font-weight: 400;
        }

        .notes-box {
            border-radius: 6px;
            border: 1px dashed #d1d5db;
            padding: 8px 10px;
            font-size: 12px;
            white-space: pre-wrap;
            background: #f9fafb;
        }

        .footer {
            margin-top: 20px;
            font-size: 11px;
            color: #9ca3af;
            text-align: right;
        }

        .noprint-actions {
            margin: 8px auto 0;
            max-width: 800px;
            text-align: right;
        }

        .btn-print {
            display: inline-block;
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            background: #111827;
            color: #f9fafb;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-print:hover {
            background: #000000;
        }

        @media print {
            body {
                background: #ffffff;
            }

            .page {
                margin: 0;
                max-width: 100%;
                box-shadow: none;
                border-radius: 0;
                border: none;
            }

            .noprint-actions {
                display: none !important;
            }

            @page {
                size: A4;
                margin: 12mm;
            }
        }

        @media (max-width: 640px) {
            .page {
                margin: 0;
                border-radius: 0;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>

    <div class="noprint-actions">
        <button class="btn-print" onclick="window.print()">Print / Save as PDF</button>
    </div>

    <div class="page">
        @php
            $user = $staff->user;
            $fullName = $user->full_name ?? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
            $status = $staff->employment_status ?? 'unknown';

            $statusClass = 'status-default';
            if ($status === 'active') {
                $statusClass = 'status-active';
            } elseif (in_array($status, ['leaver', 'terminated', 'suspended'])) {
                $statusClass = 'status-leaver';
            }

            $orgName = config('app.name', 'Pulze Care App');

            $fmtDate = function ($d) {
                return $d ? \Illuminate\Support\Carbon::parse($d)->format('d M Y') : '—';
            };
        @endphp

        <header class="header">
            <div>
                <div class="org-name">{{ $orgName }} – Staff Profile</div>
                <h1 class="staff-name">{{ $fullName ?: '—' }}</h1>
                <div class="staff-subtitle">
                    {{ $staff->job_title ?: 'Role not specified' }}
                </div>

                <div style="margin-top: 8px;">
                    <span class="status-badge {{ $statusClass }}">
                        <span class="status-dot" style="background:
                            {{ $status === 'active' ? '#22c55e' :
    (in_array($status, ['leaver', 'terminated', 'suspended']) ? '#f97316' : '#6b7280') }};">
                        </span>
                        {{ ucfirst($status) }}
                    </span>
                </div>

                <div class="meta-row">
                    @if($staff->employment_type)
                        <div class="meta-item">
                            <span>Employment Type:</span> {{ ucfirst($staff->employment_type) }}
                        </div>
                    @endif
                    @if($staff->engagement_basis)
                        <div class="meta-item">
                            <span>Basis:</span> {{ ucfirst(str_replace('_', ' ', $staff->engagement_basis)) }}
                        </div>
                    @endif
                    @if($staff->hire_date)
                        <div class="meta-item">
                            <span>Hire Date:</span> {{ $fmtDate($staff->hire_date) }}
                        </div>
                    @endif
                    @if($staff->right_to_work_verified_at)
                        <div class="meta-item">
                            <span>Right to Work:</span> Verified {{ $fmtDate($staff->right_to_work_verified_at) }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="photo-box">
                @if(!empty($staff->passport_photo_url))
                    <img src="{{ $staff->passport_photo_url }}" alt="Passport photo">
                @else
                    <span>No passport photo<br>uploaded</span>
                @endif
            </div>
        </header>

        {{-- Contact & Work Details --}}
        <section class="section">
            <div class="section-header">
                <span>Contact & Work Details</span>
            </div>
            <div class="section-body">
                <div class="grid">
                    <div>
                        <div class="field-label">Work Email</div>
                        <div class="field-value">
                            {{ $staff->work_email ?? $user->email ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <div class="field-label">Phone</div>
                        <div class="field-value">
                            {{ $staff->phone ?? '—' }}
                        </div>
                    </div>
                    <div>
                        <div class="field-label">Location</div>
                        <div class="field-value">
                            @if(method_exists($staff, 'workLocation') && $staff->relationLoaded('workLocation') && $staff->workLocation)
                                {{ $staff->workLocation->name }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="field-label">Line Manager</div>
                        <div class="field-value">
                            @if(method_exists($staff, 'lineManager') && $staff->relationLoaded('lineManager') && $staff->lineManager)
                                {{ $staff->lineManager->full_name ?? ($staff->lineManager->first_name . ' ' . $staff->lineManager->last_name) }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Compliance & Checks --}}
        <section class="section">
            <div class="section-header">
                <span>Compliance & Checks</span>
            </div>
            <div class="section-body">
                <div class="grid">
                    <div>
                        <div class="field-label">DBS Number</div>
                        <div class="field-value">{{ $staff->dbs_number ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="field-label">DBS Issued</div>
                        <div class="field-value">{{ $fmtDate($staff->dbs_issued_at) }}</div>
                    </div>
                    <div>
                        <div class="field-label">DBS Update Service</div>
                        <div class="field-value">
                            @if(!is_null($staff->dbs_update_service))
                                {{ $staff->dbs_update_service ? 'Yes' : 'No' }}
                            @else
                                —
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="field-label">Mandatory Training Complete</div>
                        <div class="field-value">
                            {{ $fmtDate($staff->mandatory_training_completed_at) }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Notes --}}
        @if(!empty($staff->notes))
            <section class="section">
                <div class="section-header">
                    <span>Notes</span>
                </div>
                <div class="section-body">
                    <div class="notes-box">
                        {{ $staff->notes }}
                    </div>
                </div>
            </section>
        @endif

        <div class="footer">
            Generated on {{ now()->format('d M Y, H:i') }} · Staff ID #{{ $staff->id }} · Tenant
            #{{ $staff->tenant_id }}
        </div>
    </div>

</body>

</html>