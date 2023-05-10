import {CreditCardUtilization} from "@/types";

export default function CreditCardUtilizationBadge({ utilization, utilization_percentage }: { utilization: CreditCardUtilization, utilization_percentage: number }) {
    let colour = null;
    let utilizationWord = null;

    switch (utilization) {
        case 'low':
            colour = 'bg-green-100 text-green-800';
            utilizationWord = 'Low';
            break;
        case 'medium':
            colour = 'bg-yellow-100 text-yellow-800';
            utilizationWord = 'Medium';
            break;
        case 'high':
            colour = 'bg-red-100 text-red-800';
            utilizationWord = 'High';
            break;
        case 'over_limit':
            colour = 'bg-red-100 text-red-800';
            utilizationWord = 'Over Limit';
            break;
    }

    return (
        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium ${colour}`}>
            {utilizationWord} ({utilization_percentage}%)
        </span>
    )
}
