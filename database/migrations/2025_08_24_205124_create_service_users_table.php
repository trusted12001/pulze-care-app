<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service_users', function (Blueprint $table) {
            $table->id();

            // Multi-tenant
            $table->unsignedBigInteger('tenant_id')->index();

            // Core Identity
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('preferred_name')->nullable();
            $table->date('date_of_birth');
            $table->string('sex_at_birth')->nullable();     // male|female|intersex|prefer_not_to_say
            $table->string('gender_identity')->nullable();
            $table->string('pronouns')->nullable();
            $table->string('nhs_number')->nullable();       // validate format in Request
            $table->string('national_insurance_no')->nullable();
            $table->string('photo_path')->nullable();

            // Contact & Address
            $table->string('primary_phone')->nullable();
            $table->string('secondary_phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('city');
            $table->string('county')->nullable();
            $table->string('postcode');
            $table->string('country')->default('UK');

            // Placement / Location
            $table->string('placement_type')->nullable();   // care_home|supported_living|domiciliary|day_center
            $table->foreignId('location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->string('room_number')->nullable();
            $table->date('admission_date');
            $table->date('expected_discharge_date')->nullable();
            $table->date('discharge_date')->nullable();
            $table->string('status')->default('active');    // active|on_leave|discharged|deceased

            // Funding (MVP)
            $table->string('funding_type')->nullable();     // local_authority|nhs_ccg|self_funded|mixed
            $table->string('funding_authority')->nullable();
            $table->string('purchase_order_ref')->nullable();
            $table->decimal('weekly_rate', 10, 2)->nullable();

            // Medical Summary (headlines)
            $table->string('primary_diagnosis')->nullable();
            $table->text('other_diagnoses')->nullable();
            $table->text('allergies_summary')->nullable();
            $table->string('diet_type')->nullable();
            $table->text('intolerances')->nullable();
            $table->string('mobility_status')->nullable();
            $table->text('communication_needs')->nullable();
            $table->boolean('behaviour_support_plan')->default(false);
            $table->boolean('seizure_care_plan')->default(false);
            $table->boolean('diabetes_care_plan')->default(false);
            $table->boolean('oxygen_therapy')->default(false);
            $table->string('baseline_bp')->nullable();
            $table->string('baseline_hr')->nullable();
            $table->string('baseline_spo2')->nullable();
            $table->string('baseline_temp')->nullable();

            // Risk & Alerts
            $table->string('fall_risk')->nullable();        // low|medium|high
            $table->string('choking_risk')->nullable();
            $table->string('pressure_ulcer_risk')->nullable();
            $table->boolean('wander_elopement_risk')->default(false);
            $table->boolean('safeguarding_flag')->default(false);
            $table->boolean('infection_control_flag')->default(false);
            $table->string('smoking_status')->nullable();   // non_smoker|smoker|ex_smoker|unknown

            // Legal & Consent
            $table->string('capacity_status')->nullable();  // full|partial|lacks_capacity|fluctuating
            $table->dateTime('consent_obtained_at')->nullable();
            $table->string('dnacpr_status')->nullable();    // none|in_place|not_applicable
            $table->date('dnacpr_review_date')->nullable();
            $table->boolean('dols_in_place')->default(false);
            $table->date('dols_approval_date')->nullable();
            $table->boolean('lpa_health_welfare')->default(false);
            $table->boolean('lpa_finance_property')->default(false);
            $table->text('advanced_decision_note')->nullable();

            // Culture & Preferences
            $table->string('ethnicity')->nullable();
            $table->string('religion')->nullable();
            $table->string('primary_language')->nullable();
            $table->boolean('interpreter_required')->default(false);
            $table->text('personal_preferences')->nullable();
            $table->text('food_preferences')->nullable();

            // GP / Healthcare (MVP inline)
            $table->string('gp_practice_name')->nullable();
            $table->string('gp_contact_name')->nullable();
            $table->string('gp_phone')->nullable();
            $table->string('gp_email')->nullable();
            $table->text('gp_address')->nullable();
            $table->string('pharmacy_name')->nullable();
            $table->string('pharmacy_phone')->nullable();

            // System meta
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->json('tags')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'last_name', 'first_name']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_users');
    }
};
