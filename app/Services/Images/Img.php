<?php

namespace App\Services\Images;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class Img
 * Сохраняет в Laravel Storage (php artisan storage:link) файл изображения.
 * Параметры: $request,  string $imgField - имя поля картинки в mysql table,
 * и оно-же (у меня совпадают) ключ картинки в $request. string $imageStorage -
 * подпапка /public в storage куда сохранится изображение: (/public/images/product).
 * в методе updateImg() еще есть параметр string $oldImageName - имя файла старого изображения.
 * Оно возвращается, если в $request нет выбранного изображения, тогда остается старое...
 * Изображению дается имя: 'unix time stamp'-'случайная строка 8 символов'.'родное расширение файла'
 * вызов: $this->img->getImg($request, 'img', $this->productImageStorage);
 * или $this->img->updateImg($request, 'img', $product->img, $this->productImageStorage);
 * или $this->img->updateImg($request, 'header_mobile', $setting->header_mobile, $this->settingsImageStorage);
 * (img - внедренная переменная класса Img.)
 *
 * @package App\Services\Images
 */
class Img
{
    public function save($request, string $imgField, string $imageStorage, $oldImageName = null)
    {
        $imgPath = $oldImageName;

        if ($request->hasFile($imgField)) {
            $image = $request->file($imgField);
            if ($image->isValid()) {
                $ext = $image->getClientOriginalExtension();
                $filename = time() . '-' . Str::random(8) . '.' . $ext;

                Storage::disk('public_images')->put($imageStorage.'/'.$filename, file_get_contents($image));

                $imgPath = $imageStorage.'/'.$filename;
            }
        }

        return $imgPath;
    }

    public function delete(string $imgPath)
    {
        return Storage::disk('public_images')->delete($imgPath);//это без симлинк

    }
}
