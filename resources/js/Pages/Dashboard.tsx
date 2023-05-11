import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import {Account, CreditCard, Goal, NetWorth, PageProps} from '@/types';
import AccountCard from "@/Components/AccountCard";
import React, {useState} from "react";
import Modal from "@/Components/Modal";
import CreditCardCard from "@/Components/CreditCardCard";
import MoneyDisplay from "@/Components/MoneyDisplay";
import GoalCard from "@/Components/GoalCard";
import LineChart from "@/Components/LineChart";
export default function Dashboard({ auth, netWorth, accounts, creditCards, goals }: PageProps<{ netWorth: NetWorth, accounts: Account[], creditCards: CreditCard[], goals: Goal[] }>) {
    const [showNetWorthCalculation, setShowNetWorthCalculation] = useState(false);

    const data = netWorth.history.map(a => {
        return {
            name: a.date,
            amount: a.amount.amount,
        }
    });

    const apexCategories = netWorth.history.map(a => a.date);
    const apexSeries = [{
        name: 'Net Worth',
        data: netWorth.history.map(a => a.amount.amount),
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
                                    Net Worth <MoneyDisplay money={netWorth.current} className="hover:underline cursor-pointer" onClick={() => setShowNetWorthCalculation(true)} />
                                    <Modal show={showNetWorthCalculation} onClose={() => setShowNetWorthCalculation(false)}>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Account</th>
                                                    <th>Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {accounts.map(a => {
                                                    return (
                                                        <tr>
                                                            <td>{a.name}</td>
                                                            <td><MoneyDisplay money={a.balance}/></td>
                                                        </tr>
                                                    )
                                                })}
                                                <tr>
                                                    <th>Credit Card</th>
                                                    <th>Balance</th>
                                                </tr>
                                                {creditCards.map(c => {
                                                    return (
                                                        <tr>
                                                            <td>{c.name}</td>
                                                            <td><MoneyDisplay money={c.balance} creditCard /></td>
                                                        </tr>
                                                    )
                                                })}
                                            </tbody>
                                        </table>
                                    </Modal>

                                    <LineChart categories={apexCategories} series={apexSeries} />
                                </div>
                            </div>
                        </div>
                        <div className="p-6 grid gap-4 border-b border-gray-200 grid-cols-3">
                            {accounts.map(a => <AccountCard account={a} />)}
                        </div>
                        <div className="p-6 grid gap-4 border-b border-gray-200 grid-cols-2">
                            {creditCards.map(c => <CreditCardCard creditCard={c} />)}
                        </div>
                        <div className="p-6 grid gap-4 grid-cols-2">
                            {goals.map(g => <GoalCard goal={g} />)}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
