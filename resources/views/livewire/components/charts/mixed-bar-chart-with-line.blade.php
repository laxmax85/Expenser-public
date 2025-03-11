<?php

use Livewire\Volt\Component;

new class extends Component {
    public $id;
    public $data;

    public function mount($id = 'test', $data)
    {
        $this->id = $id;
        $this->data = $data;
    }
};

?>

<div>
    <div>
        <div class="w-full h-full dark text-black" id="{{ $id }}"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const options = {
                colors: ["#9333ea", "#b491c8"],
                series: [{
                        name: "Spending",
                        type: "column",
                        color: "#9333ea",
                        data: {!! json_encode($data['Spending']['data']) !!},  
                    },
                    {
                        name: "Budget",
                        type: "line",
                        color: "#b491c8",
                        data: {!! json_encode($data['Budget']['data']) !!},  
                    }
                ],
                chart: {
                    height: 200,
                    type: "line",
                    fontFamily: "Inter, sans-serif",
                    toolbar: {
                        show: false,
                    },
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: "50%",
                        borderRadiusApplication: "end",
                        borderRadius: 8,
                    },
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    style: {
                        fontFamily: "Inter, sans-serif",
                    },
                    theme: 'dark',  
                    y: {
                        formatter: function (value) {
                            const formatter = new Intl.NumberFormat('de-DE', {
                                style: 'currency',
                                currency: 'EUR',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            });
                            return formatter.format(value); 
                        }
                    }
                },
                states: {
                    hover: {
                        filter: {
                            type: "darken",
                            value: 1,
                        },
                    },
                },
                stroke: {
                    show: true,
                    width: [0, 4],
                    curve: 'smooth',
                    colors: ["transparent", "#34D399"],
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        opacityFrom: 0.55,
                        opacityTo: 0,
                        shade: "#1C64F2",
                        gradientToColors: ["#1C64F2"],
                    },
                },
                grid: {
                    show: false,
                    strokeDashArray: 4,
                    padding: {
                        left: 2,
                        right: 2,
                        top: -14
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                legend: {
                    show: false,
                },
                xaxis: {
                    floating: false,
                    labels: {
                        show: true,
                        style: {
                            fontFamily: "Inter, sans-serif",
                            cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                        }
                    },
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                },
                yaxis: {
                    show: true,
                    labels: {
                        style: {
                            fontFamily: "Inter, sans-serif",
                            cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                        },
                        formatter: function (value) {
                            const formatter = new Intl.NumberFormat('de-DE', {
                                style: 'currency',
                                currency: 'EUR',
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                            });
                            return formatter.format(value);  
                        }
                    }
                },
                fill: {
                    opacity: 1,
                },
            }

            if (document.getElementById('{{ $id }}') && typeof ApexCharts !== 'undefined') {
                const chart = new ApexCharts(document.getElementById('{{ $id }}'), options);
                chart.render();
            }

        });
    </script>
</div>
