import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import {Account as AccountType, BalanceHistory, PageProps} from '@/types';
import React from "react";
import BalanceLineChart from "@/Components/BalanceLineChart";
import DeleteResourceButton from "@/Components/DeleteResourceButton";
import MoneyDisplay from "@/Components/MoneyDisplay";

export default function Show({ auth, account, accountBalanceHistory }: PageProps<{ account: AccountType, accountBalanceHistory: BalanceHistory[] }>) {

    const apexCategories = accountBalanceHistory.map((history) => history.date);
    const apexSeries = [{
        name: 'Balance',
        data: accountBalanceHistory.map((history) => history.balance.amount),
    }];

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Accounts</h2>}
        >
            <Head title="Accounts" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <div className="ml-4 text-lg leading-7 font-semibold">
                                Balance: <MoneyDisplay money={account.balance} />
                                <BalanceLineChart categories={apexCategories} series={apexSeries} />
                                <DeleteResourceButton path={route('accounts.destroy', account.id)} type="account" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
