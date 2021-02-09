<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aroma;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Note;
use App\Http\Requests\AdminProductStoreRequest;
use App\Http\Requests\AdminProductUpdateRequest;
use App\Services\Images\Img;
use App\Services\Images\ImageDelete;
use App\Services\Variants\Variants;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private $img;
    private $variants;
    private $productImageStorage;
    private $imgDelete;

    public function __construct(Img $img, Variants $variants, ImageDelete $imgDelete)
    {
        $this->img = $img;
        $this->variants = $variants;
        $this->productImageStorage = config('config.product_images');
        $this->imgDelete = $imgDelete;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('admin.products.products_content')->with([
            'title'=> 'Редактирование товара',
            'products' => Product::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.products.products_create')->with([
            'title' => 'Добавить товар',
            'categories' => Category::all(),
            'brands' => Brand::all(),
            'aromas' => Aroma::all(),
            'notes' => Note::all(),

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AdminProductStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AdminProductStoreRequest $request)
    {
        //dd($request);
        $data = $request->except('_token', 'categories', 'variants', 'notes');
        $data['img'] = $this->img->getImg($request, 'img', $this->productImageStorage);
        $data['img1'] = $this->img->getImg($request, 'img1', $this->productImageStorage);
        $data['img2'] = $this->img->getImg($request, 'img2', $this->productImageStorage);
        $categories = $request->categories;
        $notes = $request->notes;
        $notes2 = $request->notes2;
        $notes3 = $request->notes3;
        $rawVariants = $request->only('variants');

        $product = Product::create($data);
        $product->categories()->sync($categories);
        $product->notes()->sync($notes);
        $product->notes2()->sync($notes2);
        $product->notes3()->sync($notes3);

        // (очистка после JS и) сохранение массива вариаций (вариантов)
        $this->variants->store($rawVariants, $product->id);

        return redirect()->route('admin.product.index')
            ->with(['status' => 'Данные успешно добавлены']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Product  $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('admin.products.products_create', [
            'title' => 'Обновление товара',
            'product' => $product,
            'categories' => Category::all(),
            'brands' => Brand::all(),
            'aromas' => Aroma::all(),
            'notes' => Note::all(),
        ]);
    }

    /**
     * @param AdminProductUpdateRequest $request
     * @param Product $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminProductUpdateRequest $request, Product $product)
    {
        //dd($request);
        $data = $request->except('_token', '_method' ,'categories', 'variants');
        $data['img'] = $this->img->updateImg($request, 'img', $product->img, $this->productImageStorage);
        $data['img1'] = $this->img->updateImg($request, 'img1', $product->img1, $this->productImageStorage);
        $data['img2'] = $this->img->updateImg($request, 'img2', $product->img2, $this->productImageStorage);
        $categories = $request->categories;
        $notes = $request->notes;
        $notes2 = $request->notes2;
        $notes3 = $request->notes3;
        $rawVariants = $request->only('variants');

        // удаление, если нажато для img img1 img2
        $delImage = $request->deleted_image;
        $delImage1 = $request->deleted_image1;
        $delImage2 = $request->deleted_image2;
        $this->imgDelete->delete($delImage['model'], $delImage['field'], $delImage['id'], $this->productImageStorage);
        $this->imgDelete->delete($delImage1['model'], $delImage1['field'], $delImage1['id'], $this->productImageStorage);
        $this->imgDelete->delete($delImage2['model'], $delImage2['field'], $delImage2['id'], $this->productImageStorage);

        $product->update($data);
        $product->categories()->sync($categories);
        $product->notes()->sync($notes);
        $product->notes2()->sync($notes2);
        $product->notes3()->sync($notes3);

        //если ввели вариации (варианты) сохраняем их
        $this->variants->store($rawVariants, $product->id);

        return redirect()->route('admin.product.index')
            ->with(['status' => 'Данные успешно обновлены']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $product->categories()->detach();
        $product->notes()->detach();
        $product->notes2()->detach();
        $product->notes3()->detach();
        $this->variants->deleteVariants($product->id);

        if (!empty($product->img)) {
            Storage::delete('/public/'.$this->productImageStorage.'/'.$product->img);
        }
        if (!empty($product->img1)) {
            Storage::delete('/public/'.$this->productImageStorage.'/'.$product->img1);
        }
        if (!empty($product->img2)) {
            Storage::delete('/public/'.$this->productImageStorage.'/'. $product->img2);
        }

        $product->delete();

        return redirect()->route('admin.product.index')
            ->with(['status' => 'Данные успешно удалены']);
    }

    /**
     * тк это вызывается с JS(ajax) то неудобно внедрять php объект, работаем с $product->id
     * @param $productId
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteProductImage($productId)
    {
        $product = Product::where('id', $productId)->first();

        if ($product) {
            Storage::delete('/public/images/' . $product->img);
            $product->update(['img' => '']);
        }

        return response()->json(['success' => true]);
    }
}
