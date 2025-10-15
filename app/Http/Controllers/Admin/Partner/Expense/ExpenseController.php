<?php

namespace App\Http\Controllers\Admin\Partner\Expense;

use App\Models\Client;
use App\Models\Expenses;
use Illuminate\Http\Request;
use App\Traits\UploadFileTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Expense\StoreExpenseRequest;

class ExpenseController extends Controller
{
    use UploadFileTrait;

    public function index()
    {
        $expenses = Expenses::with(['client'])->get();
        return view('admin.partner.expense.index', compact('expenses'));
    }

    public function create()
    {
        $clients = Client::where(['type' => 0])->get(['id', 'name']);
        return view('admin.partner.expense.create', compact('clients'));
    }

    public function store(StoreExpenseRequest $request)
    {
        $file = $this->uploadFile(Expenses::PATH, $request->file);
        Expenses::create(['file' => $file] + $request->validated());
        toastr()->success('تمت الاضافة بنجاح');
        return redirect()->back();
    }

    public function destroy($id)
    {
        
               $expense = Expenses::findOrFail($id);
       $res= $expense->delete();
       
        toastr()->success('تمت الحذف بنجاح');
        return redirect()->back();
    }
}
