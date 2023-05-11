import Chart from "react-apexcharts";
import React from "react";

export default function LineChart({ categories, series, options = {} }: { categories: any[], series: { name: string, data: number[] }[], options?: any }) {
    return (
        <Chart
            options={{
                ...{
                    xaxis: {
                        categories: categories,
                        labels: {
                            formatter: function (value) {
                                const date = new Date(value);
                                return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
                            }
                        },
                        tooltip: {
                            enabled: false,
                        },
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return '$' + (value / 100).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            }
                        },
                    },
                    chart: {
                        toolbar: {
                            show: false,
                        },
                        zoom: {
                            enabled: false,
                        },
                    },
                    colors: ["#838BF1"],
                    stroke: {
                        curve: 'smooth',
                    },
                    fill: {
                        type: 'gradient',
                    },
                    dataLabels: {
                        enabled: false
                    },
                },
                ...options,
            }}
            series={series}
            type="area"
            height="350"
        />
    )
}
