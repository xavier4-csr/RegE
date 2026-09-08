<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplianceStepsSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the compliance_steps table with Kenyan post-registration obligations.
     */
    public function run(): void
    {
        $steps = [
            [
                'title'           => 'Obtain KRA PIN',
                'description'     => 'Register for a Personal and Business KRA PIN. This is required to file taxes and transact with the government.',
                'authority'       => 'Kenya Revenue Authority (KRA)',
                'portal_url'      => 'https://itax.kra.go.ke',
                'days_after_registration' => 30,
                'is_mandatory'    => true,
                'applies_to_business_types' => null,
                'required_documents'        => ['Business registration certificate', 'ID/Passport'],
                'sort_order'      => 1,
            ],
            [
                'title'           => 'Register for VAT (if applicable)',
                'description'     => 'Mandatory VAT registration applies if your annual turnover exceeds KES 5 million. Voluntary registration is also possible.',
                'authority'       => 'Kenya Revenue Authority (KRA)',
                'portal_url'      => 'https://itax.kra.go.ke',
                'days_after_registration' => 30,
                'is_mandatory'    => false,
                'applies_to_business_types' => null,
                'required_documents'        => ['KRA PIN certificate'],
                'sort_order'      => 2,
            ],
            [
                'title'           => 'Register for SHIF',
                'description'     => 'Register your business and employees (if any) for the Social Health Insurance Fund (SHIF).',
                'authority'       => 'Social Health Authority (SHA)',
                'portal_url'      => 'https://sha.go.ke',
                'days_after_registration' => 30,
                'is_mandatory'    => true,
                'applies_to_business_types' => null,
                'required_documents'        => ['KRA PIN certificate', 'Business registration certificate'],
                'sort_order'      => 3,
            ],
            [
                'title'           => 'Register for NSSF',
                'description'     => 'Register your business with the National Social Security Fund (NSSF) and remit contributions for yourself and employees.',
                'authority'       => 'National Social Security Fund (NSSF)',
                'portal_url'      => 'https://www.nssf.or.ke',
                'days_after_registration' => 30,
                'is_mandatory'    => true,
                'applies_to_business_types' => null,
                'required_documents'        => ['KRA PIN certificate', 'Business registration certificate'],
                'sort_order'      => 4,
            ],
            [
                'title'           => 'Obtain County Business Permit',
                'description'     => 'Apply for a Single Business Permit from your county government. Fees vary by county and business type.',
                'authority'       => 'County Government',
                'portal_url'      => null,
                'days_after_registration' => 30,
                'is_mandatory'    => true,
                'applies_to_business_types' => null,
                'required_documents'        => ['KRA PIN certificate', 'Business registration certificate', 'Lease agreement'],
                'sort_order'      => 5,
            ],
            [
                'title'           => 'File Annual Return (BRS)',
                'description'     => 'File your annual return with the Business Registration Service (BRS) to keep your business in good standing.',
                'authority'       => 'Business Registration Service (BRS)',
                'portal_url'      => 'https://brs.go.ke',
                'days_after_registration' => 365,
                'is_mandatory'    => true,
                'applies_to_business_types' => null,
                'required_documents'        => ['Business registration certificate'],
                'sort_order'      => 6,
            ],
            [
                'title'           => 'Obtain Sector-Specific Licences',
                'description'     => 'Depending on your industry you may need additional licences (e.g. health, food handling, transport, liquor). Check with the relevant regulator.',
                'authority'       => 'Relevant Regulatory Body',
                'portal_url'      => null,
                'days_after_registration' => 60,
                'is_mandatory'    => false,
                'applies_to_business_types' => null,
                'required_documents'        => ['Business registration certificate', 'County permit'],
                'sort_order'      => 7,
            ],
            [
                'title'           => 'Register for PAYE (if employing)',
                'description'     => 'If you have employees, register for Pay As You Earn (PAYE) and remit monthly employee income tax.',
                'authority'       => 'Kenya Revenue Authority (KRA)',
                'portal_url'      => 'https://itax.kra.go.ke',
                'days_after_registration' => 30,
                'is_mandatory'    => false,
                'applies_to_business_types' => null,
                'required_documents'        => ['KRA PIN certificate', 'Employee IDs'],
                'sort_order'      => 8,
            ],
        ];

        foreach ($steps as $step) {
            $step['applies_to_business_types'] = json_encode($step['applies_to_business_types']);
            $step['required_documents']        = json_encode($step['required_documents']);
            $step['created_at'] = now();
            $step['updated_at'] = now();

            DB::table('compliance_steps')->updateOrInsert(
                ['title' => $step['title']],
                $step
            );
        }
    }
}
