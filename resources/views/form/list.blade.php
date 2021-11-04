@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if(session('success'))
            <div class="alert alert-success">{{session('success')}}</div>
            @endif
            <div class="card">
                <div class="card-header">My Forms <a href="{{ route('form.create') }}"><button class="btn btn-success float-right">Create Form</button></a></div>

                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                        @foreach($forms as $key => $form)
                        <tr>
                            <td>
                                {{$key+1}}
                            </td>
                            <td>
                                {{$form->name}}
                            </td>
                            <td>
                                <a href="{{route('view-form', ['id' => $form->id])}}" target="_blank">
                                    <button class="btn btn-success btn-sm">Public View</button>
                                </a>
                                <a href="{{route('form.edit', ['form' => $form->id])}}">
                                    <button class="btn btn-warning btn-sm">Edit</button>
                                </a>

                                <form action="{{ route('form.destroy', $form->id)}}" method="post" style="display: inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @if(count($forms)===0)
                        <tr class="text-center">
                            <td colspan="3">
                                No data found.
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection