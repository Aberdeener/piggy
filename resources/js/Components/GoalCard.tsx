import {Goal} from "@/types";
import MoneyDisplay from "@/Components/MoneyDisplay";
import GoalStatusBadge from "@/Components/GoalStatusBadge";
import {Link} from "@inertiajs/react";

export default function GoalCard({ goal }: { goal: Goal }) {
    return (
        <div className="max-w p-6 bg-white border border-gray-200 rounded-lg shadow">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                <path strokeLinecap="round" strokeLinejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            <Link href={route('goals.show', goal.id)}>
                <h5 className="mb-2 text-2xl font-semibold tracking-tight text-gray-900 hover:underline">
                    {goal.name} Goal
                </h5>
            </Link>
            <div className="mb-3 font-normal text-gray-500">
                <p>Target: {goal.target_amount.formatted}</p>
                <p>Target date: {goal.target_date.toString()}</p>
                <p>Current: <MoneyDisplay money={goal.current_amount} /></p>
                <p>Status: <GoalStatusBadge status={goal.status} completion_percentage={goal.completion_percentage} /></p>
                <p>Account: <strong>{goal.account.name}</strong></p>
            </div>
        </div>
    );
}
