<?php

namespace App\Repositories;

use App\Models\Expense;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ExpenseRepository
{
    public function create(array $data): Expense
    {
        return Expense::create($data);
    }

    public function getAllByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Expense::where('user_id', $userId)
            ->orderByDesc('expense_date')
            ->paginate($perPage);
    }

    public function findByIdForUser(int $id, int $userId): ?Expense
    {
        return Expense::where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function updateForUser(int $id, int $userId, array $data): bool
    {
        return Expense::where('id', $id)
            ->where('user_id', $userId)
            ->update($data) > 0;
    }

    public function deleteForUser(int $id, int $userId): bool
    {
        return Expense::where('id', $id)
            ->where('user_id', $userId)
            ->delete() > 0;
    }

    public function filterByDateRange(
        int $userId,
        ?string $fromDate,
        ?string $toDate
    ): Collection {
        $query = Expense::where('user_id', $userId);

        if ($fromDate) {
            $query->whereDate('expense_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('expense_date', '<=', $toDate);
        }

        return $query
            ->orderByDesc('expense_date')
            ->get();
    }

    public function totalByDateRange(
        int $userId,
        ?string $fromDate,
        ?string $toDate
    ): float {
        $query = Expense::where('user_id', $userId);

        if ($fromDate) {
            $query->whereDate('expense_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('expense_date', '<=', $toDate);
        }

        return (float) $query->sum('amount');
    }

    public function getSummaryByCategory(int $userId): Collection
    {
        return Expense::join('categories', 'expenses.category_id', '=', 'categories.id')
            ->where('expenses.user_id', $userId)
            ->selectRaw('categories.name as category, sum(expenses.amount) as total')
            ->groupBy('categories.name')
            ->get();
    }
}
