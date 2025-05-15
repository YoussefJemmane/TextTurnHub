<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\CompanyProfile;
use App\Models\ArtisanProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get roles
        $companyRole = Role::where('name', 'company')->first();
        $artisanRole = Role::where('name', 'artisan')->first();
        $userRole = Role::where('name', 'user')->first();

        // Create regular users
        $this->createRegularUsers($userRole);

        // Create company users
        $this->createCompanyUsers($companyRole);

        // Create artisan users
        $this->createArtisanUsers($artisanRole);
    }

    /**
     * Create regular users
     */
    private function createRegularUsers($userRole): void
    {
        $users = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@example.com',
                'password' => Hash::make('password123'),
                'profile' => [
                    'sustainability_importance' => 'Very important',
                    'interests' => 'Recycled crafts, zero waste lifestyle, sustainable fashion',
                ]
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael.chen@example.com',
                'password' => Hash::make('password123'),
                'profile' => [
                    'sustainability_importance' => 'Somewhat important',
                    'interests' => 'Upcycling, DIY projects, environmental advocacy',
                ]
            ],
            [
                'name' => 'Jessica Smith',
                'email' => 'jessica.smith@example.com',
                'password' => Hash::make('password123'),
                'profile' => [
                    'sustainability_importance' => 'Extremely important',
                    'interests' => 'Sustainable living, eco-friendly materials, waste reduction',
                ]
            ],
        ];

        foreach ($users as $userData) {
            $profile = $userData['profile'];
            unset($userData['profile']);

            $user = User::create($userData);
            $user->assignRole($userRole);

            UserProfile::create([
                'user_id' => $user->id,
                'sustainability_importance' => $profile['sustainability_importance'],
                'interests' => $profile['interests'],
            ]);
        }
    }

    /**
     * Create company users
     */
    private function createCompanyUsers($companyRole): void
    {
        $companies = [
            [
                'name' => 'John Miller',
                'email' => 'john.miller@ecotech.com',
                'password' => Hash::make('password123'),
                'company' => [
                    'company_name' => 'EcoTech Solutions',
                    'company_size' => 'Medium (50-250 employees)',
                    'waste_types' => 'Plastic waste, textile scraps, electronic components',
                ]
            ],
            [
                'name' => 'Amanda Rodriguez',
                'email' => 'amanda@greenindustries.com',
                'password' => Hash::make('password123'),
                'company' => [
                    'company_name' => 'Green Industries Inc.',
                    'company_size' => 'Large (250+ employees)',
                    'waste_types' => 'Wood scraps, paper waste, metal shavings',
                ]
            ],
            [
                'name' => 'Robert Lee',
                'email' => 'robert@sustainablecraft.com',
                'password' => Hash::make('password123'),
                'company' => [
                    'company_name' => 'Sustainable Craft Co.',
                    'company_size' => 'Small (10-50 employees)',
                    'waste_types' => 'Fabric remnants, leather scraps, yarn waste',
                ]
            ],
        ];

        foreach ($companies as $companyData) {
            $company = $companyData['company'];
            unset($companyData['company']);

            $user = User::create($companyData);
            $user->assignRole($companyRole);

            CompanyProfile::create([
                'user_id' => $user->id,
                'company_name' => $company['company_name'],
                'company_size' => $company['company_size'],
                'waste_types' => $company['waste_types'],
            ]);
        }
    }

    /**
     * Create artisan users
     */
    private function createArtisanUsers($artisanRole): void
    {
        $artisans = [
            [
                'name' => 'Elena Martinez',
                'email' => 'elena@craftsbydesign.com',
                'password' => Hash::make('password123'),
                'artisan' => [
                    'artisan_specialty' => 'Textile Upcycling',
                    'artisan_experience' => '5-10 years',
                    'materials_interest' => 'Fabric scraps, yarn waste, clothing remnants',
                ]
            ],
            [
                'name' => 'David Wilson',
                'email' => 'david@repurposedart.com',
                'password' => Hash::make('password123'),
                'artisan' => [
                    'artisan_specialty' => 'Metal Sculpture',
                    'artisan_experience' => '10+ years',
                    'materials_interest' => 'Scrap metal, industrial waste, electronic components',
                ]
            ],
            [
                'name' => 'Sophie Kim',
                'email' => 'sophie@ecocreations.com',
                'password' => Hash::make('password123'),
                'artisan' => [
                    'artisan_specialty' => 'Paper Crafts',
                    'artisan_experience' => '3-5 years',
                    'materials_interest' => 'Paper waste, cardboard, packaging materials',
                ]
            ],
        ];

        foreach ($artisans as $artisanData) {
            $artisan = $artisanData['artisan'];
            unset($artisanData['artisan']);

            $user = User::create($artisanData);
            $user->assignRole($artisanRole);

            ArtisanProfile::create([
                'user_id' => $user->id,
                'artisan_specialty' => $artisan['artisan_specialty'],
                'artisan_experience' => $artisan['artisan_experience'],
                'materials_interest' => $artisan['materials_interest'],
            ]);
        }
    }
}