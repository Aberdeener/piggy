import Modal from "@/Components/Modal";
import InputError from "@/Components/InputError";
import PrimaryButton from "@/Components/PrimaryButton";
import {useForm} from "@inertiajs/react";
import {FormEventHandler} from "react";
import {Money} from "@/types";
import SecondaryButton from "@/Components/SecondaryButton";
import MoneyInput from "@/Components/MoneyInput";

export default function UpdateBalanceModal({ show, setShow, balance, path }: { show: boolean, setShow: (show: boolean) => void, balance: Money, path: string }) {
    const { data, setData, patch, isDirty, processing, errors } = useForm({
        balance: balance.amount / 100,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        patch(path, {
            preserveScroll: true,
        });

        setShow(false);
    };

    return (
        <Modal show={show} onClose={() => setShow(false)}>
            <form onSubmit={submit} className="p-6">
                <h2 className="text-lg font-medium text-gray-900">
                    Update Balance
                </h2>

                <MoneyInput value={data.balance} setData={setData} id="balance" />

                <InputError message={errors.balance} className="mt-2" />

                <div className="mt-6 flex justify-end">
                    <SecondaryButton onClick={() => setShow(false)}>Cancel</SecondaryButton>

                    <PrimaryButton className="ml-3" disabled={processing || !isDirty}>
                        Submit
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    )
}
