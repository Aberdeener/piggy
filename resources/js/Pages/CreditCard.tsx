import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import {
    Account as AccountType,
    AccountBalanceHistory,
    CreditCard,
    CreditCardBalanceHistory,
    NetWorth,
    PageProps
} from '@/types';
import React from "react";
import LineChart from "@/Components/LineChart";

export default function Account({ auth, creditCard, creditCardBalanceHistory }: PageProps<{ creditCard: CreditCard, creditCardBalanceHistory: CreditCardBalanceHistory[] }>) {

    const apexCategories = creditCardBalanceHistory.map((history) => history.date);
    const apexSeries = [{
        name: 'Balance',
        data: creditCardBalanceHistory.map((history) => history.balance.amount),
    }];

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Credit Cards</h2>}
        >
            <Head title="Credit Cards" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <div className="ml-4 text-lg leading-7 font-semibold">
                                {JSON.stringify(creditCard)}
                                <LineChart categories={apexCategories} series={apexSeries} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
