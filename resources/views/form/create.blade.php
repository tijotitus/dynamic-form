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
                    <form method="post" action="{{ route('form.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="staticEmail" class="col-sm-2 col-form-label">Form Name</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="form_name" id="form_name" autocomplete="off" maxlength="50" required placeholder="Form name" />
                            </div>
                        </div>
                        <div id="dynamic_fields">
                            @include('form.dynamic_field')
                        </div>
                        <div class="col-lg-1 offset-lg-11">
                            <button class="btn btn-success" onclick="addFields()" type="button">Add</button>
                        </div>
                        <div class="row">
                            <div class="col-lg-10 text-right">
                                <button class="btn btn-primary" type="submit" id="submit">Submit</button>
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