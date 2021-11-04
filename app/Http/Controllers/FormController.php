<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormField;
use App\Models\FormFieldOption;
use App\Models\FormFieldType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //selecting form data
        $data['forms'] = Form::select('id', 'user_id', 'name')
            ->where('user_id', auth()->user()->id)
            ->orderBy('id', 'desc')
            ->paginate(5);

        return view('form.list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //selecting form field types
        $data['field_types'] = FormFieldType::select('id', 'name')
            ->orderBy('name')
            ->get();
        $data['form_value'] = 1;
        return view('form.create', $data);
    }

    public function getFormField(Request $request)
    {
        //selecting form field types
        $data['field_types'] = FormFieldType::select('id', 'name')
            ->orderBy('name')
            ->get();
        $data['form_value'] = $request->form_value;

        return view('form.dynamic_field', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = Validator::make(
            $request->all(),
            [
                'form_name'                     => 'required',
                'field_types.*'                 => 'required',
                "field_name.*"                  => "required",
                "field_select_box_options.*"    => "required",
            ]
        );

        if ($validatedData->fails()) {
            return redirect('form/create')
                ->withErrors($validatedData)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $custom_form = new Form;
            $custom_form->user_id = auth()->user()->id;
            $custom_form->name = $request->form_name;
            $custom_form->save();

            //looping values
            foreach ($request->field_name as $key => $name) {

                $custom_form_fields = new FormField;
                $custom_form_fields->form_id = $custom_form->id;
                $custom_form_fields->form_field_type_id = $request->field_types[$key];
                $custom_form_fields->label = $name;
                $custom_form_fields->save();

                if ($custom_form_fields->form_field_type_id == FormFieldType::SELECT) {

                    $select_box_options = explode(",", $request->field_select_box_options[$key]);

                    foreach ($select_box_options as $option) {
                        $custom_form_field_option = new FormFieldOption;
                        $custom_form_field_option->form_field_id = $custom_form_fields->id;
                        $custom_form_field_option->options = $option;
                        $custom_form_field_option->save();
                    }
                }
            }


            DB::commit();

            return redirect('form')->with('success', 'Form successfully created');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect('form/create')->with('error', 'Failed to create form');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [];
        $field_types = FormFieldType::select('id', 'name')
            ->orderBy('name')
            ->get();
        $data['field_types'] = $field_types;
        $data['custom_form'] = Form::select('id', 'user_id', 'name')
            ->with(['FormFields.FormFieldOptions'])
            ->where('id', $id)
            ->first();

        return view('form.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = Validator::make(
            $request->all(),
            [
                'form_name'                     => 'required',
                'field_types.*'                 => 'required',
                "field_name.*"                  => "required",
                "field_select_box_options.*"    => "required",
            ]
        );

        if ($validatedData->fails()) {
            return redirect('form/' . $id . '/edit')
                ->withErrors($validatedData)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            $custom_form = Form::find($id);
            $custom_form->name = $request->form_name;
            $custom_form->save();

            //delete previous values
            foreach ($custom_form->FormFields as $custom_form_field) {
                $custom_form_field->FormFieldOptions()->delete();
            }

            $custom_form->FormFields()->delete();

            foreach ($request->field_name as $key => $name) {

                $custom_form_fields = new FormField;
                $custom_form_fields->form_id = $custom_form->id;
                $custom_form_fields->form_field_type_id = $request->field_types[$key];
                $custom_form_fields->label = $name;
                $custom_form_fields->save();

                if ($custom_form_fields->form_field_type_id == FormFieldType::SELECT) {

                    $select_box_options = explode(",", $request->field_select_box_options[$key]);

                    foreach ($select_box_options as $option) {
                        $custom_form_field_option = new FormFieldOption;
                        $custom_form_field_option->form_field_id = $custom_form_fields->id;
                        $custom_form_field_option->options = $option;
                        $custom_form_field_option->save();
                    }
                }
            }

            DB::commit();
            return redirect('form')->with('success', 'Form successfully updated');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['errors', 'Failed to update form']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $custom_form = Form::find($id);

            //delete previous values
            foreach ($custom_form->FormFields as $custom_form_field) {
                $custom_form_field->FormFieldOptions()->delete();
            }

            $custom_form->FormFields()->delete();

            $custom_form->delete();


            DB::commit();
            return redirect('form')->with('success', 'Form successfully deleted');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete form');
        }
    }
}
