<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    //view publuic form
    public function viewForm($id)
    {
        $custom_form = Form::with(['FormFields.FormFieldOptions'])
            ->where('id', $id)
            ->first();

        if ($custom_form) {
            return view('form.public_view', compact('custom_form'));
        } else {
            abort(404);
        }
    }
}
