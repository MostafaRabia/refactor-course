<?php

namespace Expense;

abstract class ExpenseType {
    const DINNER = 1;
    const BREAKFAST = 2;
    const CAR_RENTAL = 3;
}

class Expense {
    public $type;
    public $amount;
    function __construct($type, $amount) {
        $this->type = $type;
        $this->amount = $amount;
    }
}

class ExpenseLine {
    public $expenseName;
    public $amount;
    public $overExpenseMarker;
    function __construct($expense, $expenseName, $overExpenseMarker) {
        $this->expenseName = $expenseName;
        $this->amount = $expense->amount;
        $this->overExpenseMarker = $overExpenseMarker;
    }
}

class ExpenseReport {
    const DINEER_LIMIT = 5000;
    const BREAKFAST_LIMIT = 1000;

    private array $expenseLines = [];

    function print_report($expenses) {
        $mealExpenses = $this->getTotalOfMealExpenses($expenses);
        $total = $this->getTotal($expenses);
        $this->gatherExpenseLines($expenses);
        $this->printReportInTxt($expenses, $mealExpenses, $total);
    }

    private function getExpenseName(Expense $expense): string
    {
        return match ($expense->type) {
            ExpenseType::DINNER => "Dinner",
            ExpenseType::BREAKFAST => "Breakfast",
            ExpenseType::CAR_RENTAL => "Car Rental",
            default => "",
        };
    }

    private function addXIfLimitExceeded(Expense $expense): string
    {
        return (
                $expense->type == ExpenseType::DINNER
                && $expense->amount > self::DINEER_LIMIT
            ) || (
                $expense->type == ExpenseType::BREAKFAST
                && $expense->amount > self::BREAKFAST_LIMIT
            )
            ? "X"
            : " ";
    }

    private function getMealExpenses(Expense $expense, int $mealExpenses): int
    {
        if (in_array($expense->type, [ExpenseType::DINNER, ExpenseType::BREAKFAST], true)) {
            $mealExpenses += $expense->amount;
        }
        return $mealExpenses;
    }

    private function getTotalOfMealExpenses($expenses): int
    {
        $mealExpenses = 0;
        foreach ($expenses as $expense) {
            $mealExpenses = $this->getMealExpenses($expense, $mealExpenses);
        }
        return $mealExpenses;
    }

    private function getTotal($expenses): int
    {
        $total = 0;
        foreach ($expenses as $expense) {
            $total += $expense->amount;
        }
        return $total;
    }

    private function gatherExpenseLines($expenses): void
    {
        foreach ($expenses as $expense) {
            $expenseName = $this->getExpenseName($expense);
            $mealOverExpensesMarker = $this->addXIfLimitExceeded($expense);

            $this->expenseLines[] = new ExpenseLine(
                $expense,
                $expenseName,
                $mealOverExpensesMarker
            );
        }
    }

    private function printReportInTxt($expenses, int $mealExpenses, int $total): void
    {
        $date = date("Y-m-d h:i:sa");
        print("Expense Report {$date}\n");
        foreach ($this->expenseLines as $expenseLine) {
            print($expenseLine->expenseName . "\t" . $expenseLine->amount . "\t" . $expenseLine->overExpenseMarker . "\n");
        }
        print("Meal Expenses: " . $mealExpenses . "\n");
        print("Total Expenses: " . $total . "\n");
    }
}
