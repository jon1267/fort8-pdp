<?php

namespace App\Modules\Clients\Payments\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ClientPaymentRequest;
use App\Modules\Clients\Payments\Core\Http\Requests\UpdateClientPaymentRequest;

class PaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        //dd(config('view.paths'));
        return view('payments.payments_content')->with([
            'title' => 'Редактирование запроса выплат аукциона',
            'payments' => ClientPaymentRequest::paginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('payments.payments_create')->with([
            'title' => 'Добавить запрос выплаты аукциона',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param UpdateClientPaymentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UpdateClientPaymentRequest $request)
    {
        $data = $request->except('_token');
        $data['paid'] = ($request->paid === 'on') ? 1 : 0;

        ClientPaymentRequest::create($data);

        return redirect()->route('admin.payment.index')->with(['status' => 'Запрос выплаты аукциона добавлен']);
    }

    /*public function show(AdminClientStoreRequest $request)
    {
        //
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ClientPaymentRequest $payment
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(ClientPaymentRequest $payment)
    {
        return view('payments.payments_create')->with([
            'title'=> 'Редактирование запроса выплаты аукциона',
            'payment' => $payment,
        ]);
    }

    /**
     * @param UpdateClientPaymentRequest $request
     * @param ClientPaymentRequest $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateClientPaymentRequest $request, ClientPaymentRequest $payment)
    {
        $data = $request->except('_token', '_method');
        $data['paid'] = ($request->paid === 'on') ? 1 : 0;

        $payment->update($data);

        return redirect()->route('admin.payment.index')->with(['status' => 'Данные запроса выплаты аукциона были изменены']);
    }

    /*public function destroy(ClientPaymentRequest $payment)
    {
        $payment->delete();

        return redirect()->route('admin.client.index')
            ->with(['status' => 'Данные запроса выплаты аукциона были удалены']);
    }*/
}

