<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\Images\Img;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private $img;
    private $settingsImageStorage;

    public function __construct(Img $img)
    {
        $this->img = $img;
        $this->settingsImageStorage = config('config.settings_images');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Setting $setting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit(Setting $setting)
    {
        return view('admin.settings.settings')->with([
            'title'=> 'Настройки',
            'setting' => $setting,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        $data = $request->except('_token', '_method');
        $data['header_mobile'] = $this->img->save($request, 'header_mobile', $this->settingsImageStorage, $setting->header_mobile);
        $data['header_desktop'] = $this->img->save($request, 'header_desktop', $this->settingsImageStorage, $setting->header_desktop);
        $data['slider_show'] = $data['slider_show'] ?? 0;

        $setting->update($data);

        return redirect('home')->with(['status' => 'Настройки были обновлены']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }
}
