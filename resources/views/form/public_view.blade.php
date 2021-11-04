@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body text-center">
                    <h2>{{$custom_form->name}} </h2>
                    <hr>
                    <form id="public-form" name="public-form" method="post">
                        @csrf
                        @foreach ($custom_form->FormFields as $custom_form_field)
                        <div class="form-group row">
                            <div class="col-lg-3">
                                <label for="label" class="col-form-label font-weight-bold">{{ $custom_form_field->label }}</label>
                            </div>
                            <div class="col-lg-8">
                                @if ($custom_form_field->form_field_type_id == 1)
                                <input type="text" class="form-control" placeholder="{{$custom_form_field->label}}">
                                @elseif ($custom_form_field->form_field_type_id == 2)
                                <input type="number" class="form-control" placeholder="{{$custom_form_field->label}}">
                                @elseif ($custom_form_field->form_field_type_id == 3)
                                <select class="form-control">
                                    @foreach ($custom_form_field->FormFieldOptions as $option_value)
                                    <option>
                                        {{$option_value->options}}
                                    </option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection