<?php

use Livewire\Volt\Component;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Collection;

new class extends Component {
    public $chartData = [];
    public $colors = ['#3c1361', '#52307c', '#663a82', '#7c5295', '#b491c8', '#bca0dc', '#E8D4FF', '#808080'];
    public function mount()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $categoryExpenses = DB::table('categories')
            ->leftJoin('expenses', function ($join) use ($startOfMonth, $endOfMonth) {
                $join->on('categories.id', '=', 'expenses.category_id')->whereBetween('expenses.expense_date', [$startOfMonth, $endOfMonth]);
            })
            ->selectRaw('categories.name, COALESCE(SUM(expenses.amount), 0) AS total_expense')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_expense')
            ->get();

        $categories = collect($categoryExpenses)->map(
            fn($category) => [
                'name' => $category->name,
                'expenses' => $category->total_expense,
            ],
        );

        $topCategories = $categories->take(7);
        $otherCategories = $categories->skip(7);


        if ($otherCategories->isNotEmpty()) {
            $topCategories->push([
                'name' => 'Others',
                'expenses' => $otherCategories->sum('expenses'),
            ]);
        }

        $this->chartData = $topCategories->toArray();
    }
};

?>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div class="w-full rounded-lg shadow-sm p-4 md:p-6 flex flex-col md:flex-row gap-6">
    <div class="w-full md:w-1/2 py-6" id="donut-chart"></div>

    <div class="w-full md:w-1/2 flex flex-col justify-center">
        <ul class="space-y-2">
            @foreach ($chartData as $category)
                <li class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $colors[$loop->index] }}">
                        </div>
                        <span class="text-white font-medium truncate" style="max-width: 150px;" title="{{ $category['name'] }}">
                            {{ Str::limit($category['name'], 20) }}
                        </span>
                    </div>

                    <div class="flex-1 border-b-2 border-gray-600 mx-4"></div>

                    <span class="text-white">
                        €{{ number_format($category['expenses'], 2, ',', '.') }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartData = @json($chartData);

        const categories = chartData.map(item => item.name);
        const series = chartData.map(item => item.expenses);

        const colors = [
            '#3c1361', 
            '#52307c', 
            '#663a82', 
            '#7c5295', 
            '#b491c8', 
            '#bca0dc',
            '#E8D4FF', 
            '#808080', 
        ];

        const getChartOptions = () => {
            return {
                series: series,
                colors: colors.slice(0, series.length),
                chart: {
                    height: 320,
                    width: "100%",
                    type: "donut",
                },
                labels: categories,
                stroke: {
                    colors: ["transparent"]
                },
                plotOptions: {
                    pie: {
                        donut: {
                            labels: {
                                show: false, 
                            },
                        },
                    },
                },

                dataLabels: {
                    enabled: false,
                },
                legend: {
                    show: false,
                },
                tooltip: {
                    enabled: true,
                    y: {
                        formatter: function(value) {
                            const formatter = new Intl.NumberFormat('de-DE', {
                                style: 'currency',
                                currency: 'EUR',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            });
                            return formatter.format(
                            value); 
                        },
                    },
                },
            };
        };

        if (document.getElementById("donut-chart") && typeof ApexCharts !== 'undefined') {
            const chart = new ApexCharts(document.getElementById("donut-chart"), getChartOptions());
            chart.render();
        }
    });
</script>
