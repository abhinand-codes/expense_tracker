<?php

namespace App\Services;

use App\Models\Expense;
use App\Repositories\ExpenseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExpenseService
{
    protected ExpenseRepository $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function create(array $data): Expense
    {
        $data['user_id'] = Auth::id();
        return $this->expenseRepository->create($data);
    }

    public function list(): Collection
    {
        return $this->expenseRepository->getAllByUser(Auth::id());
    }

    public function get(int $id): Expense
    {
        $expense = $this->expenseRepository->findByIdAndUser($id, Auth::id());

        if (! $expense) {
            throw new ModelNotFoundException('Expense not found');
        }

        return $expense;
    }

    public function update(int $id, array $data): Expense
    {
        $expense = $this->get($id);
        return $this->expenseRepository->update($expense, $data);
    }

    public function delete(int $id): bool
    {
        $expense = $this->get($id);
        return $this->expenseRepository->delete($expense);
    }
}
