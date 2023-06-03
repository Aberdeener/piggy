import DangerButton from "@/Components/DangerButton";
import Modal from "@/Components/Modal";
import {FormEventHandler, useRef, useState} from "react";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import SecondaryButton from "@/Components/SecondaryButton";
import {useForm} from "@inertiajs/react";

export default function DeleteResourceButton({ path, type }: { path: string, type: 'account' | 'credit_card' | 'goal' }) {
    const [show, setShow] = useState(false);
    const passwordInput = useRef<HTMLInputElement>();

    const {
        data,
        setData,
        delete: destroy,
        processing,
        errors,
    } = useForm({
        password: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        destroy(path, {
            onError: () => passwordInput.current?.focus(),
        });
    };

    const humanName = (type: string, toLower: boolean = false) => {
        let name = null;
        switch (type) {
            case 'account':
                name = 'Account';
                break;
            case 'credit_card':
                name =  'Credit Card';
                break;
            case 'goal':
                name =  'Goal';
                break;
        }

        return toLower ? name!.toLowerCase() : name;
    }

    return <>
        <DangerButton onClick={() => setShow(true)}>Delete {humanName(type)}</DangerButton>
        <Modal show={show} onClose={() => setShow(false)}>
            <form onSubmit={submit} className="p-6">
                <h2 className="text-lg font-medium text-gray-900">
                    Are you sure you want to delete this {humanName(type, true)}?
                </h2>

                <p className="mt-1 text-sm text-gray-600">
                    Once deleted, all of its data will be permanently deleted. Please
                    enter your password to confirm you would like to permanently delete this {humanName(type, true)}.
                </p>

                <div className="mt-6">
                    <InputLabel htmlFor="password" value="Password" className="sr-only" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        ref={passwordInput}
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        className="mt-1 block w-3/4"
                        isFocused
                        placeholder="Password"
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="mt-6 flex justify-end">
                    <SecondaryButton onClick={() => setShow(false)}>Cancel</SecondaryButton>

                    <DangerButton className="ml-3" disabled={processing}>
                        Delete {humanName(type)}
                    </DangerButton>
                </div>
            </form>
        </Modal>
    </>
}
