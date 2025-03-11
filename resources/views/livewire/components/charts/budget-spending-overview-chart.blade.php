<?php

use Livewire\Volt\Component;
use Carbon\Carbon;
use App\Models\Category;
use App\Models\Expense;

new class extends Component {
    public $spendingData;
    public $totalCurrentMonthSpent;
    public $totalLastMonthSpent;
    public $totalOverspent;

    public function mount()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        $userCategories = Auth::user()->categories;

        $this->spendingData = [
            'Spending' => [
                'name' => 'Spending',
                'type' => 'column',
                'color' => '#1A56DB',
                'data' => [],
            ],
            'Budget' => [
                'name' => 'Budget',
                'type' => 'line',
                'color' => '#34D399',
                'data' => [],
            ],
        ];

        $pastSixMonthsExpenses = DB::table('expenses')
            ->selectRaw("strftime('%Y-%m', expense_date) AS month, SUM(amount) AS total_amount")
            ->where('expense_date', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total_amount', 'month');

        $months = collect(range(0, 5))->map(fn($i) => $startOfMonth->copy()->subMonths($i)->format('Y-m'))->reverse();

        $monthNames = [
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Aug',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        ];

        $totalBudget = $userCategories->sum('budget');
        $this->totalCurrentMonthSpent = $pastSixMonthsExpenses[$startOfMonth->format('Y-m')] ?? 0;
        $this->totalLastMonthSpent = $pastSixMonthsExpenses[$startOfLastMonth->format('Y-m')] ?? 0;
        $this->totalOverspent = max(0, $this->totalCurrentMonthSpent - $totalBudget);

        foreach ($months as $month) {
            $monthNum = substr($month, 5, 2); 
            $monthName = $monthNames[$monthNum]; 

            $this->spendingData['Spending']['data'][] = [
                'x' => $monthName,
                'y' => $pastSixMonthsExpenses[$month] ?? 0,
            ];

            $this->spendingData['Budget']['data'][] = [
                'x' => $monthName,
                'y' => $totalBudget,
            ];
        }
    }
}; ?>

<div>
    <h3 class="dark:text-white text-2xl">Budget and spending overview</h3>
    <p class="dark:text-gray-500 text-lg">An overview between the budget and spending across all categories
    </p>
    <hr class="my-4 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10" />
    <div class="pt-10 w-full h-full">
        @livewire('components.charts.mixed-bar-chart-with-line', ['id' => 1, 'data' => $spendingData])
    </div>
    <hr class="my-4 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10" />

    <div class="flex gap-10 pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-2">
            <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center me-3">
                <x-fluentui-money-20 class="text-gray-800 dark:text-gray-400 m-1" />
            </div>
            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <h5 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-1">
                        €{{ number_format($totalCurrentMonthSpent, 2, ',', '.') }}
                    </h5>

                    @php
                        $percentageChange =
                            $totalLastMonthSpent > 0
                                ? (($totalCurrentMonthSpent - $totalLastMonthSpent) / $totalLastMonthSpent) * 100
                                : 0;
                        $badgeClass =
                            $percentageChange > 0
                                ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300'
                                : ($percentageChange < 0
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
                                    : 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300');
                        $percentageChangeFormatted = number_format($percentageChange, 2, ',', '.');
                    @endphp
                    <span class="px-2.5 py-0.5 rounded-sm text-sm font-medium text-center {{ $badgeClass }}">
                        @if ($percentageChangeFormatted > 0)
                            +{{ $percentageChangeFormatted }}%
                        @else
                            {{ $percentageChangeFormatted }}%
                        @endif
                    </span>
                </div>
                <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Spent this month</p>
            </div>
        </div>

        <div class="border-l-2 border-gray-200 dark:border-gray-700"></div>

        <div class="flex items-center">
            <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center me-3">
                <x-fluentui-money-calculator-20 class="text-gray-800 dark:text-gray-400 m-1" />
            </div>
            <div>
                <h5 class="leading-none text-2xl font-bold text-gray-900 dark:text-white pb-1">
                    €{{ number_format($totalOverspent, 2, ',', '.') }}
                </h5>
                <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Total overspent</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
