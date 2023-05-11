import {Account} from "@/types";
import MoneyDisplay from "@/Components/MoneyDisplay";
import {FormEventHandler, useState} from "react";
import {Link, useForm} from "@inertiajs/react";
import UpdateBalanceModal from "@/Components/UpdateBalanceModal";

export default function AccountCard({ account }: { account: Account }) {
    const [showUpdateBalanceModal, setShowUpdateBalanceModal] = useState(false);

    return (
        <div className="p-6 bg-white border border-gray-200 rounded-lg shadow">
            <Link href={route('accounts.show', account.id)}>
                <h5 className="mb-2 text-2xl font-semibold tracking-tight text-gray-900 hover:underline">
                    {account.name} Account
                </h5>
            </Link>
            <p className="font-normal text-gray-500">
                Balance: <MoneyDisplay className="hover:underline cursor-pointer" money={account.balance} onClick={() => setShowUpdateBalanceModal(true)} />
                <UpdateBalanceModal show={showUpdateBalanceModal} setShow={setShowUpdateBalanceModal} balance={account.balance} path={route('accounts.balance.update', account.id)} />
            </p>
        </div>
    );
}
