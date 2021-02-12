<?php

namespace App\Services\Images;

use Illuminate\Support\Str;

class Img
{
    public function getImg($request)
    {
        $imgPath = null;

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            if ($image->isValid()) {
                $ext = $image->getClientOriginalExtension(); // ? strtolower()
                $filename = time() . '-' . Str::random(8) . '.' . $ext;
                //dd($image, $filename, $ext);

                //фактическое сохранение
                $request->img->storeAs('images', $filename, 'public');
                $imgPath = $filename;
            }
        }

        return $imgPath;
    }

    public function updateImg($request, $product)
    {
        $imgPath = $product->img;

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            if ($image->isValid()) {
                $imgPath = $this->getImg($request);
            }
        }

        return $imgPath;
    }
}
