<?php

namespace Expense;

use PHPUnit\Framework\TestCase;

class ExpenseReportTest extends TestCase
{
    private $report;

    protected function setUp(): void
    {
        $this->report = new ExpenseReport();
    }

    /**
     * حالة 1: مصروف عشاء عادي (أقل من 5000)
     */
    public function test_dinner_below_limit()
    {
        $expenses = [new Expense(ExpenseType::DINNER, 3000)];
        
        $output = $this->captureOutput($expenses);
        
        $this->assertStringContainsString("Dinner\t3000\t \n", $output);
        $this->assertStringContainsString("Meal Expenses: 3000", $output);
        $this->assertStringContainsString("Total Expenses: 3000", $output);
    }

    /**
     * حالة 2: مصروف عشاء زيادة عن الحد (أكتر من 5000)
     */
    public function test_dinner_over_limit()
    {
        $expenses = [new Expense(ExpenseType::DINNER, 6000)];
        
        $output = $this->captureOutput($expenses);
        
        $this->assertStringContainsString("Dinner\t6000\tX\n", $output);
        $this->assertStringContainsString("Meal Expenses: 6000", $output);
        $this->assertStringContainsString("Total Expenses: 6000", $output);
    }

    /**
     * حالة 3: مصروف عشاء على الحد بالظبط (5000)
     */
    public function test_dinner_exactly_at_limit()
    {
        $expenses = [new Expense(ExpenseType::DINNER, 5000)];
        
        $output = $this->captureOutput($expenses);
        
        $this->assertStringContainsString("Dinner\t5000\t \n", $output);
        $this->assertStringContainsString("Meal Expenses: 5000", $output);
    }

    /**
     * حالة 4: مصروف فطار عادي (أقل من 1000)
     */
    public function test_breakfast_below_limit()
    {
        $expenses = [new Expense(ExpenseType::BREAKFAST, 500)];
        
        $output = $this->captureOutput($expenses);
        
        $this->assertStringContainsString("Breakfast\t500\t \n", $output);
        $this->assertStringContainsString("Meal Expenses: 500", $output);
        $this->assertStringContainsString("Total Expenses: 500", $output);
    }

    /**
     * حالة 5: مصروف فطار زيادة عن الحد (أكتر من 1000)
     */
    public function test_breakfast_over_limit()
    {
        $expenses = [new Expense(ExpenseType::BREAKFAST, 1500)];
        
        $output = $this->captureOutput($expenses);
        
        $this->assertStringContainsString("Breakfast\t1500\tX\n", $output);
        $this->assertStringContainsString("Meal Expenses: 1500", $output);
        $this->assertStringContainsString("Total Expenses: 1500", $output);
    }

    /**
     * حالة 6: مصروف فطار على الحد بالظبط (1000)
     */
    public function test_breakfast_exactly_at_limit()
    {
        $expenses = [new Expense(ExpenseType::BREAKFAST, 1000)];
        
        $output = $this->captureOutput($expenses);
        
        $this->assertStringContainsString("Breakfast\t1000\t \n", $output);
        $this->assertStringContainsString("Meal Expenses: 1000", $output);
    }

    /**
     * حالة 7: مصروف تأجير سيارة
     */
    public function test_car_rental()
    {
        $expenses = [new Expense(ExpenseType::CAR_RENTAL, 15000)];
        
        $output = $this->captureOutput($expenses);
        
        $this->assertStringContainsString("Car Rental\t15000\t \n", $output);
        $this->assertStringContainsString("Meal Expenses: 0", $output);
        $this->assertStringContainsString("Total Expenses: 15000", $output);
    }

    /**
     * حالة 8: قائمة فاضية (edge case)
     */
    public function test_empty_expenses_list()
    {
        $expenses = [];
        
        $output = $this->captureOutput($expenses);
        
        $this->assertStringContainsString("Expense Report", $output);
        $this->assertStringContainsString("Meal Expenses: 0", $output);
        $this->assertStringContainsString("Total Expenses: 0", $output);
    }

    /**
     * حالة 9: مزيج من كل أنواع المصروفات
     */
    public function test_mixed_expenses()
    {
        $expenses = [
            new Expense(ExpenseType::DINNER, 6000),      // over limit
            new Expense(ExpenseType::BREAKFAST, 500),     // below limit
            new Expense(ExpenseType::CAR_RENTAL, 15000),  // no limit
            new Expense(ExpenseType::DINNER, 3000),       // below limit
        ];
        
        $output = $this->captureOutput($expenses);
        
        // التحقق من كل سطر
        $this->assertStringContainsString("Dinner\t6000\tX\n", $output);
        $this->assertStringContainsString("Breakfast\t500\t \n", $output);
        $this->assertStringContainsString("Car Rental\t15000\t \n", $output);
        $this->assertStringContainsString("Dinner\t3000\t \n", $output);
        
        // التحقق من المجاميع
        $this->assertStringContainsString("Meal Expenses: 9500", $output); // 6000 + 500 + 3000
        $this->assertStringContainsString("Total Expenses: 24500", $output); // الكل
    }

    /**
     * حالة 10: عدة مصروفات زيادة عن الحد
     */
    public function test_multiple_over_limit_expenses()
    {
        $expenses = [
            new Expense(ExpenseType::DINNER, 7000),
            new Expense(ExpenseType::BREAKFAST, 2000),
        ];
        
        $output = $this->captureOutput($expenses);
        
        $this->assertStringContainsString("Dinner\t7000\tX\n", $output);
        $this->assertStringContainsString("Breakfast\t2000\tX\n", $output);
        $this->assertStringContainsString("Meal Expenses: 9000", $output);
        $this->assertStringContainsString("Total Expenses: 9000", $output);
    }

    /**
     * حالة 11: التحقق من شكل الهيدر والتاريخ
     */
    public function test_report_header_format()
    {
        $expenses = [];
        
        $output = $this->captureOutput($expenses);
        
        $this->assertMatchesRegularExpression(
            '/Expense Report \d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}[ap]m/',
            $output
        );
    }

    /**
     * Helper method: للتقاط الـ output
     */
    private function captureOutput(array $expenses): string
    {
        ob_start();
        $this->report->print_report($expenses);
        return ob_get_clean();
    }
}
