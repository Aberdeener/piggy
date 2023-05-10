export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};

export interface Goal {
    id: number;
    name: string;
    target_amount: Money;
    status: 'on_track' | 'off_track' | 'completed';
}

export interface Account {
    id: number;
    name: string;
    goals: Goal[];
    balance: Money;
}

export interface CreditCard {
    id: number;
    name: string;
    balance: Money;
    limit: Money;
    utilization: CreditCardUtilization;
    utilization_percentage: number;
}

export type CreditCardUtilization = 'low' | 'medium' | 'high' | 'over_limit';

export interface NetWorth {
    current: Money;
    history: [{
        date: Date;
        amount: Money;
    }]
}

export interface Money {
    currency: string;
    formatted: string;
    amount: number;
}
