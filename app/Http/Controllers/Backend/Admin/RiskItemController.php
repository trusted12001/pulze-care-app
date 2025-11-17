<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiskAssessment;          // item rows
use App\Models\RiskAssessmentProfile;   // parent profile
use App\Models\RiskType;                // catalog
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RiskItemController extends Controller
{
    /**
     * Adjust this if your FK is named differently (e.g., 'profile_id').
     */
    private const PROFILE_FK = 'risk_assessment_profile_id';

    public function create(Request $request)
    {
        // Prefill IDs if provided as query (?profile=&type=)
        $prefillProfileId = $request->integer('profile');
        $prefillTypeId = $request->integer('type');

        $profile = $prefillProfileId
            ? RiskAssessmentProfile::query()->find($prefillProfileId)
            : null;

        $riskType = $prefillTypeId
            ? RiskType::query()->find($prefillTypeId)
            : null;

        // Dropdown data
        $profiles = RiskAssessmentProfile::query()
            ->with(['serviceUser:id,first_name,last_name'])
            ->latest('created_at')
            ->get(['id', 'service_user_id', 'title', 'status', 'created_at']);

        $types = RiskType::query()
            ->orderBy('name')
            ->get(['id', 'name', 'default_guidance', 'default_matrix']);

        $owners = User::query()
            ->orderBy('first_name')
            ->get(['id', 'first_name']);

        return view('backend.admin.risk-items.create', [
            'profiles' => $profiles,
            'types' => $types,
            'owners' => $owners,
            'profile' => $profile,
            'riskType' => $riskType,
            'profileFk' => self::PROFILE_FK, // so the view knows what to post
        ]);
    }

    public function store(Request $request, ?RiskAssessmentProfile $riskAssessment = null)
    {
        // If nested route was used, prefer that profile id
        $profileId = $riskAssessment?->id ?? $request->input(self::PROFILE_FK);
        $service_user_id = $riskAssessment?->service_user_id;
        $title = $riskAssessment?->title;

        $data = $request->validate([
            self::PROFILE_FK => ['nullable', 'integer', 'exists:risk_assessment_profiles,id'],
            'risk_type_id' => ['required', 'integer', 'exists:risk_types,id'],
            'hazard' => ['required', 'string', 'max:1000'],
            'context' => ['required', 'string', 'max:1000'],
            'likelihood' => ['required', 'integer', 'min:1', 'max:5'],
            'severity' => ['required', 'integer', 'min:1', 'max:5'],
            'controls' => ['nullable', 'string'],
            'residual_likelihood' => ['nullable', 'integer', 'min:1', 'max:5'],
            'residual_severity' => ['nullable', 'integer', 'min:1', 'max:5'],
            'owner_id' => ['nullable', 'integer', 'exists:users,id'],
            'review_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:draft,active,archived'],
            'action' => ['nullable', 'in:save,publish,save_add_another'],
        ]);

        // Ensure the correct profile id is used
        $data[self::PROFILE_FK] = $profileId;
        // Note: service_user_id and title were removed from risk_assessments table
        // They are now stored in risk_assessment_profiles table

        // Compute scores - use risk_score not score
        $data['risk_score'] = (int) ($data['likelihood'] * $data['severity']);

        // Calculate risk band based on score
        $score = $data['risk_score'];
        $data['risk_band'] = match (true) {
            $score >= 20 => 'critical',
            $score >= 15 => 'high',
            $score >= 10 => 'medium',
            default => 'low',
        };

        if (!empty($data['residual_likelihood']) && !empty($data['residual_severity'])) {
            $data['residual_score'] = (int) ($data['residual_likelihood'] * $data['residual_severity']);
        }

        // Default status
        $action = $request->input('action', 'save');
        $data['status'] = $action === 'publish' ? 'active' : ($data['status'] ?? 'draft');

        // Audit - only created_by exists in the table
        $data['created_by'] = auth()->id();

        // Create item row
        /** @var \App\Models\RiskAssessment $item */
        $item = RiskAssessment::query()->create($data);

        // Decide where to go back
        $targetProfileId = $data[self::PROFILE_FK];
        if ($action === 'save_add_another') {
            // Back to form with same profile & type preselected
            return redirect()
                ->route('backend.admin.risk-items.create', [
                    'profile' => $targetProfileId,
                    'type' => $data['risk_type_id'],
                ])
                ->with('success', 'Risk item added. You can add another.');
        }

        // Default: back to profile show, anchored to that type accordion
        return redirect()
            ->route('backend.admin.risk-assessments.show', $targetProfileId)
            ->with('success', 'Risk item added successfully.')
            ->withFragment('type-' . $data['risk_type_id']); // scroll to accordion
    }


    public function edit(\App\Models\RiskAssessment $riskItem): View
    {
        // FK name we used when creating items
        $profileFk = self::PROFILE_FK;

        $types = \App\Models\RiskType::query()
            ->orderBy('name')
            ->get(['id', 'name', 'default_guidance', 'default_matrix']);

        $owners = \App\Models\User::query()
            ->orderBy('first_name')
            ->get(['id', 'first_name']);

        // If you want to allow moving item to another profile, load profiles
        $profiles = \App\Models\RiskAssessmentProfile::query()
            ->with(['serviceUser:id,first_name,last_name'])
            ->latest('created_at')
            ->get(['id', 'service_user_id', 'title', 'status', 'created_at']);

        return view('backend.admin.risk-items.edit', [
            'riskItem' => $riskItem,
            'types' => $types,
            'owners' => $owners,
            'profiles' => $profiles,
            'profileFk' => $profileFk,
        ]);
    }


    public function update(Request $request, \App\Models\RiskAssessment $riskItem): RedirectResponse
    {
        $profileFk = self::PROFILE_FK;

        $data = $request->validate([
            $profileFk => ['required', 'integer', 'exists:risk_assessment_profiles,id'],
            'risk_type_id' => ['required', 'integer', 'exists:risk_types,id'],
            'context' => ['nullable', 'string', 'max:1000'],
            'hazard' => ['required', 'string', 'max:1000'],
            'likelihood' => ['required', 'integer', 'min:1', 'max:5'],
            'severity' => ['required', 'integer', 'min:1', 'max:5'],
            'controls' => ['nullable', 'string'],
            'residual_likelihood' => ['nullable', 'integer', 'min:1', 'max:5'],
            'residual_severity' => ['nullable', 'integer', 'min:1', 'max:5'],
            'owner_id' => ['nullable', 'integer', 'exists:users,id'],
            'review_date' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in(['draft', 'active', 'archived'])],
            'action' => ['nullable', Rule::in(['save', 'publish'])],
        ]);

        // Recompute scores - use risk_score not score
        $data['risk_score'] = (int) ($data['likelihood'] * $data['severity']);

        // Calculate risk band based on score
        $score = $data['risk_score'];
        $data['risk_band'] = match (true) {
            $score >= 20 => 'critical',
            $score >= 15 => 'high',
            $score >= 10 => 'medium',
            default => 'low',
        };

        $data['residual_score'] =
            (!empty($data['residual_likelihood']) && !empty($data['residual_severity']))
            ? (int) ($data['residual_likelihood'] * $data['residual_severity'])
            : null;

        // Status based on action
        $action = $request->input('action', 'save');
        $data['status'] = $action === 'publish' ? 'active' : ($data['status'] ?? 'draft');

        // Audit - updated_by_id doesn't exist, only created_by
        // The timestamps will be updated automatically

        $riskItem->update($data);

        $profileId = $data[$profileFk];
        return redirect()
            ->route('backend.admin.risk-assessments.show', $profileId)
            ->with('success', 'Risk item updated.');
    }



    public function destroy(\App\Models\RiskAssessment $riskItem): RedirectResponse
    {
        $profileFk = self::PROFILE_FK;
        $profileId = $riskItem->{$profileFk};

        $riskItem->delete();

        return redirect()
            ->route('backend.admin.risk-assessments.show', $profileId)
            ->with('success', 'Risk item deleted.');
    }
}
