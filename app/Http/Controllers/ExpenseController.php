<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SendinBlue\Client\Api\TransactionalEmailsApi;
use SendinBlue\Client\Model\SendSmtpEmail;
use SendinBlue\Client\Configuration;
use Exception;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller{
    
    public function index()
    {
        return view('admin.expenses.index'); // just loads view with empty or initial content
    }

    public function fetchExpenses(Request $request)
    {
        $expenses = DB::table('expenses')->where('gym_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.expenses.partials.expense-table', compact('expenses'))->render(); // returns only table partial
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'amount'  => 'required|numeric|min:1',
            'date'     => 'required|date',
            'description'   => 'nullable|string|max:255',
        ]);


        DB::table('expenses')->insert([
            'name' => $request->input('name'),
            'amount'  => $request->input('amount'),
            'date'     => $request->input('date'),
            'gym_id'    => Auth::user()->id,
            'description'   => $request->input('description'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Plan added successfully']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'editName' => 'required|string|max:255',
            'amount'  => 'required|numeric|min:1',
            'date'     => 'required|date',
            'expense_id'   => 'required|exists:expenses,id',
        ]);

        DB::table('expenses')->where('id', $request->input('expense_id'))->update([
            'name' => $request->input('editName'),
            'amount'  => $request->input('amount'),
            'date'     => $request->input('date'),    
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Expense updated successfully']);
    }

    public function view($id)
    {
        $id = decrypt($id);
        $expense = DB::table('expenses')->where('id', $id)->first();
        return view('admin.expenses.view', compact('expense'));
    }
}
