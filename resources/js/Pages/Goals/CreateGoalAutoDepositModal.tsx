import {useForm} from "@inertiajs/react";
import React, {FormEventHandler} from "react";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import InputLabel from "@/Components/InputLabel";
import InputError from "@/Components/InputError";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import Datepicker from "react-tailwindcss-datepicker";
import MoneyInput from "@/Components/MoneyInput";
import {Account, AccountType, AutoDepositFrequency, Goal} from "@/types";
import {DateValueType} from "react-tailwindcss-datepicker/dist/types";

export default function CreateGoalAutoDepositModal({ goal, accounts, show, onClose }: { goal: Goal, accounts: Account[], show: boolean, onClose: () => void }) {
    const { data, setData, post, processing, errors, reset, isDirty } = useForm({
        goal_id: goal.id,
        amount: undefined,
        frequency: '' as AutoDepositFrequency,
        withdraw_account_id: 0,
        start_date: undefined as Date | undefined,
        enabled: true,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        // TODO: meh
        // TODO: money formatting util
        const withdrawAccountId = document.getElementById('withdraw_account_id') as HTMLSelectElement;
        if (data.withdraw_account_id == 0) {
            withdrawAccountId.setCustomValidity('Please select an account');
            return;
        } else {
            withdrawAccountId.setCustomValidity('');
        }

        post(route('goal-auto-deposits.store'), {
            preserveScroll: true,
            onSuccess: () => {
                onClose()
                reset()
            },
        });
    };

    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);

    const frequencyWord = (frequency: AutoDepositFrequency) => {
        switch (frequency) {
            case 'daily':
                return 'day';
            case 'weekly':
                return 'week';
            case 'biweekly':
                return '2 weeks';
            case 'monthly':
                return 'month';
        }
    }

    return (
        <Modal show={show} onClose={onClose}>
            <form onSubmit={submit} className="m-4">
                <h2 className="text-lg font-medium text-gray-900 pb-2">
                    Creating Auto Deposit
                </h2>

                <InputLabel htmlFor="amount" value="Amount" className="mt-2" />
                <MoneyInput value={data.amount} setData={setData} id="amount" props={{ min: 0.01 }} />
                <InputError className="mt-2" message={errors.amount} />

                <InputLabel htmlFor="frequency" value="Frequency" className="mt-2" />
                <select
                    id="frequency"
                    name="frequency"
                    className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    value={data.frequency}
                    onChange={e => setData('frequency', e.target.value)}
                >
                    <option value="0" disabled>
                        Select a frequency
                    </option>
                    {['daily', 'weekly', 'biweekly', 'monthly'].map((frequency: string) => (
                        <option key={frequency} value={frequency}>{frequency}</option>
                    ))}
                </select>
                <InputError className="mt-2" message={errors.frequency} />

                <InputLabel htmlFor="start_date" value="Start Date" className="mt-2" />
                <Datepicker
                    value={{
                        startDate: data.start_date ?? null,
                        endDate: data.start_date ?? null
                    }}
                    inputId="start_date"
                    inputName="start_date"
                    onChange={(e: DateValueType) => {
                        if (!e) {
                            return;
                        }

                        if (e.startDate) {
                            setData('start_date', new Date(e.startDate.toString()));
                        } else {
                            setData('start_date', undefined);
                        }
                    }}
                    startFrom={yesterday}
                    minDate={yesterday}
                    asSingle
                    useRange={false}
                    containerClassName={"mt-1 relative"}
                />
                <InputError className="mt-2" message={errors.start_date} />

                <InputLabel htmlFor="withdraw_account_id" value="Account" className="mt-2" />
                <select
                    id="account_id"
                    name="account_id"
                    className="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    value={data.withdraw_account_id}
                    onChange={e => setData('withdraw_account_id', Number(e.target.value))}
                >
                    <option value="0" disabled>
                        Select an account
                    </option>
                    {accounts.filter((account: Account) => account.id !== goal.account.id).map((account: Account) => (
                        <option key={account.id} value={account.id}>{account.name}</option>
                    ))}
                </select>
                <InputError className="mt-2" message={errors.withdraw_account_id} />

                {data.amount !== undefined && data.amount > 0 && data.frequency && data.start_date != undefined && data.withdraw_account_id != 0 && (
                    <div className="mt-2 text-gray-500 text-sm">
                        <p>${data.amount} will be taken from your {accounts.find(a => a.id == data.withdraw_account_id)?.name} account every {frequencyWord(data.frequency)} starting on {data.start_date.toDateString()}.</p>
                    </div>
                )}

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
