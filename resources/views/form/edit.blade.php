@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Form <a href="{{ route('form.index') }}"><button class="btn btn-success float-right">View Forms</button></a></div>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    @if ($errors->has('email'))
                    @endif
                </div>
                @endif
                <div class="card-body">
                    <form method="post" action="{{ route('form.update', $custom_form->id)  }}">
                        @csrf
                        {{ method_field('PUT') }}
                        <input type="hidden" id="form_id" value="{{$custom_form->id}}">
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Form Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="form_name" id="form_name" autocomplete="off" maxlength="50" required placeholder="Form name" value="{{$custom_form->name}}" />
                            </div>
                        </div>
                        <div id="dynamic_fields">
                            @foreach ($custom_form->FormFields as $custom_form_field)
                            @php
                            $form_value = $loop->index + 1;
                            @endphp
                            <!-- edit dyanmic form start -->
                            <div class="form-group row" id="form-group-{{$form_value}}">
                                <div class="col-lg-2">
                                    <label for="label" class="col-form-label">Label</label>
                                </div>
                                <div class="col-lg-4">
                                    <input class="form-control form-field-label" id="{{$form_value}}" type="text" name="field_name[{{$form_value}}]" placeholder="field name" value="{{$custom_form_field->label}}">
                                    <span class="text-danger" id="{{'error-labels.' . $form_value}}"></span>
                                </div>
                                <div class="col-lg-4">
                                    <select class="form-control" name="field_types[{{$form_value}}]" onchange="typeChange({{$form_value}})" id="field-type-{{$form_value}}">
                                        @isset($field_types)
                                        @foreach ($field_types as $field_type)
                                        <option value="{{$field_type->id}}" {{ ($field_type->id == $custom_form_field->form_field_type_id) ? 'selected' : '' }}>
                                            {{$field_type->name}}
                                        </option>
                                        @endforeach
                                        @endisset
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <button class="btn btn-danger" onclick="removeField({{$form_value}})" type="button">Delete</button>
                                </div>

                            </div>
                            <div class="form-group row mt-2">
                                <div class="col-lg-10 offset-lg-2" id="field-type-properties-{{$form_value}}">
                                    @if ($custom_form_field->form_field_type_id == App\Models\FormFieldType::SELECT)
                                    @php
                                    $text = '';
                                    foreach ($custom_form_field->FormFieldOptions as $key => $option) {
                                    $text = $text . $option->options . ',';
                                    }
                                    @endphp
                                    <input class="form-control" type="text" name="field_select_box_options[{{$form_value}}]" placeholder="Add options separated by comma" required value="{{ rtrim($text,',') }}" />

                                    @endif
                                </div>
                            </div>
                            <!-- edit dyanamic form end -->
                            @endforeach
                        </div>
                        <div class="col-lg-1 offset-lg-11">
                            <button class="btn btn-success" onclick="addFields()" type="button">Add</button>
                        </div>
                        <div class="row">
                            <div class="col-lg-10 text-right">
                                <button class="btn btn-primary" type="submit" id="submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function addFields() {
        var field_count = $(".form-field-label").last().attr('id');
        $.ajax({
            type: 'GET',
            url: "{{route('get-form-fields')}}",
            data: {
                'form_value': parseInt(field_count) + 1
            },
            success: function(response) {
                $('#dynamic_fields').append(response);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
    //remove filds
    function removeField(form_field_value) {
        $("#form-group-" + form_field_value).remove();
    }

    function typeChange(form_field_value) {

        let field_type_properties = $('#field-type-properties-' + form_field_value);

        field_type_properties.empty();
        let field_type = $('#field-type-' + form_field_value).val();

        let html = '';

        if (field_type == '3') {
            html = '<input class="form-control" type="text" name="field_select_box_options[' + form_field_value + ']" placeholder="Add options separated by comma" required/>';
        }

        field_type_properties.append(html);
    }

</script>