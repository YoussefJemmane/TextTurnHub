<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use App\Models\TextileWaste;
use Illuminate\Database\Seeder;

class TextileWasteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get company profiles
        $companyProfiles = CompanyProfile::all();

        // Create textile wastes for each company
        foreach ($companyProfiles as $profile) {
            // Create different types of textile waste based on company waste types
            if (str_contains(strtolower($profile->waste_types), 'textile') || 
                str_contains(strtolower($profile->waste_types), 'fabric')) {
                $this->createTextileWastes($profile);
            }
            
            if (str_contains(strtolower($profile->waste_types), 'yarn')) {
                $this->createYarnWastes($profile);
            }
            
            if (str_contains(strtolower($profile->company_name), 'sustainable craft')) {
                // Create specialized wastes for Sustainable Craft Co.
                $this->createLeatherWastes($profile);
            }
            
            // Create some generic wastes for all companies
            $this->createGenericWastes($profile);
        }
    }

    /**
     * Create textile/fabric wastes
     */
    private function createTextileWastes($profile): void
    {
        $wastes = [
            [
                'title' => 'Denim Fabric Scraps',
                'description' => 'Mixed denim fabric scraps from jean production. Various shades of blue, good condition.',
                'waste_type' => 'scraps',
                'material_type' => 'Denim',
                'quantity' => 25.5,
                'unit' => 'kg',
                'condition' => 'Good - clean and ready to use',
                'color' => 'Blue (various shades)',
                'composition' => '98% cotton, 2% elastane',
                'minimum_order_quantity' => 5.0,
                'price_per_unit' => 3.50,
                'location' => 'Warehouse A, Bay 12',
                'availability_status' => 'available',
                'images' => 'denim_scraps.jpg',
                'sustainability_metrics' => json_encode([
                    'water_saved' => '25000 liters',
                    'co2_reduction' => '15kg',
                    'circular_economy_impact' => 'high'
                ]),
            ],
            [
                'title' => 'Cotton Fabric Remnants',
                'description' => 'High-quality cotton fabric remnants from t-shirt production. Various colors available.',
                'waste_type' => 'offcuts',
                'material_type' => 'Cotton Jersey',
                'quantity' => 18.75,
                'unit' => 'kg',
                'condition' => 'Excellent - unused material',
                'color' => 'White, Black, Red, Navy',
                'composition' => '100% organic cotton',
                'minimum_order_quantity' => 2.5,
                'price_per_unit' => 5.25,
                'location' => 'Main Facility, Zone C',
                'availability_status' => 'available',
                'images' => 'cotton_remnants.jpg',
                'sustainability_metrics' => json_encode([
                    'water_saved' => '12000 liters',
                    'co2_reduction' => '8kg',
                    'circular_economy_impact' => 'medium'
                ]),
            ],
            [
                'title' => 'Polyester Fabric Rolls',
                'description' => 'End-of-roll polyester fabric pieces, suitable for smaller projects. Various patterns.',
                'waste_type' => 'fabric',
                'material_type' => 'Polyester',
                'quantity' => 150.0,
                'unit' => 'meters',
                'condition' => 'Good - minor imperfections',
                'color' => 'Various patterns and colors',
                'composition' => '100% recycled polyester',
                'minimum_order_quantity' => 10.0,
                'price_per_unit' => 2.00,
                'location' => 'Storage Facility B',
                'availability_status' => 'available',
                'images' => 'polyester_rolls.jpg',
                'sustainability_metrics' => json_encode([
                    'plastic_diverted' => '45kg',
                    'co2_reduction' => '22kg',
                    'circular_economy_impact' => 'medium'
                ]),
            ],
        ];

        $this->createWastes($profile->id, $wastes);
    }

    /**
     * Create yarn wastes
     */
    private function createYarnWastes($profile): void
    {
        $wastes = [
            [
                'title' => 'Wool Yarn Remainders',
                'description' => 'High-quality wool yarn remainders from sweater production. Mixed colors, perfect for small knitting projects.',
                'waste_type' => 'yarn',
                'material_type' => 'Wool',
                'quantity' => 8.5,
                'unit' => 'kg',
                'condition' => 'Excellent - unused material',
                'color' => 'Assorted colors',
                'composition' => '100% merino wool',
                'minimum_order_quantity' => 1.0,
                'price_per_unit' => 12.00,
                'location' => 'Production Floor, Section D',
                'availability_status' => 'available',
                'images' => 'wool_yarn.jpg',
                'sustainability_metrics' => json_encode([
                    'material_recovery' => '100%',
                    'co2_reduction' => '5kg',
                    'circular_economy_impact' => 'high'
                ]),
            ],
            [
                'title' => 'Cotton Yarn Spools',
                'description' => 'End-of-lot cotton yarn spools. Various weights and colors.',
                'waste_type' => 'yarn',
                'material_type' => 'Cotton',
                'quantity' => 12.25,
                'unit' => 'kg',
                'condition' => 'Good - some color variations',
                'color' => 'Multiple colors available',
                'composition' => '85% cotton, 15% recycled cotton',
                'minimum_order_quantity' => 2.0,
                'price_per_unit' => 8.75,
                'location' => 'Warehouse C, Shelf 15',
                'availability_status' => 'available',
                'images' => 'cotton_yarn.jpg',
                'sustainability_metrics' => json_encode([
                    'water_saved' => '8500 liters',
                    'co2_reduction' => '4kg',
                    'circular_economy_impact' => 'medium'
                ]),
            ],
        ];

        $this->createWastes($profile->id, $wastes);
    }

    /**
     * Create leather wastes
     */
    private function createLeatherWastes($profile): void
    {
        $wastes = [
            [
                'title' => 'Leather Scrap Assortment',
                'description' => 'Mixed leather scraps from accessory production. Various colors and textures.',
                'waste_type' => 'scraps',
                'material_type' => 'Leather',
                'quantity' => 15.0,
                'unit' => 'kg',
                'condition' => 'Good - usable pieces',
                'color' => 'Brown, Black, Tan, Burgundy',
                'composition' => 'Genuine leather',
                'minimum_order_quantity' => 2.0,
                'price_per_unit' => 18.50,
                'location' => 'Workshop, Bin 7',
                'availability_status' => 'available',
                'images' => 'leather_scraps.jpg',
                'sustainability_metrics' => json_encode([
                    'material_recovery' => '85%',
                    'landfill_diversion' => '15kg',
                    'circular_economy_impact' => 'high'
                ]),
            ],
            [
                'title' => 'Suede Offcuts Collection',
                'description' => 'High-quality suede offcuts from luxury bag production. Soft texture, various sizes.',
                'waste_type' => 'offcuts',
                'material_type' => 'Suede',
                'quantity' => 8.75,
                'unit' => 'kg',
                'condition' => 'Excellent - premium material',
                'color' => 'Navy, Gray, Camel',
                'composition' => '100% suede leather',
                'minimum_order_quantity' => 1.0,
                'price_per_unit' => 25.00,
                'location' => 'Production Room 3',
                'availability_status' => 'available',
                'images' => 'suede_offcuts.jpg',
                'sustainability_metrics' => json_encode([
                    'material_recovery' => '95%',
                    'waste_reduction' => '8.75kg',
                    'circular_economy_impact' => 'high'
                ]),
            ],
        ];

        $this->createWastes($profile->id, $wastes);
    }

    /**
     * Create generic wastes for all companies
     */
    private function createGenericWastes($profile): void
    {
        $wastes = [
            [
                'title' => 'Mixed Fabric Scraps',
                'description' => 'Assorted fabric scraps and offcuts from various production lines. Mixed materials and colors.',
                'waste_type' => 'scraps',
                'material_type' => 'Mixed Fabrics',
                'quantity' => 35.0,
                'unit' => 'kg',
                'condition' => 'Variable - mostly good',
                'color' => 'Multiple colors',
                'composition' => 'Mixed materials (cotton, polyester, blends)',
                'minimum_order_quantity' => 5.0,
                'price_per_unit' => 2.00,
                'location' => 'Central Storage',
                'availability_status' => 'available',
                'images' => 'mixed_scraps.jpg',
                'sustainability_metrics' => json_encode([
                    'landfill_diversion' => '35kg',
                    'co2_reduction' => '12kg',
                    'circular_economy_impact' => 'medium'
                ]),
            ],
            [
                'title' => 'Upholstery Fabric Remnants',
                'description' => 'Heavy-duty upholstery fabric remnants. Durable material suitable for furniture projects.',
                'waste_type' => 'offcuts',
                'material_type' => 'Upholstery Fabric',
                'quantity' => 45.5,
                'unit' => 'meters',
                'condition' => 'Good - mostly larger pieces',
                'color' => 'Neutrals and patterns',
                'composition' => 'Various (cotton, polyester, wool blends)',
                'minimum_order_quantity' => 3.0,
                'price_per_unit' => 7.50,
                'location' => 'Warehouse D, Section 8',
                'availability_status' => 'available',
                'images' => 'upholstery_remnants.jpg',
                'sustainability_metrics' => json_encode([
                    'material_recovery' => '80%',
                    'waste_reduction' => '45.5 meters',
                    'circular_economy_impact' => 'high'
                ]),
            ],
        ];

        $this->createWastes($profile->id, $wastes);
    }

    /**
     * Create textile wastes in the database
     */
    private function createWastes($companyProfileId, $wastesData): void
    {
        foreach ($wastesData as $wasteData) {
            TextileWaste::create([
                'company_profiles_id' => $companyProfileId,
                'title' => $wasteData['title'],
                'description' => $wasteData['description'],
                'waste_type' => $wasteData['waste_type'],
                'material_type' => $wasteData['material_type'],
                'quantity' => $wasteData['quantity'],
                'unit' => $wasteData['unit'],
                'condition' => $wasteData['condition'],
                'color' => $wasteData['color'],
                'composition' => $wasteData['composition'],
                'minimum_order_quantity' => $wasteData['minimum_order_quantity'],
                'price_per_unit' => $wasteData['price_per_unit'],
                'location' => $wasteData['location'],
                'availability_status' => $wasteData['availability_status'],
                'images' => $wasteData['images'],
                'sustainability_metrics' => $wasteData['sustainability_metrics'],
            ]);
        }
    }
}