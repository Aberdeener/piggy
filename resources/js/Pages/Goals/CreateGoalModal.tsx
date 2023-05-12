import {useForm, usePage} from "@inertiajs/react";
import React, {FormEventHandler, useEffect, useState} from "react";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import InputLabel from "@/Components/InputLabel";
import InputError from "@/Components/InputError";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import Datepicker from "react-tailwindcss-datepicker";
import MoneyInput from "@/Components/MoneyInput";
import {Account} from "@/types";

export default function CreateGoalModal({ accounts, show, onClose }: { accounts: Account[], show: boolean, onClose: () => void }) {
    const { data, setData, post, processing, errors, reset, isDirty } = useForm({
        name: '',
        target_date: new Date(),
        target_amount: 0,
        account_id: 0,
        use_account_balance_to_start: false,
    });

    useEffect(() => {
        return () => {
            reset('name');
        };
    }, []);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('goals.store'), {
            preserveScroll: true,
            onFinish: onClose,
        });
    };

    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);

    // @ts-ignore
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

                <InputLabel htmlFor="target_amount" value="Target amount" className="mt-2" />
                <MoneyInput value={data.target_amount} setData={setData} id="target_amount" />
                <InputError className="mt-2" message={errors.target_amount} />

                <InputLabel htmlFor="target_date" value="Target Date" className="mt-2" />
                <Datepicker
                    value={data.target_date}
                    inputId="target_date"
                    inputName="target_date"
                    onChange={(e) => setData('target_date', e.startDate.toString())}
                    startFrom={yesterday}
                    minDate={yesterday}
                    asSingle
                    useRange={false}
                    containerClassName={"mt-1 relative"}
                />
                <InputError className="mt-2" message={errors.target_date} />

                <InputLabel htmlFor="account_id" value="Account" className="mt-2" />
                <select
                    id="account_id"
                    name="account_id"
                    className="mt-1 block w-full"
                    value={data.account_id}
                    onChange={(e) => setData('account_id', e.target.value)}
                >
                    <option value="0">Select an account</option>
                    {accounts.map((account: Account) => (
                        <option key={account.id} value={account.id}>{account.name}</option>
                    ))}
                </select>
                <InputError className="mt-2" message={errors.account_id} />

                {data.account_id !== 0 && (<>
                    <InputLabel htmlFor="use_account_balance_to_start" value="Use account balance to start?" className="mt-2" />
                    <label className="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" className="sr-only peer" value="0" onChange={(e) => setData('use_account_balance_to_start', !use_account_balance_to_start)} />
                        <div className="w-11 h-6 bg-gray-200 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#838BF1]"></div>
                    </label>
                    <InputError className="mt-2" message={errors.use_account_balance_to_start} />
                </>)}

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
