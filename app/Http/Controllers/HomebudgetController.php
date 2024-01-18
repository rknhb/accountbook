<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Homebudget;
use App\Models\Category;

class HomebudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $homebudgets = Homebudget::with('category')->orderBy('date', 'desc')->paginate(5);
        $income = Homebudget::where('category_id', 6)->sum('price');
        $payment = Homebudget::where('category_id', '!=', 6)->sum('price');

        $keyword = $request->input('keyword');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $query = Homebudget::query();
        if(!empty($keyword)) {
            $categoryQuery = Category::query();
            $categoryQuery->where('name', 'LIKE', "%{$keyword}%");
            $categories = $categoryQuery->get();
            if ($categories != null) {
                $ids = [];
                foreach ($categories as $category) {
                    $ids[] = $category->id;
                }
                $query->whereIn('category_id', $ids);
            }
        }

        if (isset($startDate) || isset($endDate)) {
            if (empty($endDate)) {
                $endDate = "9999-12-31";
            } else if (empty($startDate)) {
                $startDate = "1970-01-01";
            }
            $query->whereBetween('date', [$startDate, $endDate]);
        } 

        $homebudgets = $query->orderBy('date', 'desc')->paginate(5);

        return view('homebudget.index', compact('homebudgets', 'income', 'payment', 'keyword'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $result = Homebudget::create([
            'date' => $request->date,
            'category_id' => $request->category,
            'price' => $request->price
        ]);

        if (!empty($result)) {
            session()->flash('flash_message', '支出を登録しました。');
        } else {
            session()->flash('flash_error_message', '支出を登録できませんでした。');
        }

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $homebudget = Homebudget::find($id);
        return view('homebudget.edit', compact('homebudget'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'category_id' => 'required|numeric',
            'price' => 'required|numeric',
        ]);

        $hasData = Homebudget::where('id', '=', $request->id);
        if ($hasData->exists()) {
            $hasData->update([
                'date' => $request->date,
                'category_id' => $request->category_id,
                'price' => $request->price
            ]);
            session()->flash('flash_message', '支出を更新しました。');
        } else {
            session()->flash('flash_error_message', '支出を更新できませんでした。');
        }

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $homebudget = Homebudget::find($id);
        $homebudget->delete();
        session()->flash('flash_message', '収支を削除しました。');
        return redirect('/');
    }
}