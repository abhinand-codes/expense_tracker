<?php

namespace App\Repositories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Collection;

class ExpenseRepository
{
   public function create(array $data): Expense
{
    return Expense::create($data);
}


    public function getAllByUser(int $userId): Collection
    {
        return Expense::where('user_id', $userId)->get();
    }

    public function findByIdAndUser(int $id, int $userId): ?Expense
    {
        return Expense::where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function update(Expense $expense, array $data): Expense
    {
        $expense->update($data);
        return $expense;
    }

    public function delete(Expense $expense): bool
    {
        return (bool) $expense->delete();
    }

    public function getAllByUser(int $userId)
{
    return Expense::where('user_id', $userId)
        ->orderByDesc('expense_date')
        ->get();
}

public function findByIdForUser(int $id, int $userId): ?Expense
{
    return Expense::where('id', $id)
        ->where('user_id', $userId)
        ->first();
}

}
