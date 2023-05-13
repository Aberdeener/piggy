import {CreditCardUtilization, GoalStatus} from "@/types";

export default function LineProgress({ status, percentage }: { status: GoalStatus | CreditCardUtilization, percentage: number }) {
    const barColour = (status: GoalStatus | CreditCardUtilization) => {
        if (status === 'completed' || status === 'low') {
            return 'bg-green-500';
        }

        // TODO: on_track never seems to be used for projected percentages
        if (status === 'on_track' || status === 'medium') {
            return 'bg-yellow-500';
        }

        if (status === 'off_track' || status === 'high' || status === 'over_limit') {
            return 'bg-red-500';
        }
    }

    const barPercentage = (percentage: number) => {
        if (percentage > 100) {
            return 100;
        }

        if (percentage < 0) {
            return 0;
        }

        return percentage;
    }

    // parse percentage as a float value, even if it has commas
    percentage = Number(percentage.toString().replace(',', ''));

    return (
        <div className="w-full bg-gray-200 rounded-full h-2.5">
            <div className={`${barColour(status)} h-2.5 rounded-full`} style={{
                "width": `${barPercentage(percentage)}%`,
                "transition": "width 0.3s, background-color 0.1s",
            }}/>
        </div>
    )
}
