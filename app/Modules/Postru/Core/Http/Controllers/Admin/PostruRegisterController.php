<?php

namespace App\Modules\Postru\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostruRegisters;
use App\Modules\Postru\Core\Http\Controllers\Api\PostruController;


class PostruRegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('postru.postru_content')->with([
            'title' => 'Реестры. Почта России',
            'registers' => PostruRegisters::paginate(10),
        ]);
    }

    /**
     * метод назван show чтоб он вписался в схему ресурных контроллеров ларавела
     * (это попытка получить pdf F103 по N партии, из админки Реестры-Почта России)
     * @param string $batch
     */
    public function show(string $batch)
    {
        $postru = new PostruController();
        $result = $postru->printF103($batch);
        header("Content-type: application/pdf");
        print $result->getStream();
        die();
    }
}
