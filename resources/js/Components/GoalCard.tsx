import {Goal} from "@/types";
import MoneyDisplay from "@/Components/MoneyDisplay";
import GoalStatusBadge from "@/Components/GoalStatusBadge";
import {Link} from "@inertiajs/react";
import {dateFormat} from "@/utils";

export default function GoalCard({ goal }: { goal: Goal }) {
    return (
        <div className="max-w p-6 bg-white border border-gray-200 rounded-lg shadow">
            <Link href={route('goals.show', goal.id)}>
                <h5 className="mb-2 text-2xl font-semibold tracking-tight text-gray-900 hover:underline">
                    {goal.name}
                </h5>
            </Link>
            <div className="font-normal text-gray-500">
                <p>Target: {goal.target_amount.formatted}</p>
                <p>Target date: {dateFormat(goal.target_date)}</p>
                <p>Current: <MoneyDisplay money={goal.current_amount} /></p>
                <p>Status: <GoalStatusBadge status={goal.status} completion_percentage={goal.completion_percentage} /></p>
                <p>Account: <Link href={route('accounts.show', goal.account.id)}><strong>{goal.account.name}</strong></Link></p>
            </div>
        </div>
    );
}
