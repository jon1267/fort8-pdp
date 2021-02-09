<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\Images\Img;
use App\Services\Images\ImageDelete;
use App\Http\Requests\AdminCategoryStoreRequest;
use App\Http\Requests\AdminCategoryUpdateRequest;

class CategoryController extends Controller
{
    private $img;
    private $imgDelete;
    private $settingsImageStorage;
    /**
     * @var ImageDelete
     */
    private $imageDelete;

    public function __construct(Img $img, ImageDelete $imgDelete)
    {
        $this->img = $img;
        $this->imgDelete = $imgDelete;
        $this->settingsImageStorage = config('config.settings_images');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.categories.categories_content')->with([
            'title'=> 'Редактирование категорий',
            'categories' => Category::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.categories_create')->with([
            'title' => 'Добавить категорию',
            'userId' => auth()->user()->id,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AdminCategoryStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminCategoryStoreRequest $request)
    {
        //dd($request);
        $data = $request->except('_token');
        $data['header_mobile'] = $this->img->getImg($request, 'header_mobile',  $this->settingsImageStorage);
        $data['header_desktop'] = $this->img->getImg($request, 'header_desktop',  $this->settingsImageStorage);
        $data['slider_show'] = $data['slider_show'] ?? 0;

        if (Category::create($data)) {
            return redirect()->route('admin.category.index')
                ->with(['status' => 'Категория успешно добавлена']);
        }

        $request->flash();
        return redirect()->route('admin.category.index')
            ->with(['error' => 'Ошибка добавления категории']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.categories_create')->with([
            'title'=> 'Редактирование категории',
            'category' => $category,
            'userId' => auth()->user()->id,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AdminCategoryUpdateRequest  $request
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(AdminCategoryUpdateRequest $request, Category $category)
    {
        //dd($request, $category);
        $data = $request->except('_token', '_method', 'deleted_image', 'deleted_image1');
        $data['header_mobile'] = $this->img->updateImg($request, 'header_mobile', $category->header_mobile, $this->settingsImageStorage);
        $data['header_desktop'] = $this->img->updateImg($request, 'header_desktop', $category->header_desktop ,$this->settingsImageStorage);
        $data['slider_show'] = $data['slider_show'] ?? 0;

        $delImage = $request->deleted_image;
        $this->imgDelete->delete($delImage['model'], $delImage['field'], $delImage['id'], $this->settingsImageStorage);

        $nextImage = $request->deleted_image1;
        $this->imgDelete->delete($nextImage['model'], $nextImage['field'], $nextImage['id'], $this->settingsImageStorage);


        if ($category->update($data)) {
            return redirect()->route('admin.category.index')
                ->with(['status' => 'Категория успешно изменена']);
        }

        $request->flash();
        return redirect()->route('admin.category.index')
            ->with(['error' => 'Ошибка изменения категории']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        if($category->delete()) {
            return redirect()->route('admin.category.index')
                ->with(['status' => 'Категория успешно удалена']);
        }

        return redirect()->route('admin.category.index')
            ->with(['error' => 'Ошибка удаления данных']);
    }
}
