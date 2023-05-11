import {CreditCard} from "@/types";
import MoneyDisplay from "@/Components/MoneyDisplay";
import CreditCardUtilizationBadge from "@/Components/CreditCardUtilizationBadge";
import {FormEventHandler, useState} from "react";
import UpdateBalanceModal from "@/Components/UpdateBalanceModal";
import {Link} from "@inertiajs/react";
import {IconCreditCard} from "@tabler/icons-react";

export default function CreditCardCard({ creditCard }: { creditCard: CreditCard }) {
    const [showUpdateBalanceModal, setShowUpdateBalanceModal] = useState(false);

    return (
        <div className="p-6 bg-white border border-gray-200 rounded-lg shadow">
            <Link href={route('credit-cards.show', creditCard.id)}>
                <h5 className="mb-2 text-2xl font-semibold tracking-tight text-gray-900 hover:underline">
                    {creditCard.name} Credit Card
                </h5>
            </Link>
            <div className="font-normal text-gray-500">
                <p>Balance: <MoneyDisplay className="hover:underline cursor-pointer" money={creditCard.balance} onClick={() => setShowUpdateBalanceModal(true)} creditCard /></p>
                <UpdateBalanceModal show={showUpdateBalanceModal} setShow={setShowUpdateBalanceModal} balance={creditCard.balance} path={route('credit-cards.balance.update', creditCard.id)} />
                <p>Limit: <span>{creditCard.limit.formatted}</span></p>
                <p>Utilization: <CreditCardUtilizationBadge utilization={creditCard.utilization} utilization_percentage={creditCard.utilization_percentage}/></p>
            </div>
        </div>
    );
}
