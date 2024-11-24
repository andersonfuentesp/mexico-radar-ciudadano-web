<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use App\Mail\CotizacionFormMail;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('landing.index');
    }

    public function nosotros()
    {
        return view('landing.nosotros');
    }

    public function nosotrosEmpresa()
    {
        return view('landing.nosotros-empresa');
    }

    public function servicios()
    {
        return view('landing.servicios');
    }

    public function contacto()
    {
        return view('landing.contacto');
    }

    public function contactoStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:6',
            'message' => 'required',
        ], [
            'name.required' => 'El campo nombre es obligatorio',
            'name.max' => 'El nombre no puede contener más de 255 caracteres',
            'phone.required' => 'El campo teléfono es obligatorio',
            'phone.regex' => 'El teléfono tiene un formato inválido',
            'phone.min' => 'El teléfono debe tener al menos 6 caracteres',
            'message.required' => 'El campo mensaje es obligatorio',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($validator->fails()) {
            $notification = [
                'message' => $validator->errors()->first(), // Obtiene el primer error de validación
                'alert-type' => 'error'
            ];

            return redirect()->back()->with('notification', $notification);
        }

        $details = [
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
            'subject' => $request->get('subject'),
            'message' => $request->get('message')
        ];

        Mail::to("contacto@grupoitaai.com")->send(new ContactFormMail($details));

        $notification = [
            'message' => 'Mensaje enviado con éxito',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with('notification', $notification);
    }
}
