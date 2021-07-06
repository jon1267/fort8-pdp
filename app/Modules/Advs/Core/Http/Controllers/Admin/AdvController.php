<?php

namespace App\Modules\Advs\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Adv;
use App\Modules\Advs\Core\Http\Requests\AdminAdvStoreRequest;
use App\Modules\Advs\Core\Http\Requests\AdminAdvUpdateRequest;

class AdvController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        //dd(config('view.paths'));
        return view('advs.advs_content')->with([
            'title' => 'Редактирование таблицы advs',
            'advs' => Adv::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('advs.advs_create')->with([
            'title' => 'Добавить в таблицу advs',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AdminAdvStoreRequest  $request
     */
    public function store(AdminAdvStoreRequest $request)
    {
        $data = $request->except('_token');
        $data['pixel_plugin'] = ($request->pixel_plugin === 'on') ? 1 : 0;

        Adv::create($data);

        return redirect()->route('admin.adv.index')->with(['status' => 'Данные были добавлены']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Adv $adv
     * @return \Illuminate\Http\Response
     */
    public function show(Adv $adv)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Adv $adv
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Adv $adv)
    {
        return view('advs.advs_create')->with([
            'title'=> 'Редактирование таблицы advs',
            'adv' => $adv,
        ]);
    }

    /**
     * @param AdminAdvUpdateRequest $request
     * @param Adv $adv
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminAdvUpdateRequest $request, Adv $adv)
    {
        $data = $request->except('_token', '_method');
        $data['pixel_plugin'] = ($request->pixel_plugin === 'on') ? 1 : 0;

        $adv->update($data);

        return redirect()->route('admin.adv.index')->with(['status' => 'Данные были изменены']);
    }

    /**
     * @param Adv $adv
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Adv $adv)
    {
        $adv->delete();

        return redirect()->route('admin.adv.index')
            ->with(['status' => 'Данные были удалены']);
    }
}
