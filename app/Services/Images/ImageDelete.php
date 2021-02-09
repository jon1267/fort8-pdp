<?php

namespace App\Services\Images;

use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;

/**
 * Class ImageDelete
 * Удаляет файл картинки из файлсторедж, и апдейтит в пустую строку поле с именем картинки.
 * Параметры - $model ларина модель например 'Product',  $field имя поля, $id - id записи,
 * $imgStorage - подпапка папки '/public' где длжен быть файл картинки. Это сделано, чтоб была
 * возможность удалять картинку (очищать поле с именем файла) и в Тварах и Категориях и тд.
 * @package App\Services\Images
 */
class ImageDelete
{

    public function delete($model, $field, $id, $imgStorage)
    {
        if (is_null($model) || is_null($field) || is_null($id) || is_null($imgStorage)) {
            return false;
        }

        $deletedModel =  app('App\Models\\'.$model)::where('id', $id)->first();

        if ($deletedModel) {
            Storage::delete('/public/'.$imgStorage.'/'. $deletedModel->{$field});
            $deletedModel->update([ "{$field}" => '']);
            return true;
        }

        return false;
    }
}
