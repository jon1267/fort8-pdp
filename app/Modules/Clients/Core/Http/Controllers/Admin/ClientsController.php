<?php

namespace App\Modules\Clients\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Modules\Clients\Core\Http\Requests\AdminClientStoreRequest;
//use App\Modules\Fops\Core\Http\Requests\AdminFopUpdateRequest;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        //dd(config('view.paths'));
        return view('clients.clients_content')->with([
            'title' => 'Редактирование клиентов аукциона',
            'clients' => Client::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('clients.clients_create')->with([
            'title' => 'Добавить клиента аукциона',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  AdminClientStoreRequest  $request
     */
    public function store(AdminClientStoreRequest $request)
    {
        $data = $request->except('_token');
        $data['active'] = ($request->active === 'on') ? 1 : 0;

        Client::create($data);

        return redirect()->route('admin.client.index')->with(['status' => 'Клиент аукциона добавлен']);
    }

    /**
     * Display the specified resource.
     *
     * @param  AdminClientStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function show(AdminClientStoreRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Client $client
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Client $client)
    {
        return view('clients.clients_create')->with([
            'title'=> 'Редактирование клиента аукциона',
            'client' => $client,
        ]);
    }

    /**
     * @param AdminClientStoreRequest $request
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AdminClientStoreRequest $request, Client $client)
    {
        $data = $request->except('_token', '_method');
        $data['active'] = ($request->active === 'on') ? 1 : 0;

        $client->update($data);

        return redirect()->route('admin.client.index')->with(['status' => 'Данные клиента аукциона были изменены']);
    }

    /**
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('admin.client.index')
            ->with(['status' => 'Данные клиента аукциона были удалены']);
    }
}
