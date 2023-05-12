import {useForm} from "@inertiajs/react";
import React, {FormEventHandler, useEffect, useState} from "react";
import PrimaryButton from "@/Components/PrimaryButton";
import TextInput from "@/Components/TextInput";
import InputLabel from "@/Components/InputLabel";
import InputError from "@/Components/InputError";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import Datepicker from "react-tailwindcss-datepicker";
import MoneyInput from "@/Components/MoneyInput";

export default function CreateGoalModal({ show, onClose }: { show: boolean, onClose: () => void }) {
    const { data, setData, post, processing, errors, reset, isDirty } = useForm({
        name: '',
        target_date: new Date(),
        target_amount: 0,
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
