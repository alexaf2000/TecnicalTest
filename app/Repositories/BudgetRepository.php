<?php

namespace App\Repositories;

use App\Models\Budget;
use App\Models\BudgetLine;
use Illuminate\Support\Collection;


class BudgetRepository
{
    /**
     * Returns a list of budgets
     *
     * @param array|null $filters
     * @return Collection
     */
    public function get(?array $filters): Collection
    {
        $budgets = Budget::query();

        // Filtering
        if($filters != null){
            if (isset($filters['total_amount'])) {
                $budgets->where('total_amount', '>', $filters['total_amount']);
            }
        }

        // Get elements with it's relationship
        $budgets = $budgets->with('lines')->get();

        return $budgets;
    }

    /**
     * Creates a budget.
     *
     * @param array $budgetLines Must be an array with net_amount and vat
     * @return Budget Created budget
     */
    public function create(array $budgetLines): Budget
    {
        $budgetLineModels = Collection::make();

        foreach ($budgetLines as $singleBudgetLine) {
            // Check that required properties are set
            if (isset($singleBudgetLine['net_amount']) && isset($singleBudgetLine['vat'])) {
                // Parses to the model type
                $budgetLineModels->push(
                    new BudgetLine([
                        'net_amount' => $singleBudgetLine['net_amount'],
                        'vat' => $singleBudgetLine['vat'],
                        'vat_amount' => BudgetLine::calculateVatAmount(floatval($singleBudgetLine['net_amount'],), floatval($singleBudgetLine['vat'])),
                        'total_amount' => BudgetLine::calculateTotal(floatval($singleBudgetLine['net_amount'],), floatval($singleBudgetLine['vat']))
                    ])
                );
            }
        }
        // Free budget lines from memory
        unset($budgetLines);

        // Creates the budget with the total amount
        $budget = Budget::create([
            'total_amount' => $budgetLineModels->sum('total_amount')
        ]);

        $budget->lines()->saveMany($budgetLineModels);

        return $budget;
    }
}
