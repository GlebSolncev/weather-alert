<div>
    @foreach($charts as $inx => $data)
        <p class="mt-4 text-gray-500 text-sm leading-relaxed">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">{{ $data['title'] }}</h3>
                </div>

                <div wire:ignore class="h-64" style="display: flex; align-items: center">
                    <div id="chart-last-{{$inx}}" style="flex:1"></div>
                    <div id="chart-data-{{$inx}}" style="flex:3"></div>
                </div>
            </div>
        </p>

    @endforeach

    <p class="mt-4 text-sm">
        <a href="https://apexcharts.com/"
           class="inline-flex items-center font-semibold text-indigo-700">
            Apex for chart :D
        </a>
    </p>

    @push('scripts')
        <script>

            let chart;

            Livewire.on('initChartData', (data) => {
                chart = data[0].charts;

                chart.forEach((item, inx) => {
                    drawChartInfo(inx, item.series, item.categories);
                    drawChartFound(inx, item.series);
                });
            })


            function drawChartInfo(inx, series, categories) {
                var chart = new ApexCharts(document.querySelector("#chart-data-" + inx), {
                    series: series,
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '35%',
                            borderRadius: 5,
                            borderRadiusApplication: 'end'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: categories
                    },
                    fill: {
                        opacity: 1,
                        colors: ['#6875f5', '#3cef51']
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val
                            }
                        }
                    }
                });

                chart.render();
            }

            function drawChartFound(inx, series){
                seriesValues = []
                series.map((item) => {
                    if(item.name === 'UV Index') {
                        seriesValues[0] = item.data[item.data.length - 1];
                    }
                    if(item.name === 'Precipitation') {
                        seriesValues[1] = item.data[item.data.length - 1];
                    }
                })

                var options = {
                    series: seriesValues,
                    chart: {
                        height: 390,
                        type: "radialBar",
                    },
                    plotOptions: {
                        radialBar: {
                            offsetY: 0,
                            startAngle: 0,
                            endAngle: 270,
                            track: {
                                background: "#FFFFFF",
                                strokeWidth: "100%",
                            },
                            dataLabels: {
                                name: {
                                    show: false,
                                },
                                value: {
                                    offsetY: 5,
                                    fontSize: "24px",
                                    fontWeight: "bold",
                                    color: "#333",
                                    formatter: function (val) {
                                        return val;
                                    },
                                },
                            },
                            barLabels: {
                                enabled: true,
                                useSeriesColors: true,
                                offsetX: -8,
                                fontSize: '16px',
                                formatter: function(seriesName, opts) {
                                    return seriesName + ":" + opts.w.globals.series[opts.seriesIndex].toFixed(2)
                                },
                            },
                        },
                    },
                    labels: ['UV Indev', 'Precipitation'],
                    colors: ["#6875f5", "#3cef51"],
                };

                var chart = new ApexCharts(document.querySelector("#chart-last-" + inx), options);
                chart.render();
            }
        </script>
    @endpush
</div>