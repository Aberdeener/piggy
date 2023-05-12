import {useForm} from "@inertiajs/react";
import React, {FormEventHandler, useEffect} from "react";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import InputLabel from "@/Components/InputLabel";
import InputError from "@/Components/InputError";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";

export default function CreateAccountModal({ show, onClose }: { show: boolean, onClose: () => void }) {
    const { data, setData, post, processing, errors, reset, isDirty } = useForm({
        name: '',
        balance: '',
    });

    useEffect(() => {
        return () => {
            reset('name');
        };
    }, []);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('accounts.store'), {
            preserveScroll: true,
            onFinish: onClose,
        });
    };

    return (
        <Modal show={show} onClose={onClose}>
            <form onSubmit={submit} className="m-4">
                <InputLabel htmlFor="name" value="Name" />

                <TextInput
                    id="name"
                    className="mt-1 block w-full"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    required
                    isFocused
                    autoComplete="name"
                />

                <InputError className="mt-2" message={errors.name} />

                <InputLabel htmlFor="balance" value="Balance" className="mt-2" />

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
                        className="mt-1 block w-full pl-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        onChange={(e) => setData('balance', e.target.value)}
                        onBlur={e => setData('balance', parseFloat(e.target.value).toFixed(2))}
                        required />
                </div>

                <InputError className="mt-2" message={errors.balance} />

                <div className="mt-6 flex justify-end">
                    <SecondaryButton onClick={onClose}>Cancel</SecondaryButton>

                    <PrimaryButton className="ml-3" disabled={processing || !isDirty}>
                        Create
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
    )
}
