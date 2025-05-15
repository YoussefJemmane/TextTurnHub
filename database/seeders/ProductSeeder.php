<?php

namespace Database\Seeders;

use App\Models\ArtisanProfile;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get artisan profiles
        $artisanProfiles = ArtisanProfile::all();

        // Seed products for each artisan profile
        foreach ($artisanProfiles as $profile) {
            // Create different products based on artisan specialty
            switch ($profile->artisan_specialty) {
                case 'Textile Upcycling':
                    $this->createTextileProducts($profile);
                    break;
                case 'Metal Sculpture':
                    $this->createMetalProducts($profile);
                    break;
                case 'Paper Crafts':
                    $this->createPaperProducts($profile);
                    break;
                default:
                    // Create generic products
                    $this->createGenericProducts($profile);
            }
        }
    }

    /**
     * Create textile-related products
     */
    private function createTextileProducts($profile): void
    {
        $products = [
            [
                'name' => 'Upcycled Denim Tote Bag',
                'description' => 'Handcrafted tote bag made from recycled denim jeans. Each bag is unique with different pocket designs and fabric patterns.',
                'category' => 'Bags',
                'price' => 45.99,
                'stock' => 15,
                'unit' => 'piece',
                'color' => 'Blue',
                'material' => 'Recycled denim',
                'image' => 'denim_tote.jpg',
                'sales_count' => 0,
                'is_featured' => false,
            ],
            [
                'name' => 'Patchwork Throw Pillow Cover',
                'description' => 'Colorful patchwork pillow cover made from fabric scraps and remnants. Zipper closure for easy removal.',
                'category' => 'Home Decor',
                'price' => 28.50,
                'stock' => 20,
                'unit' => 'piece',
                'color' => 'Multicolor',
                'material' => 'Cotton fabric scraps',
                'image' => 'patchwork_pillow.jpg',
                'sales_count' => 0,
                'is_featured' => false,
            ],
            [
                'name' => 'Recycled Fabric Coasters (Set of 4)',
                'description' => 'Set of 4 coasters made from upcycled fabric. Padded and quilted for absorbency and heat protection.',
                'category' => 'Kitchen & Dining',
                'price' => 18.99,
                'stock' => 25,
                'unit' => 'set',
                'color' => 'Assorted',
                'material' => 'Mixed fabrics, batting',
                'image' => 'fabric_coasters.jpg',
                'sales_count' => 0,
                'is_featured' => false,
            ],
            [
                'name' => 'Upcycled Sweater Mittens',
                'description' => 'Warm mittens made from recycled wool sweaters. Lined with soft fleece for extra comfort and warmth.',
                'category' => 'Accessories',
                'price' => 24.00,
                'stock' => 12,
                'unit' => 'pair',
                'color' => 'Various',
                'material' => 'Recycled wool, fleece lining',
                'image' => 'sweater_mittens.jpg',
                'sales_count' => 0,
                'is_featured' => false,
            ],
        ];

        $this->createProducts($profile->id, $products);
    }

    /**
     * Create metal-related products
     */
    private function createMetalProducts($profile): void
    {
        $products = [
            [
                'name' => 'Industrial Desk Lamp',
                'description' => 'Unique desk lamp crafted from repurposed metal pipes and fittings. Includes vintage-style Edison bulb.',
                'category' => 'Lighting',
                'price' => 89.99,
                'stock' => 8,
                'unit' => 'piece',
                'color' => 'Bronze/Copper',
                'material' => 'Repurposed metal pipes, electrical components',
                'image' => 'industrial_lamp.jpg',
                'sales_count' => 0,
                'is_featured' => false,
            ],
            [
                'name' => 'Metal Garden Sculpture',
                'description' => 'Abstract garden sculpture made from welded scrap metal. Weather-resistant finish for outdoor use.',
                'category' => 'Garden & Outdoor',
                'price' => 175.00,
                'stock' => 5,
                'unit' => 'piece',
                'color' => 'Rustic',
                'material' => 'Assorted scrap metal, steel',
                'image' => 'garden_sculpture.jpg',
                 'sales_count' => 0,
                'is_featured' => false,
            ],
            [
                'name' => 'Upcycled Metal Wall Art',
                'description' => 'Contemporary wall art piece created from reclaimed industrial metal parts. Each piece is unique.',
                'category' => 'Wall Art',
                'price' => 120.00,
                'stock' => 7,
                'unit' => 'piece',
                'color' => 'Mixed metals',
                'material' => 'Reclaimed industrial metal, repurposed hardware',
                'image' => 'metal_wall_art.jpg',
                 'sales_count' => 0,
                'is_featured' => false,
            ],
            [
                'name' => 'Recycled Metal Bookends',
                'description' => 'Heavy-duty bookends made from salvaged metal pieces. Perfect for organizing your bookshelf with industrial style.',
                'category' => 'Office & Study',
                'price' => 42.50,
                'stock' => 15,
                'unit' => 'pair',
                'color' => 'Industrial gray',
                'material' => 'Salvaged metal, steel',
                'image' => 'metal_bookends.jpg',
                 'sales_count' => 0,
                'is_featured' => false,
            ],
        ];

        $this->createProducts($profile->id, $products);
    }

    /**
     * Create paper-related products
     */
    private function createPaperProducts($profile): void
    {
        $products = [
            [
                'name' => 'Handmade Recycled Paper Journal',
                'description' => 'Beautifully crafted journal with 100 pages of handmade recycled paper. Features a decorative cover made from upcycled materials.',
                'category' => 'Stationery',
                'price' => 32.99,
                'stock' => 25,
                'unit' => 'piece',
                'color' => 'Natural',
                'material' => 'Recycled paper, cardboard',
                'image' => 'recycled_journal.jpg',
                 'sales_count' => 0,
                'is_featured' => false,
            ],
            [
                'name' => 'Paper Mosaic Wall Hanging',
                'description' => 'Colorful wall art created from tiny pieces of reclaimed paper. Each piece is sealed for longevity.',
                'category' => 'Wall Art',
                'price' => 65.00,
                'stock' => 10,
                'unit' => 'piece',
                'color' => 'Multicolor',
                'material' => 'Reclaimed paper, cardstock',
                'image' => 'paper_mosaic.jpg',
                 'sales_count' => 0,
                'is_featured' => false,
            ],
            [
                'name' => 'Eco-Friendly Gift Box Set',
                'description' => 'Set of 5 decorative gift boxes in various sizes, handcrafted from recycled cardboard and paper. Perfect for sustainable gift-giving.',
                'category' => 'Gift Packaging',
                'price' => 22.50,
                'stock' => 30,
                'unit' => 'set',
                'color' => 'Kraft/Natural',
                'material' => 'Recycled cardboard, paper',
                'image' => 'gift_boxes.jpg',
                 'sales_count' => 0,
                'is_featured' => false,
            ],
            [
                'name' => 'Paper Quilling Greeting Cards (Pack of 3)',
                'description' => 'Handmade greeting cards featuring intricate paper quilling designs. Includes envelopes made from recycled paper.',
                'category' => 'Stationery',
                'price' => 18.99,
                'stock' => 25,
                'unit' => 'pack',
                'color' => 'Various',
                'material' => 'Recycled paper strips, cardstock',
                'image' => 'quilling_cards.jpg',
                 'sales_count' => 0,
                'is_featured' => false,
            ],
        ];

        $this->createProducts($profile->id, $products);
    }

    /**
     * Create generic products for any specialty
     */
    private function createGenericProducts($profile): void
    {
        $products = [
            [
                'name' => 'Upcycled Decorative Bowl',
                'description' => 'Handcrafted decorative bowl made from repurposed materials. Perfect as a centerpiece or for holding small items.',
                'category' => 'Home Decor',
                'price' => 38.99,
                'stock' => 12,
                'unit' => 'piece',
                'color' => 'Mixed',
                'material' => 'Various recycled materials',
                'image' => 'decorative_bowl.jpg',
                 'sales_count' => 0,
                'is_featured' => false,
            ],
            [
                'name' => 'Eco-Friendly Plant Hanger',
                'description' => 'Handmade macramé plant hanger created from reclaimed materials. Adds a sustainable touch to your indoor plants.',
                'category' => 'Home & Garden',
                'price' => 29.50,
                'stock' => 18,
                'unit' => 'piece',
                'color' => 'Natural',
                'material' => 'Reclaimed cotton rope',
                'image' => 'plant_hanger.jpg',
                 'sales_count' => 0,
                'is_featured' => false,
            ],
            [
                'name' => 'Upcycled Jewelry Set',
                'description' => 'Unique jewelry set featuring necklace and earrings made from recycled materials. Eco-friendly and stylish.',
                'category' => 'Jewelry',
                'price' => 45.00,
                'stock' => 10,
                'unit' => 'set',
                'color' => 'Mixed',
                'material' => 'Recycled metals, glass beads',
                'image' => 'jewelry_set.jpg',
                 'sales_count' => 0,
                'is_featured' => false,
            ],
        ];

        $this->createProducts($profile->id, $products);
    }

    /**
     * Create products in the database
     */
    private function createProducts($artisanProfileId, $productsData): void
    {
        foreach ($productsData as $productData) {
            Product::create([
                'artisan_profile_id' => $artisanProfileId,
                'name' => $productData['name'],
                'description' => $productData['description'],
                'category' => $productData['category'],
                'price' => $productData['price'],
                'stock' => $productData['stock'],
                'unit' => $productData['unit'],
                'color' => $productData['color'],
                'material' => $productData['material'],
                'image' => $productData['image'],
                'sales_count' => $productData['sales_count'],
                'is_featured' => $productData['is_featured'],
            ]);
        }
    }
}