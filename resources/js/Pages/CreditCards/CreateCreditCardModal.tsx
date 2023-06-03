import {useForm} from "@inertiajs/react";
import React, {FormEventHandler, useEffect} from "react";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import InputLabel from "@/Components/InputLabel";
import InputError from "@/Components/InputError";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import MoneyInput from "@/Components/MoneyInput";

export default function CreateCreditCardModel({ show, onClose }: { show: boolean, onClose: () => void }) {
    const { data, setData, post, processing, errors, reset, isDirty } = useForm({
        name: '',
        balance: undefined,
        limit: undefined,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('credit-cards.store'), {
            preserveScroll: true,
            onSuccess: () => {
                onClose()
                reset()
            },
        });
    };

    return (
        <Modal show={show} onClose={onClose}>
            <form onSubmit={submit} className="m-4">
                <h2 className="text-lg font-medium text-gray-900 pb-2">
                    Creating Credit Card
                </h2>

                <InputLabel htmlFor="name" value="Name" />
                <TextInput
                    id="name"
                    placeholder="Name"
                    className="mt-1 block w-full"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    required
                    isFocused
                />
                <InputError className="mt-2" message={errors.name} />

                <InputLabel htmlFor="balance" value="Balance" className="mt-2" />
                <MoneyInput value={data.balance} setData={setData} id="balance" />
                <InputError className="mt-2" message={errors.balance} />

                <InputLabel htmlFor="limit" value="Limit" className="mt-2" />
                <MoneyInput value={data.limit} setData={setData} id="limit" />
                <InputError className="mt-2" message={errors.limit} />

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
