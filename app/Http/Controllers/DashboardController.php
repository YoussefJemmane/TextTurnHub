<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TextileWaste;
use App\Models\WasteExchange;
use App\Charts\TextileWasteByType;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;

use Carbon\Carbon;
class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('company')) {

            return $this->companyDashboard();
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
        $charts = $this->prepareCharts($companyProfile);

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

    private function prepareCharts($companyProfile)
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


}
