<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BudgetRequest;
use App\Repositories\BudgetRepository;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    protected $budgetRepository;

    public function __construct(BudgetRepository $budgetRepository)
    {
        $this->budgetRepository = $budgetRepository;
    }

    /**
     * Display a listing of budgets with its lines.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->only('total_amount');

        $budgets = $this->budgetRepository->get($filters);


        $budgets = $budgets->map->only('id', 'total_amount', 'lines', 'created_at', 'updated_at');

        foreach ($budgets as &$budget) {
            foreach ($budget['lines'] as &$budgetLine) {
                $budgetLine = $budgetLine->only([
                    'id',
                    'vat',
                    'net_mount',
                    'vat_amount',
                    'total_amount',
                    'created_at',
                    'updated_at'
                ]);
            }
        }


        // TODO: Use standard response builder for API
        return response()
            ->json([
                'message' => 'Budgets fetched',
                'data' => ['budgets' => $budgets]
            ]);
    }

    /**
     * Store a newly created Budget.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(BudgetRequest $request)
    {
        // Parses every single budget line to secure
        $budgetLines = [];
        foreach ($request->budget_line as $singleBudgetLine) {
            $budgetLines[] = [
                'net_amount' => $singleBudgetLine['net_amount'],
                'vat' => $singleBudgetLine['vat']
            ];
        }

        // Creates the budget
        $budget = $this->budgetRepository->create($budgetLines);

        // TODO: Use standard response builder for API
        return response()
            ->json([
                'message' => 'The budget was successfully created',
                'data' => ['budget' => $budget]
            ]);
    }
}
