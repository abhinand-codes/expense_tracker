<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Http\Resources\ExpenseResource;
use App\Services\ExpenseService;

class ExpenseController extends Controller
{
    protected ExpenseService $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->middleware('auth:api');
        $this->expenseService = $expenseService;
    }

    public function store(StoreExpenseRequest $request)
    {
        $expense = $this->expenseService->create($request->validated());

        return response()->json([
            'data' => new ExpenseResource($expense),
        ], 201);
    }

    public function index()
    {
        $expenses = $this->expenseService->listForUser();

        return ExpenseResource::collection($expenses);
    }

    public function show(int $id)
    {
        $expense = $this->expenseService->getForUser($id);

        return new ExpenseResource($expense);
    }

    public function update(UpdateExpenseRequest $request, int $id)
    {
        $this->expenseService->update($id, $request->validated());

        return response()->json([
            'message' => 'Expense updated successfully',
        ]);
    }

    public function destroy(int $id)
    {
        $this->expenseService->delete($id);

        return response()->json([
            'message' => 'Expense deleted successfully',
        ]);
    }
}
