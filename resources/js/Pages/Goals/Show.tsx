import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, router, useForm} from '@inertiajs/react';
import React, {useState} from "react";
import {PageProps, Goal as GoalType, GoalStatus, Account} from "@/types";
import SecondaryButton from "@/Components/SecondaryButton";
import {IconPencil, IconProgress} from "@tabler/icons-react";
import GoalStatusBadge from "@/Components/GoalStatusBadge";
import Modal from "@/Components/Modal";
import MoneyDisplay from "@/Components/MoneyDisplay";
import PrimaryButton from "@/Components/PrimaryButton";
import LineProgress from "@/Components/LineProgress";
import GoalAutoDepositModal from "@/Pages/Goals/GoalAutoDepositModal";
import {dateFormat, uppercaseFirst} from "@/utils";

export default function Show({ auth, goal, accounts }: PageProps<{ goal: GoalType, accounts: Account[] }>) {

    const { patch } = useForm();

    const toggleDepositEnabled = (id: number) => {
        patch(route('goal-auto-deposits.toggle', id), {
            preserveScroll: true,
        });
    }

    const [showCreateAutoDepositModal, setShowCreateAutoDepositModal] = useState(false);

    const [showDepositEditModal, setShowDepositEditModal] = useState(false);
    const [currentDepositEditModalId, setCurrentDepositEditModalId] = useState(0);
    const showDepositEditModalForId = (id: number) => {
        setCurrentDepositEditModalId(id);
        setShowDepositEditModal(true);
    }

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Goals</h2>}
        >
            <Head title="Goals" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <h2 className="font-semibold text-2xl text-gray-900 inline-flex">
                                {goal.name} <GoalStatusBadge status={goal.status} completion_percentage={goal.completion_percentage} className="ml-2" />
                            </h2>

                            <div className="grid grid-cols-3 gap-4 pt-2">
                                <div className="my-auto">
                                    <p>Target amount: {goal.target_amount.formatted}</p>
                                    <p>Target date: {dateFormat(goal.target_date)}</p>
                                    <p>Linked account: <Link href={route('accounts.show', goal.account.id)}>{goal.account.name}</Link></p>
                                </div>
                                <div className="max-w p-5 bg-white border border-gray-200 rounded-lg shadow">
                                    <h2 className="font-semibold text-xl text-gray-900 inline-flex">
                                        Current progress
                                    </h2>
                                    <p>Current amount: <MoneyDisplay money={goal.current_amount} /></p>
                                    <LineProgress status={goal.status} percentage={goal.completion_percentage} />
                                </div>
                                <div className="max-w p-5 bg-white border border-gray-200 rounded-lg shadow">
                                    <h2 className="font-semibold text-xl text-gray-900 inline-flex">
                                        Projected progress
                                    </h2>
                                    <p>Projected total: <MoneyDisplay money={goal.projected_total_by_target_date} /></p>
                                    <LineProgress status={goal.projected_status} percentage={goal.projected_completion_percentage} />
                                </div>
                            </div>

                            <div className="p-5 mt-4 bg-white border border-gray-200 rounded-lg shadow">
                                <div className="flex justify-between items-center pb-1">
                                    <h2 className="font-semibold text-xl text-gray-900 inline-flex">
                                        Auto deposits
                                    </h2>
                                    <PrimaryButton onClick={() => setShowCreateAutoDepositModal(true)}>Create</PrimaryButton>
                                    <GoalAutoDepositModal goal={goal} accounts={accounts} show={showCreateAutoDepositModal} onClose={() => setShowCreateAutoDepositModal(false)} />
                                </div>
                                <table className="table-fixed w-full text-sm text-left text-gray-500">
                                    <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" className="px-6 py-3">
                                            Withdraw Account
                                        </th>
                                        <th scope="col" className="px-6 py-3">
                                            Amount
                                        </th>
                                        <th scope="col" className="px-6 py-3">
                                            Frequency
                                        </th>
                                        <th scope="col" className="px-6 py-3">
                                            Start Date
                                        </th>
                                        <th scope="col" className="px-6 py-3">
                                            Next Deposit
                                        </th>
                                        <th scope="col" className="px-6 py-3">
                                            Enabled
                                        </th>
                                        <th scope="col" className="px-6 py-3">
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {goal.auto_deposits.length > 0
                                        ? goal.auto_deposits.map((deposit) => {
                                            return (
                                                <tr className="bg-white border-b" key={deposit.id}>
                                                    <th className="px-6 py-4">
                                                        <Link href={route('accounts.show', deposit.withdraw_account_id)}>
                                                            {accounts.find(a => a.id === deposit.withdraw_account_id)?.name}
                                                        </Link>
                                                    </th>
                                                    <td className="px-6 py-4">
                                                        {deposit.amount.formatted}
                                                    </td>
                                                    <td className="px-6 py-4">
                                                        {uppercaseFirst(deposit.frequency)}
                                                    </td>
                                                    <td className="px-6 py-4">
                                                        {dateFormat(deposit.start_date)}
                                                    </td>
                                                    <td className="px-6 py-4">
                                                        {dateFormat(deposit.next_deposit_date)}
                                                    </td>
                                                    <td className="px-6 py-4">
                                                        <label className="relative inline-flex items-center cursor-pointer">
                                                            <input type="checkbox" className="sr-only peer" defaultChecked={deposit.enabled} onChange={() => toggleDepositEnabled(deposit.id)} />
                                                            <div className="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#838BF1]"></div>
                                                        </label>
                                                    </td>
                                                    <td className="">
                                                        <SecondaryButton onClick={() => showDepositEditModalForId(deposit.id)}>
                                                            <IconPencil className="h-4 w-4" />
                                                        </SecondaryButton>
                                                    </td>
                                                </tr>
                                            );
                                        }) : (
                                            <tr className="bg-white border-b">
                                                <td className="px-6 py-4" colSpan={6}>
                                                    No auto deposits
                                                </td>
                                            </tr>
                                        )}
                                    </tbody>
                                </table>
                                {showDepositEditModal && (
                                    <GoalAutoDepositModal goal={goal} accounts={accounts} show={showDepositEditModal} onClose={() => setShowDepositEditModal(false)} autoDeposit={goal.auto_deposits.find((autoDeposit) => autoDeposit.id == currentDepositEditModalId)} />
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
