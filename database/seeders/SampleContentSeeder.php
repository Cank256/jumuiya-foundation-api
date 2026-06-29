<?php

namespace Database\Seeders;

use App\Models\AnnualReport;
use App\Models\Career;
use App\Models\Event;
use App\Models\NewsArticle;
use App\Models\Tender;
use App\Models\TenderDocument;
use Illuminate\Database\Seeder;

class SampleContentSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- Events ----------
        Event::create([
            'title'            => 'Annual Community Forum 2025',
            'description'      => '<p>Join us for the Annual Community Forum where we discuss progress, challenges, and opportunities for the communities we serve.</p>',
            'category'         => 'Forum',
            'location'         => 'Kampala, Uganda',
            'start_date'       => '2025-09-20 09:00:00',
            'end_date'         => '2025-09-21 17:00:00',
            'time'             => '9:00 AM – 5:00 PM EAT',
            'seats'            => 'Limited seats available',
            'status'           => 'upcoming',
            'featured'         => true,
            'registration_url' => 'https://forms.jumuiyafoundation.org/forum-2025',
        ]);

        Event::create([
            'title'    => 'Gender Empowerment Workshop',
            'category' => 'Workshop',
            'location' => 'Gulu, Uganda',
            'start_date' => '2025-07-15 08:30:00',
            'end_date'   => '2025-07-15 16:00:00',
            'time'     => '8:30 AM – 4:00 PM EAT',
            'status'   => 'completed',
        ]);

        // ---------- News ----------
        NewsArticle::create([
            'slug'         => 'community-outreach-june-2025',
            'title'        => 'Community Outreach Programme Reaches 500 Families',
            'excerpt'      => 'Our latest outreach programme has successfully connected with over 500 families across rural Uganda, delivering essential health and educational resources.',
            'content'      => '<p>The Jumuiya Development Foundation is proud to announce a major milestone in our community outreach efforts...</p><p>Working alongside local volunteers and community leaders, our teams visited 12 villages over the course of three weeks.</p>',
            'category'     => 'Community',
            'published_at' => '2025-06-10 08:00:00',
            'featured'     => true,
        ]);

        NewsArticle::create([
            'slug'         => 'education-scholarship-program-2025',
            'title'        => '50 Students Awarded Scholarships for the 2025 Academic Year',
            'excerpt'      => 'Jumuiya Development Foundation awards scholarships to 50 deserving students from underserved communities.',
            'content'      => '<p>We are delighted to announce the 2025 cohort of scholarship recipients...</p>',
            'category'     => 'Education',
            'published_at' => '2025-05-20 10:00:00',
        ]);

        // ---------- Careers ----------
        Career::create([
            'title'                => 'Programme Officer – Education',
            'status'               => 'active',
            'department'           => 'Education & Skills',
            'employment_type'      => 'full-time',
            'location'             => 'Kampala, Uganda',
            'salary_range'         => 'UGX 3,500,000 – 4,500,000/month',
            'application_deadline' => '2025-08-01',
            'purpose_of_role'      => '<p>Lead the design and implementation of education programmes across target districts.</p>',
            'responsibilities'     => '<ul><li>Develop programme plans and budgets</li><li>Coordinate with district education officers</li><li>Monitor and evaluate programme outcomes</li></ul>',
            'requirements'         => '<ul><li>Bachelor\'s degree in Education or related field</li><li>3+ years of programme management experience</li></ul>',
            'apply_here'           => 'https://forms.jumuiyafoundation.org/apply',
        ]);

        Career::create([
            'title'           => 'Community Volunteer Coordinator',
            'status'          => 'active',
            'department'      => 'Community Development',
            'employment_type' => 'volunteer',
            'location'        => 'Various Locations, Uganda',
            'application_deadline' => '2025-09-01',
            'purpose_of_role' => '<p>Coordinate and support volunteer activities across community development projects.</p>',
        ]);

        // ---------- Tenders ----------
        $tender = Tender::create([
            'title'            => 'Supply of IT Equipment for District Offices',
            'reference_number' => 'JDF/PROC/2025/004',
            'description'      => '<p>Jumuiya Development Foundation invites bids for the supply and delivery of IT equipment to our district offices.</p>',
            'requirements'     => '<p>Bidders must be registered companies with at least 3 years of experience in IT equipment supply.</p>',
            'deadline'         => '2025-08-15 17:00:00',
            'status'           => 'open',
        ]);

        TenderDocument::create([
            'tender_id' => $tender->id,
            'name'      => 'JDF-RFP-2025-004.pdf',
            'type'      => 'rfp',
            'path'      => 'tenders/sample-rfp.pdf',
            'size'      => 1048576,
        ]);

        // ---------- Annual Reports ----------
        AnnualReport::create([
            'label'      => '2024 / 2025 Annual Report',
            'year'       => '2024-25',
            'href'       => 'https://jumuiyafoundation.org/reports/jdf-annual-2024-25.pdf',
            'sort_order' => 1,
        ]);

        AnnualReport::create([
            'label'      => '2023 / 2024 Annual Report',
            'year'       => '2023-24',
            'href'       => 'https://jumuiyafoundation.org/reports/jdf-annual-2023-24.pdf',
            'sort_order' => 2,
        ]);

        $this->command->info('Sample content seeded successfully.');
    }
}
