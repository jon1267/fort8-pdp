<?php

namespace App\Modules\Brands\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Modules\Brands\Core\Http\Requests\AdminBrandStoreRequest;
use App\Modules\Brands\Core\Http\Requests\AdminBrandUpdateRequest;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('brands.brands_content')->with([
            'title' => 'Редактирование таблицы брендов',
            'brands' => Brand::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('brands.brands_create')->with([
            'title' => 'Добавить бренд',
            //'userId' => auth()->user()->id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AdminBrandStoreRequest  $request
     */
    public function store(AdminBrandStoreRequest $request)
    {
        Brand::create($request->except('_token'));

        return redirect()->route('admin.brand.index')->with(['status' => 'Бренд успешно добавлен']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Brand  $brand
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Brand $brand)
    {
        return view('brands.brands_create')->with([
            'title'=> 'Редактирование бренда',
            'brand' => $brand,
            //'userId' => auth()->user()->id,
        ]);
    }

    /**
     * @param AdminBrandUpdateRequest $request
     * @param Brand $brand
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminBrandUpdateRequest $request, Brand $brand)
    {
        $brand->update($request->except('_token', '_method'));

        return redirect()->route('admin.brand.index')->with(['status' => 'Бренд был изменен']);
    }

    /**
     * @param Brand $brand
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();

        return redirect()->route('admin.brand.index')
            ->with(['status' => 'Бренд успешно удален']);
    }
}
