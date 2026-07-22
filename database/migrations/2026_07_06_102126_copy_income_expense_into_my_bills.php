<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ---- Income rows ----
        $incomeRows = DB::table('income_amounts')->get();

        foreach ($incomeRows as $row) {
            DB::table('my_bills')->insert([
                'name'           => $row->name,
                'category'       => 'Income',
                'description'    => $row->description ?? null,
                'bill_date'      => $row->income_date,
                'start_date'     => $row->income_date,
                'end_date'       => $row->income_date,
                'recurring_type' => 0,
                'status'         => $row->status ?? 'Active',
                'bill_amount'    => $row->amount,
                'created_at'     => $row->created_at ?? now(),
                'updated_at'     => $row->updated_at ?? now(),
            ]);
        }

        // ---- Expense rows ----
        $expenseRows = DB::table('expensive_amounts')->get();

        foreach ($expenseRows as $row) {
            DB::table('my_bills')->insert([
                'name'           => $row->name,
                'category'       => 'Expense',
                'description'    => $row->description ?? null,
                'bill_date'      => $row->expensive_date,
                'start_date'     => $row->expensive_date,
                'end_date'       => $row->expensive_date,
                'recurring_type' => 0,
                'status'         => $row->status ?? 'Active',
                'bill_amount'    => $row->amount,
                'created_at'     => $row->created_at ?? now(),
                'updated_at'     => $row->updated_at ?? now(),
            ]);
        }
    }

    public function down(): void
    {
        // Best-effort rollback: remove rows tagged Income/Expense
        // (safe only if MyBill's own native rows never use these exact categories accidentally)
        DB::table('my_bills')->where('category', 'Income')->delete();
        DB::table('my_bills')->where('category', 'Expense')->delete();
    }
};