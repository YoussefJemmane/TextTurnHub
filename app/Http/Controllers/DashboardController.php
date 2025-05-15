<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TextileWaste;
use App\Models\WasteExchange;
use App\Charts\TextileWasteByType;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\Product;
use App\Models\CompanyProfile;
use App\Models\ArtisanProfile;
use App\Models\Company;
use App\Models\Artisan;


use Carbon\Carbon;
class DashboardController extends Controller
{
    
    public function index()
    {
        if (auth()->user()->hasRole('company')) {

            return $this->companyDashboard();
        }
        if (auth()->user()->hasRole('artisan')) {
            return $this->artisanDashboard();
        }

        return view('dashboard');
    }

    protected function companyDashboard()
{
    $user = auth()->user();
        $companyProfile = $user->companyProfile;

        // Basic company profile information
        $companyInfo = $companyProfile;

        // Textile waste listings data
        $activeListings = TextileWaste::where('company_profiles_id', $companyProfile->id)
                                    ->where('availability_status', 'available')
                                    ->get();
        $recentListings = TextileWaste::where('company_profiles_id', $companyProfile->id)
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

        // Waste exchange activities
        $incomingRequests = WasteExchange::where('supplier_company_id', $companyProfile->id)
                                        ->where('status', 'requested')
                                        ->with('textileWaste', 'receiverCompany')
                                        ->get();
        $outgoingRequests = WasteExchange::where('receiver_company_id', $companyProfile->id)
                                        ->where('status', 'requested')
                                        ->with('textileWaste', 'supplierCompany')
                                        ->get();
        $completedExchanges = WasteExchange::where(function($query) use ($companyProfile) {
                                        $query->where('supplier_company_id', $companyProfile->id)
                                              ->orWhere('receiver_company_id', $companyProfile->id);
                                        })
                                        ->where('status', 'completed')
                                        ->get();

        // Transaction summary
        $totalExchanged = WasteExchange::where('supplier_company_id', $companyProfile->id)
                                      ->where('status', 'completed')
                                      ->sum('quantity');
        $financialSummary = WasteExchange::where('supplier_company_id', $companyProfile->id)
                                        ->where('status', 'completed')
                                        ->sum('final_price');

        // Charts preparation
        $charts = $this->prepareCompanyCharts($companyProfile);

        return view('dashboard', compact(
            'companyInfo',
            'activeListings',
            'recentListings',
            'incomingRequests',
            'outgoingRequests',
            'completedExchanges',
            'totalExchanged',
            'financialSummary',
            'charts'
        ));
    }

