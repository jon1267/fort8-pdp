<?php

namespace App\Modules\Operators\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Operator;
use App\Modules\Operators\Core\Http\Requests\AdminOperatorStoreRequest;
use App\Modules\Operators\Core\Http\Requests\AdminOperatorUpdateRequest;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('operators.operators_content')->with([
            'title' => 'Редактирование таблицы операторов',
            'operators' => Operator::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('operators.operators_create')->with([
            'title' => 'Добавить бренд',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AdminOperatorStoreRequest  $request
     */
    public function store(AdminOperatorStoreRequest $request)
    {
        Operator::create($request->except('_token'));

        return redirect()->route('admin.operator.index')->with(['status' => 'Оператор успешно добавлен']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Operator  $operator
     * @return \Illuminate\Http\Response
     */
    public function show(Operator $operator)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Operator $operator
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Operator $operator)
    {
        return view('operators.operators_create')->with([
            'title'=> 'Редактирование оператора',
            'operator' => $operator,
        ]);
    }

    /**
     * @param AdminOperatorUpdateRequest $request
     * @param Operator $operator
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminOperatorUpdateRequest $request, Operator $operator)
    {
        $operator->update($request->except('_token', '_method'));

        return redirect()->route('admin.operator.index')->with(['status' => 'Оператор был изменен']);
    }

    /**
     * @param Operator $operator
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Operator $operator)
    {
        $operator->delete();

        return redirect()->route('admin.operator.index')
            ->with(['status' => 'Оператор успешно удален']);
    }
}
