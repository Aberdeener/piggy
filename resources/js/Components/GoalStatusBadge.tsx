import {GoalStatus} from "@/types";

export default function GoalStatusBadge({ status, completion_percentage, className = '' }: { status: GoalStatus, completion_percentage: number, className?: string }) {
    let colour = null;
    let statusWord = null;

    switch (status) {
        case 'completed':
            colour = 'bg-green-100 text-green-800';
            statusWord = 'Completed';
            break;
        case 'on_track':
            colour = 'bg-yellow-100 text-yellow-800';
            statusWord = 'On Track';
            break;
        case 'off_track':
            colour = 'bg-red-100 text-red-800';
            statusWord = 'Off Track';
            break;
    }

    return (
        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium ${colour} ${className}`}>
            {statusWord} { status !== 'completed' && `(${completion_percentage}%)`}
        </span>
    )
}
