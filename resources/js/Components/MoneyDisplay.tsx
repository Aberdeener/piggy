import {Money} from "@/types";
import {HTMLAttributes} from "react";

export default function MoneyDisplay({ money, creditCard = false, className = '', onClick = undefined, ...props }: { money: Money, creditCard?: boolean, className?: string, onClick?: () => void, props?: HTMLAttributes<HTMLSpanElement> }) {
    let colour = null;
    if (creditCard) {
        colour = money.amount > 0 ? 'text-red-600' : 'text-green-600';
    } else {
        colour = money.amount <= 0 ? 'text-red-600' : 'text-green-600';
    }
    return (
        <span className={colour + ' ' + className} onClick={onClick} {...props}>
            {money.formatted}
        </span>
    );
}
