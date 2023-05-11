import {CreditCard} from "@/types";
import MoneyDisplay from "@/Components/MoneyDisplay";
import CreditCardUtilizationBadge from "@/Components/CreditCardUtilizationBadge";
import {FormEventHandler, useState} from "react";
import UpdateBalanceModal from "@/Components/UpdateBalanceModal";
import {Link} from "@inertiajs/react";

export default function CreditCardCard({ creditCard }: { creditCard: CreditCard }) {
    const [showUpdateBalanceModal, setShowUpdateBalanceModal] = useState(false);

    return (
        <div className="max-w p-6 bg-white border border-gray-200 rounded-lg shadow">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth="1.5"
                 stroke="currentColor" className="w-10 h-10 mb-2 text-gray-500">
                <path strokeLinecap="round" strokeLinejoin="round"
                      d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
            </svg>
            <Link href={route('credit-cards.show', creditCard.id)}>
                <h5 className="mb-2 text-2xl font-semibold tracking-tight text-gray-900 hover:underline">
                    {creditCard.name} Credit Card
                </h5>
            </Link>
            <div className="mb-3 font-normal text-gray-500">
                <p>Balance: <MoneyDisplay className="hover:underline cursor-pointer" money={creditCard.balance} onClick={() => setShowUpdateBalanceModal(true)} creditCard /></p>
                <UpdateBalanceModal show={showUpdateBalanceModal} setShow={setShowUpdateBalanceModal} balance={creditCard.balance} path={'credit-cards.balance.update'} id={creditCard.id} />
                <p>Limit: <span>{creditCard.limit.formatted}</span></p>
                <p>Utilization: <CreditCardUtilizationBadge utilization={creditCard.utilization} utilization_percentage={creditCard.utilization_percentage}/></p>
            </div>
        </div>
    );
}
