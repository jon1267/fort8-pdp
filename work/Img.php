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
    public function getImg($request, string $imgField, string $imageStorage)
    {
        $imgPath = null;

        if ($request->hasFile($imgField)) {
            $image = $request->file($imgField);
            if ($image->isValid()) {
                $ext = $image->getClientOriginalExtension();
                $filename = time() . '-' . Str::random(8) . '.' . $ext;
                //dd($image, $filename, $ext);

                //фактическое сохранение {img} - имя поля в $request и в mysql табл.
                //$request->img->storeAs('images', $filename, 'public');//это с симв. ссылкой

                //ок это работает. в папку $imageStorage лара сохраняет картинку (и сама дает ей имя)
                //$imgPath = $request->{$imgField}->store($imageStorage, ['disk' => 'public_images']);//без симв.ссылки

                //вернется полный путь (имя файла и путь) сохраняемого изобр.
                //$imgPath = $request->{$imgField}->storeAs($imageStorage, $filename, ['disk' => 'public_images']);
                //$imgPath = Storage::disk('public_images')->putFileAs($imageStorage, $image, $filename);

                Storage::disk('public_images')->put($imageStorage.'/'.$filename, file_get_contents($image));

                $imgPath = $imageStorage.'/'.$filename;
            }
        }

        return $imgPath;
    }

    public function updateImg($request, string $imgField, ?string $oldImageName, string $imageStorage)
    {
        $imgPath = $oldImageName;

        if ($request->hasFile($imgField)) {
            $image = $request->file($imgField);
            if ($image->isValid()) {
                $imgPath = $this->getImg($request, $imgField, $imageStorage);
            }
        }

        return $imgPath;
    }

    public function deleteImg(string $imgPath)
    {
        return Storage::disk('public_images')->delete('/'.$imgPath);//это без симлинк

    }
}
