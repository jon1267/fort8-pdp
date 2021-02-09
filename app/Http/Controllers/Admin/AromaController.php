<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aroma;
//use Illuminate\Http\Request;
use App\Http\Requests\AdminAromaStoreRequest;
use App\Http\Requests\AdminAromaUpdateRequest;

class AromaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.aromas.aromas_content')->with([
            'title' => 'Редактирование таблицы ароматов',
            'aromas' => Aroma::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.aromas.aromas_create')->with([
            'title' => 'Добавить аромат',
            //'userId' => auth()->user()->id,//
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AdminAromaStoreRequest $request
     */
    public function store(AdminAromaStoreRequest $request)
    {
        Aroma::create($request->except('_token'));

        return redirect()->route('admin.aroma.index')
            ->with(['status' => 'Аромат успешно добавлен']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Aroma  $aroma
     * @return \Illuminate\Http\Response
     */
    public function show(Aroma $aroma)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Aroma $aroma
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Aroma $aroma)
    {
        return view('admin.aromas.aromas_create')->with([
            'title'=> 'Редактирование аромата',
            'aroma' => $aroma,
            //'userId' => auth()->user()->id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AdminAromaUpdateRequest  $request
     * @param Aroma  $aroma
     * @return \Illuminate\Http\Response
     */
    public function update(AdminAromaUpdateRequest $request, Aroma $aroma)
    {
        $aroma->update($request->except('_token', '_method'));

        return redirect()->route('admin.aroma.index')
            ->with(['status' => 'Аромат был изменен']);
    }

    /**
     * @param Aroma $aroma
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Aroma $aroma)
    {
        $aroma->delete();

        return redirect()->route('admin.aroma.index')
            ->with(['status' => 'Аромат успешно удален']);
    }
}
