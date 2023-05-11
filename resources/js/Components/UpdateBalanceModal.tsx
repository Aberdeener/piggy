import Modal from "@/Components/Modal";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import PrimaryButton from "@/Components/PrimaryButton";
import {useForm} from "@inertiajs/react";
import {FormEventHandler} from "react";
import {Money} from "@/types";
import SecondaryButton from "@/Components/SecondaryButton";
import DangerButton from "@/Components/DangerButton";

export default function UpdateBalanceModal({ show, setShow, balance, path, id }: { show: boolean, setShow: (show: boolean) => void, balance: Money, path: string, id: number }) {
    const { data, setData, patch, isDirty, processing, errors } = useForm({
        balance: balance.amount / 100,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        patch(route(path, id));

        setShow(false);
    };

    return (
        <Modal show={show} onClose={() => setShow(false)}>
            <form onSubmit={submit} className="p-6">
                <h2 className="text-lg font-medium text-gray-900">
                    Update Balance
                </h2>

                <div className="relative">
                    <div className="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6">
                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <input
                        type="number"
                        id="balance"
                        step={0.01}
                        value={data.balance}
                        className="mt-1 block w-full p-4 pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        onChange={(e) => setData('balance', e.target.value)}
                        required />
                </div>

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
