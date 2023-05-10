import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import React from "react";
import {PageProps, Goal as GoalType} from "@/types";

export default function Goal({ auth, goal }: PageProps<{ goal: GoalType }>) {

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Goals</h2>}
        >
            <Head title="Goals" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <div className="p-6 grid gap-4 border-b border-gray-200 grid-cols-3">
                                {JSON.stringify(goal)}
                            </div>
                            <div className="flex items-center">
                                <div className="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                    <div className="bg-blue-600 h-2.5 rounded-full" style={{"width": `${goal.completion_percentage}%`}}></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
