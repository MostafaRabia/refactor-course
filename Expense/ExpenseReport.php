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

class ExpenseReport {
    const DINEER_LIMIT = 5000;
    const BREAKFAST_LIMIT = 1000;

    function print_report($expenses) {
        $mealExpenses = $this->getTotalOfMealExpenses($expenses);
        $total = 0;
        $date = date("Y-m-d h:i:sa");
        print("Expense Report {$date}\n");

        $total = $this->getTotal($expenses, $total);
        foreach ($expenses as $expense) {
            $expenseName = $this->getExpenseName($expense);
            $mealOverExpensesMarker = $this->addXIfLimitExceeded($expense);
            print($expenseName . "\t" . $expense->amount . "\t" . $mealOverExpensesMarker . "\n");
        }
        print("Meal Expenses: " . $mealExpenses . "\n");
        print("Total Expenses: " . $total . "\n");
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

    private function getTotal($expenses, $total): mixed
    {
        foreach ($expenses as $expense) {
            $total += $expense->amount;
        }
        return $total;
    }
}
