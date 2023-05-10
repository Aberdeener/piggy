import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import {Account as AccountType, AccountBalanceHistory, CreditCard, NetWorth, PageProps} from '@/types';
import {LineChart, Line, XAxis, YAxis, ResponsiveContainer} from 'recharts';
import Chart from "react-apexcharts";
import React from "react";

export default function Account({ auth, account, accountBalanceHistory }: PageProps<{ account: AccountType, accountBalanceHistory: AccountBalanceHistory[] }>) {

    const data = accountBalanceHistory.map((history) => {
        return {
            name: history.date,
            amount: history.balance.amount,
        }
    });

    const apexCategories = accountBalanceHistory.map((history) => history.date);
    const apexSeries = [{
        name: 'Balance',
        data: accountBalanceHistory.map((history) => history.balance.amount),
    }];

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <div className="flex items-center">
                                <div className="ml-4 text-lg leading-7 font-semibold">
                                    {JSON.stringify(account)}
                                    <Chart
                                        options={{
                                            xaxis: {
                                                categories: apexCategories,
                                            }
                                        }}
                                        series={apexSeries}
                                        type="line"
                                        width="500"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
