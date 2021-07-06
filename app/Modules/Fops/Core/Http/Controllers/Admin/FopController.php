<?php

namespace App\Modules\Fops\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fop;
use App\Modules\Fops\Core\Http\Requests\AdminFopStoreRequest;
use App\Modules\Fops\Core\Http\Requests\AdminFopUpdateRequest;

class FopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        //dd(config('view.paths'));
        return view('fops.fops_content')->with([
            'title' => 'Редактирование таблицы фопов',
            'fops' => Fop::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('fops.fops_create')->with([
            'title' => 'Добавить фоп',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AdminFopStoreRequest  $request
     */
    public function store(AdminFopStoreRequest $request)
    {
        $data = $request->except('_token');
        $data['active'] = ($request->active === 'on') ? 1 : 0;
        $data['payment_control'] = ($request->payment_control === 'on') ? 1 : 0;

        Fop::create($data);

        return redirect()->route('admin.fop.index')->with(['status' => 'Фоп успешно добавлен']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Fop $fop
     * @return \Illuminate\Http\Response
     */
    public function show(Fop $fop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Fop $fop
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Fop $fop)
    {
        return view('fops.fops_create')->with([
            'title'=> 'Редактирование фопа',
            'fop' => $fop,
        ]);
    }

    /**
     * @param AdminFopUpdateRequest $request
     * @param Fop $fop
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminFopUpdateRequest $request, Fop $fop)
    {
        $data = $request->except('_token', '_method');
        $data['active'] = ($request->active === 'on') ? 1 : 0;
        $data['payment_control'] = ($request->payment_control === 'on') ? 1 : 0;

        $fop->update($data);

        return redirect()->route('admin.fop.index')->with(['status' => 'Данные фопа были изменены']);
    }

    /**
     * @param Fop $fop
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Fop $fop)
    {
        $fop->delete();

        return redirect()->route('admin.fop.index')
            ->with(['status' => 'Данные фопа были удалены']);
    }
}
