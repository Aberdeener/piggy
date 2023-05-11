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
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function (value) {
                                return '$' + (value / 100).toFixed(2);
                            }
                        },
                    },
                },
                ...options,
            }}
            series={series}
            type="line"
            width="500"
        />
    )
}