    private function prepareCompanyCharts($companyProfile)
    {
        $charts = [];

        // 1. Textile Waste by Type Chart
        $wasteTypeChart = new TextileWasteByType;
        $wasteTypes = TextileWaste::where('company_profiles_id', $companyProfile->id)
                                 ->selectRaw('waste_type, count(*) as count')
                                 ->groupBy('waste_type')
                                 ->get();

        $wasteTypeChart->labels($wasteTypes->pluck('waste_type')->toArray());
        $wasteTypeChart->dataset('Waste by Type', 'pie', $wasteTypes->pluck('count')->toArray())
                      ->backgroundColor(collect(['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'])->random($wasteTypes->count()));
        $wasteTypeChart->displayLegend(true);
        $wasteTypeChart->title('Textile Waste Distribution by Type');
        $charts['wasteTypeChart'] = $wasteTypeChart;

       // 2. Exchange Status Chart
$exchangeStatusChart = new Chart;
$exchangeStats = WasteExchange::where('supplier_company_id', $companyProfile->id) // Only where company is supplier
                           ->selectRaw('status, count(*) as count')
                           ->groupBy('status')
                           ->get();

$exchangeStatusChart->labels($exchangeStats->pluck('status')->toArray());
$exchangeStatusChart->dataset('Exchange Status', 'doughnut', $exchangeStats->pluck('count')->toArray())
                   ->backgroundColor(['#36A2EB', '#FF6384', '#4BC0C0', '#FFCE56']);
$exchangeStatusChart->title('Your Waste Exchange Status Distribution');
$charts['exchangeStatusChart'] = $exchangeStatusChart;

        // 3. Monthly Transactions Chart (Last 6 months)
        $monthlyChart = new Chart;
        $lastSixMonths = collect();
        for ($i = 5; $i >= 0; $i--) {
            $lastSixMonths->push(Carbon::now()->subMonths($i)->format('M Y'));
        }

        $monthlyData = collect();
        foreach ($lastSixMonths as $month) {
            $startDate = Carbon::parse($month)->startOfMonth();
            $endDate = Carbon::parse($month)->endOfMonth();

            $count = WasteExchange::where('supplier_company_id', $companyProfile->id)
                                 ->where('status', 'completed')
                                 ->whereBetween('exchange_date', [$startDate, $endDate])
                                 ->count();

            $monthlyData->push($count);
        }

        $monthlyChart->labels($lastSixMonths->toArray());
        $monthlyChart->dataset('Completed Exchanges', 'line', $monthlyData->toArray())
                    ->backgroundColor('rgba(54, 162, 235, 0.2)')

                    ->lineTension(0.2);
        $monthlyChart->title('Monthly Exchange Transactions');
        $charts['monthlyChart'] = $monthlyChart;

        // 4. Material Type Distribution Chart
        $materialTypeChart = new Chart;
        $materialTypes = TextileWaste::where('company_profiles_id', $companyProfile->id)
                                    ->selectRaw('material_type, count(*) as count')
                                    ->groupBy('material_type')
                                    ->get();

        $materialTypeChart->labels($materialTypes->pluck('material_type')->toArray());
        $materialTypeChart->dataset('Distribution by Material', 'polarArea', $materialTypes->pluck('count')->toArray())
                         ->backgroundColor(['#FF6384', '#4BC0C0', '#FFCE56', '#9966FF', '#36A2EB', '#FF9F40']);
        $materialTypeChart->title('Material Type Distribution');
        $charts['materialTypeChart'] = $materialTypeChart;

        return $charts;
    }

        
    
       
    protected function artisanDashboard()
    {
        $user = auth()->user();
        $artisanProfile = $user->artisanProfile;
    
        // Basic artisan profile information
        $artisanInfo = $artisanProfile;
    
        // Calculate product statistics
        $productStats = [
            'total' => Product::where('artisan_profile_id', $artisanProfile->id)->count(),
            'sales' => Product::where('artisan_profile_id', $artisanProfile->id)
                             ->selectRaw('SUM(price * sales_count) as total')
                             ->first()
                             ->total ?? 0
        ];
    
        // Calculate exchange statistics
        $exchangeStats = [
            'active' => WasteExchange::where('receiver_artisan_id', $artisanProfile->id)
                                    ->whereIn('status', ['accepted', 'requested'])
                                    ->count(),
            'materials' => WasteExchange::where('receiver_artisan_id', $artisanProfile->id)
                                       ->where('status', 'completed')
                                       ->count()
        ];
    
        // Get products for the table
        $products = Product::where('artisan_profile_id', $artisanProfile->id)
                          ->latest()
                          ->take(10)
                          ->get();
    
        // Get waste exchanges for the table
        $wasteExchanges = WasteExchange::where('receiver_artisan_id', $artisanProfile->id)
                                      ->with(['textileWaste', 'supplierCompany'])
                                      ->latest()
                                      ->take(10)
                                      ->get();
    
        // Prepare charts
        $charts = [
            'salesChart' => $this->createSalesChart($artisanProfile),
            'productPerformanceChart' => $this->createProductPerformanceChart($artisanProfile)
        ];
    
        return view('dashboard.artisan', compact(
            'artisanInfo',
            'productStats',
            'exchangeStats',
            'products',
            'wasteExchanges',
            'charts'
        ));
    }
    
    // Add these helper methods for charts
    private function createSalesChart($artisanProfile)
    {
        $chart = new Chart;
        $lastSixMonths = collect();
        for ($i = 5; $i >= 0; $i--) {
            $lastSixMonths->push(Carbon::now()->subMonths($i)->format('M Y'));
        }
    
        $salesData = collect();
        foreach ($lastSixMonths as $month) {
            $startDate = Carbon::parse($month)->startOfMonth();
            $endDate = Carbon::parse($month)->endOfMonth();
    
            $monthlySales = Product::where('artisan_profile_id', $artisanProfile->id)
                                  ->whereBetween('created_at', [$startDate, $endDate])
                                  ->sum('sales_count');
    
            $salesData->push($monthlySales);
        }
    
        $chart->labels($lastSixMonths->toArray());
        $chart->dataset('Monthly Sales', 'line', $salesData->toArray())
              ->backgroundColor('rgba(54, 162, 235, 0.2)')
              ->lineTension(0.2);
    
        return $chart;
    }
    
