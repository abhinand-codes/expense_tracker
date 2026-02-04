<?php

namespace App\Services;

use App\Models\Expense;
use App\Repositories\ExpenseRepository;

class ExpenseService
{
    protected ExpenseRepository $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    public function create(array $data): Expense
    {
        $data['user_id'] = auth()->id();

        return $this->expenseRepository->create($data);
    }

    public function listForUser()
    {
        return $this->expenseRepository->getAllByUser(auth()->id());
    }

    public function getForUser(int $id): Expense
    {
        $expense = $this->expenseRepository->findByIdForUser($id, auth()->id());

        if (! $expense) {
            abort(404, 'Expense not found');
        }

        return $expense;
    }

    public function update(int $id, array $data): void
    {
        $updated = $this->expenseRepository->updateForUser(
            $id,
            auth()->id(),
            $data
        );

        if (! $updated) {
            abort(404, 'Expense not found');
        }
    }

    public function delete(int $id): void
    {
        $deleted = $this->expenseRepository->deleteForUser(
            $id,
            auth()->id()
        );

        if (! $deleted) {
            abort(404, 'Expense not found');
        }
    }

    public function filter(array $filters)
    {
        return $this->expenseRepository->filterByDateRange(
            auth()->id(),
            $filters['from_date'] ?? null,
            $filters['to_date'] ?? null
        );
    }

    public function total(array $filters): float
    {
        return $this->expenseRepository->totalByDateRange(
            auth()->id(),
            $filters['from_date'] ?? null,
            $filters['to_date'] ?? null
        );
    }
}
