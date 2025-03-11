import './bootstrap';

window.calculateSpendingForCurrentMonth = function (expense) {
    const currentDate = new Date();
    const startDate = new Date(expense.start_date);
    const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
    const startOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);

    let spendingForCurrentMonth = 0;
    let occurrences = 0;

    if (expense.recurrence_frequency === 'daily') {
        //will be added later
    }
    else if (expense.recurrence_frequency === 'weekly') {
        //will be added later
    } else if (expense.recurrence_frequency === 'monthly') {
        let monthsSinceStart = (currentDate.getFullYear() - startDate.getFullYear()) * 12 + currentDate.getMonth() - startDate.getMonth();
        if (monthsSinceStart < 0) monthsSinceStart = 0;
        occurrences = Math.floor(monthsSinceStart / expense.recurrence_interval);
        spendingForCurrentMonth = expense.amount / expense.recurrence_interval;
    } else if (expense.recurrence_frequency === 'yearly') {
        spendingForCurrentMonth = Math.floor((expense.amount / expense.recurrence_interval) / 12);
    }

    return spendingForCurrentMonth.toFixed(2);
};

window.isExpenseChargedThisMonth = function (expense) {
    const currentDate = new Date();
    const startDate = new Date(expense.start_date);  // Using expense.start_date (underscore instead of camelCase)

    // Check if the startDate is valid
    if (isNaN(startDate)) {
        console.error('Invalid start_date:', expense.start_date);
        return false; // Early exit if the date is invalid
    }

    if (startDate.getFullYear() > currentDate.getFullYear() ||
        (startDate.getFullYear() === currentDate.getFullYear() && startDate.getMonth() > currentDate.getMonth())) {
        return false; // Expense hasn't started yet
    }

    // Monthly recurrence logic
    else if (expense.recurrence_frequency === 'monthly') {
        let monthsSinceStart = currentDate.getMonth() - startDate.getMonth() + (12 * (currentDate.getFullYear() - startDate.getFullYear()));

        if (monthsSinceStart < 0) monthsSinceStart = 0;

        if (monthsSinceStart % expense.recurrence_interval == 0 || monthsSinceStart == 0) {
            return true; // Charge full amount for this month
        }
    }

    // Yearly recurrence logic
    else if (expense.recurrence_frequency === 'yearly') {
        if (currentDate.getMonth() === startDate.getMonth() && currentDate.getFullYear() === startDate.getFullYear()) {
            return true; // Charge full amount if it's the anniversary month
        }
    }

    return false; // Don't charge if none of the conditions are met
};
