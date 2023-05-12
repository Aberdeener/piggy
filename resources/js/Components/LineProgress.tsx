import {GoalStatus} from "@/types";

export default function LineProgress({ status, percentage }: { status: GoalStatus, percentage: number }) {
    const barColour = (status: GoalStatus) => {
        switch (status) {
            case 'completed':
                return 'bg-green-500';
            case 'on_track':
                return 'bg-yellow-500';
            case 'off_track':
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
            <div className={`${barColour(status)} h-2.5 rounded-full`} style={{"width": `${barPercentage(percentage)}%`}}></div>
        </div>
    )
}
