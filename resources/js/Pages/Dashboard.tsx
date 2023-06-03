import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, router} from '@inertiajs/react';
import {Account, CreditCard, Goal, NetWorth, PageProps} from '@/types';
import AccountCard from "@/Components/AccountCard";
import React, {useState} from "react";
import Modal from "@/Components/Modal";
import CreditCardCard from "@/Components/CreditCardCard";
import MoneyDisplay from "@/Components/MoneyDisplay";
import GoalCard from "@/Components/GoalCard";
import BalanceLineChart from "@/Components/BalanceLineChart";
import {
    IconCreditCard,
    IconMoneybag, IconPencil,
    IconProgress,
} from "@tabler/icons-react";
import PrimaryButton from "@/Components/PrimaryButton";
import CreateAccountModal from "@/Pages/Accounts/CreateAccountModal";
import CreateGoalModal from "@/Pages/Goals/CreateGoalModal";
import SecondaryButton from "@/Components/SecondaryButton";
import CreateCreditCardModel from "@/Pages/CreditCards/CreateCreditCardModal";

export default function Dashboard({ auth, netWorth, accounts, creditCards, goals }: PageProps<{ netWorth: NetWorth, accounts: Account[], creditCards: CreditCard[], goals: Goal[] }>) {
    const [showNetWorthCalculation, setShowNetWorthCalculation] = useState(false);

    const apexCategories = netWorth.history.map(a => a.date);
    const apexSeries = [{
        name: 'Net Worth',
        data: netWorth.history.map(a => a.amount.amount),
    }];

    const calculateGridCols = (count: number) => {
        if (count % 4 === 0) {
            return 'grid-cols-4';
        }

        if (count % 2 === 0) {
            return 'grid-cols-2';
        }

        if (count === 1) {
            return 'grid-cols-1';
        }

        return 'grid-cols-3';
    }

    const [showCreateAccountModal, setShowCreateAccountModal] = useState(false);
    const [showCreateCreditCardModal, setShowCreateCreditCardModal] = useState(false);
    const [showCreateGoalModal, setShowCreateGoalModal] = useState(false);

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
                            <div className="ml-4 text-lg leading-7 font-semibold">
                                Net Worth: <MoneyDisplay money={netWorth.current} className="hover:underline cursor-help" onClick={() => setShowNetWorthCalculation(true)} />
                                <Modal show={showNetWorthCalculation} onClose={() => setShowNetWorthCalculation(false)}>
                                    <table className="w-full text-sm text-left text-gray-500">
                                        <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr>
                                                <th scope="col" className="px-6 py-3">Account</th>
                                                <th scope="col" className="px-6 py-3">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {accounts.length > 0
                                                ? accounts.map(a => {
                                                    return (
                                                        <tr className="bg-white border-b" key={a.id}>
                                                            <td className="px-6 py-4">
                                                                <Link href={route('accounts.show', a.id)}>{a.name}</Link>
                                                            </td>
                                                            <td className="px-6 py-4"><MoneyDisplay money={a.balance}/></td>
                                                        </tr>
                                                    )
                                                })
                                                : (
                                                    <tr className="bg-white border-b">
                                                        <td className="px-6 py-4" colSpan={2}>No accounts yet</td>
                                                    </tr>
                                                )
                                            }
                                            <tr className="text-xs text-gray-700 uppercase bg-gray-50">
                                                <th scope="col" className="px-6 py-3">Credit Card</th>
                                                <th scope="col" className="px-6 py-3">Balance</th>
                                            </tr>
                                            {creditCards.length > 0 ?
                                                creditCards.map(c => {
                                                    return (
                                                        <tr className="bg-white border-b" key={c.id}>
                                                            <td className="px-6 py-4">
                                                                <Link href={route('credit-cards.show', c.id)}>{c.name}</Link>
                                                            </td>
                                                            <td className="px-6 py-4"><MoneyDisplay money={c.balance} creditCard /></td>
                                                        </tr>
                                                    )
                                                })
                                                : (
                                                    <tr className="bg-white border-b">
                                                        <td className="px-6 py-4" colSpan={2}>No credit cards yet</td>
                                                    </tr>
                                                )
                                            }
                                        </tbody>
                                    </table>
                                </Modal>
                            </div>
                            <BalanceLineChart categories={apexCategories} series={apexSeries} />
                        </div>
                        <div className="p-6 border-b border-gray-200">
                            <div className="flex justify-between items-center mb-4">
                                <h2 className="font-semibold text-2xl text-gray-900 inline-flex">
                                    <IconMoneybag className="w-8 h-8 mr-2" /> Accounts
                                </h2>
                                <PrimaryButton onClick={() => setShowCreateAccountModal(true)}>Create</PrimaryButton>
                                <CreateAccountModal show={showCreateAccountModal} onClose={() => setShowCreateAccountModal(false)} />
                            </div>
                            <div className={`grid gap-4 ${calculateGridCols(accounts.length)}`}>
                                {accounts.length > 0
                                    ? accounts.map(a => <AccountCard key={a.id} account={a} />)
                                    : <p className="text-gray-500">No accounts yet</p>
                                }
                            </div>
                        </div>
                        <div className="p-6 border-b border-gray-200">
                            <div className="flex justify-between items-center mb-4">
                                <h2 className="font-semibold text-2xl text-gray-900 inline-flex">
                                    <IconCreditCard className="w-8 h-8 mr-2" /> Credit Cards
                                </h2>
                                <PrimaryButton onClick={() => setShowCreateCreditCardModal(true)}>Create</PrimaryButton>
                                <CreateCreditCardModel show={showCreateCreditCardModal} onClose={() => setShowCreateCreditCardModal(false)} />
                            </div>
                            <div className={`grid gap-4 ${calculateGridCols(creditCards.length)}`}>
                                {creditCards.length > 0
                                    ? creditCards.map(c => <CreditCardCard key={c.id} creditCard={c} />)
                                    : <p className="text-gray-500">No credit cards yet</p>
                                }
                            </div>
                        </div>
                        <div className="p-6">
                            <div className="flex justify-between items-center mb-4">
                                <h2 className="font-semibold text-2xl text-gray-900 inline-flex">
                                    <IconProgress className="w-8 h-8 mr-2" /> Goals
                                </h2>
                                <PrimaryButton onClick={() => setShowCreateGoalModal(true)}>Create</PrimaryButton>
                                <CreateGoalModal accounts={accounts} show={showCreateGoalModal} onClose={() => setShowCreateGoalModal(false)} />
                            </div>
                            <div className={`grid gap-4 ${calculateGridCols(goals.length)}`}>
                                {goals.length > 0
                                    ? goals.map(g => <GoalCard key={g.id} goal={g} />)
                                    : <p className="text-gray-500">No goals yet</p>
                                }
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
