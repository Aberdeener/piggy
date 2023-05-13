function dateFormat(value: string | Date): string {
    if (!(value instanceof Date)) {
        value = new Date(value);
    }

    return value.toDateString();
}

function uppercaseFirst(value: string): string {
    return value.charAt(0).toUpperCase() + value.slice(1);
}

export {
    dateFormat,
    uppercaseFirst,
};
