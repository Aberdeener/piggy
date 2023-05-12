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
    target_date: string;
    status: GoalStatus;
    current_amount: Money;
    completion_percentage: number;
    account: Account;
    auto_deposits: AutoDeposit[];
    projected_status: GoalStatus;
    projected_total_by_target_date: Money;
    projected_completion_percentage: number;
}

export type GoalStatus = 'on_track' | 'off_track' | 'completed';

export interface AutoDeposit {
    id: number;
    amount: Money;
    withdraw_account_id: number;
    frequency: AutoDepositFrequency;
    next_deposit_date: string;
    last_deposit_date: string;
    enabled: boolean;
}

export type AutoDepositFrequency = 'daily' | 'weekly' | 'biweekly' | 'monthly';

export interface Account {
    id: number;
    name: string;
    goals: Goal[];
    balance: Money;
}

export interface AccountBalanceHistory {
    date: string;
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

export interface CreditCardBalanceHistory {
    date: string;
    balance: Money;
}

export type CreditCardUtilization = 'low' | 'medium' | 'high' | 'over_limit';

export interface NetWorth {
    current: Money;
    history: [{
        date: string;
        amount: Money;
    }]
}

export interface Money {
    currency: string;
    formatted: string;
    amount: number;
}
