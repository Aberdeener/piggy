import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import React from "react";
import {PageProps, Goal as GoalType} from "@/types";
import SecondaryButton from "@/Components/SecondaryButton";
import {IconPencil, IconProgress} from "@tabler/icons-react";
import GoalStatusBadge from "@/Components/GoalStatusBadge";
import Modal from "@/Components/Modal";

export default function Goal({ auth, goal }: PageProps<{ goal: GoalType }>) {

    let barColour;
    switch (goal.status) {
        case 'completed':
            barColour = 'bg-green-500';
            break;
        case 'on_track':
            barColour = 'bg-yellow-500';
            break;
        case 'off_track':
            barColour = 'bg-red-500';
            break;
    }

    let barPercentage;
    if (goal.completion_percentage < 0) {
        barPercentage = 0;
    } else if (goal.completion_percentage > 100) {
        barPercentage = 100;
    } else {
        barPercentage = goal.completion_percentage;
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

                            <p>Target amount: {goal.target_amount.formatted}</p>
                            <p>Current amount: {goal.current_amount.formatted}</p>
                            <p>Target date: {goal.target_date}</p>
                            <p>Linked account: {goal.account.name}</p>
                            <p>Projected total: {goal.projected_total_by_target_date.formatted}</p>

                            <div className="w-full bg-gray-200 rounded-full h-2.5">
                                <div className={`${barColour} h-2.5 rounded-full mt-4`} style={{"width": `${barPercentage}%`}}></div>
                            </div>

                            <table className="w-full text-sm text-left text-gray-500">
                                <caption className="p-5 text-lg font-semibold text-left text-gray-900 bg-white">
                                    Auto deposits
                                    <p className="mt-1 text-sm font-normal text-gray-500">
                                        Automatically deposit money into this goal from your accounts.
                                    </p>
                                </caption>
                                <thead className="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" className="px-6 py-3">
                                        Account
                                    </th>
                                    <th scope="col" className="px-6 py-3">
                                        Amount
                                    </th>
                                    <th scope="col" className="px-6 py-3">
                                        Frequency
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
                                    {goal.auto_deposits.map((deposit) => {
                                        return (
                                            <tr className="bg-white border-b" key={deposit.id}>
                                                <th scope="row" className="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                                    {deposit.withdraw_account_id}
                                                </th>
                                                <td className="px-6 py-4">
                                                    {deposit.amount.formatted}
                                                </td>
                                                <td className="px-6 py-4">
                                                    {deposit.frequency}
                                                </td>
                                                <td className="px-6 py-4">
                                                    {deposit.next_deposit_date.toString()}
                                                </td>
                                                <td className="px-6 py-4">
                                                    <label className="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" value="" className="sr-only peer"/>
                                                        <div className="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                    </label>
                                                </td>
                                                <td className="px-6 py-4">
                                                    <SecondaryButton onClick={() => showDepositEditModal(deposit.id)}>
                                                        <IconPencil className="h-4 w-4" />
                                                    </SecondaryButton>
                                                </td>
                                            </tr>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