    private function createProductPerformanceChart($artisanProfile)
    {
        $chart = new Chart;
        
        $topProducts = Product::where('artisan_profile_id', $artisanProfile->id)
                             ->orderBy('sales_count', 'desc')
                             ->take(5)
                             ->get();
    
        $chart->labels($topProducts->pluck('name')->toArray());
        $chart->dataset('Sales Count', 'bar', $topProducts->pluck('sales_count')->toArray())
              ->backgroundColor('rgba(54, 162, 235, 0.2)');
              
    
        return $chart;
    }
    
    private function prepareArtisanCharts($artisanProfile)
    {
        $charts = [];
    
        // 1. Monthly Sales Chart
        $salesChart = new Chart;
        $lastSixMonths = collect();
        for ($i = 5; $i >= 0; $i--) {
            $lastSixMonths->push(Carbon::now()->subMonths($i)->format('M Y'));
        }
    
        $salesData = collect();
        $revenueData = collect();
        foreach ($lastSixMonths as $month) {
            $startDate = Carbon::parse($month)->startOfMonth();
            $endDate = Carbon::parse($month)->endOfMonth();
    
            $monthlySales = Product::where('artisan_profile_id', $artisanProfile->id)
                                  ->whereBetween('created_at', [$startDate, $endDate])
                                  ->sum('sales_count');
    
            $monthlyRevenue = Product::where('artisan_profile_id', $artisanProfile->id)
                                    ->whereBetween('created_at', [$startDate, $endDate])
                                    ->selectRaw('SUM(price * sales_count) as total')
                                    ->first()
                                    ->total ?? 0;
    
            $salesData->push($monthlySales);
            $revenueData->push($monthlyRevenue);
        }
    
        $salesChart->labels($lastSixMonths->toArray());
        $salesChart->dataset('Sales Count', 'line', $salesData->toArray())
                  ->backgroundColor('rgba(54, 162, 235, 0.2)')
                  ->lineTension(0.2);
        $salesChart->dataset('Revenue (MAD)', 'line', $revenueData->toArray())
                  ->backgroundColor('rgba(75, 192, 192, 0.2)')
                  ->lineTension(0.2);
        $salesChart->title('Monthly Sales Performance');
        $charts['salesChart'] = $salesChart;
    
        // 2. Product Category Distribution Chart
        $categoryChart = new Chart;
        $categories = Product::where('artisan_profile_id', $artisanProfile->id)
                            ->selectRaw('category, count(*) as count')
                            ->groupBy('category')
                            ->get();
    
        $categoryChart->labels($categories->pluck('category')->toArray());
        $categoryChart->dataset('Products by Category', 'doughnut', $categories->pluck('count')->toArray())
                     ->backgroundColor(['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']);
        $categoryChart->title('Product Category Distribution');
        $charts['categoryChart'] = $categoryChart;
    
        // 3. Materials Usage Chart
        $materialsChart = new Chart;
        $materials = Product::where('artisan_profile_id', $artisanProfile->id)
                           ->selectRaw('material, count(*) as count')
                           ->groupBy('material')
                           ->get();
    
        $materialsChart->labels($materials->pluck('material')->toArray());
        $materialsChart->dataset('Products by Material', 'polarArea', $materials->pluck('count')->toArray())
                      ->backgroundColor(['#FF6384', '#4BC0C0', '#FFCE56', '#9966FF', '#36A2EB', '#FF9F40']);
        $materialsChart->title('Materials Usage Distribution');
        $charts['materialsChart'] = $materialsChart;
    
        // 4. Top Products Performance Chart
        $performanceChart = new Chart;
        $topProducts = Product::where('artisan_profile_id', $artisanProfile->id)
                             ->orderBy('sales_count', 'desc')
                             ->take(5)
                             ->get();
    
        $performanceChart->labels($topProducts->pluck('name')->toArray());
        $performanceChart->dataset('Sales Count', 'bar', $topProducts->pluck('sales_count')->toArray())
                        ->backgroundColor('rgba(54, 162, 235, 0.2)');
                        
        $performanceChart->title('Top 5 Products Performance');
        $charts['performanceChart'] = $performanceChart;
    
        return $charts;
    }

    

}
