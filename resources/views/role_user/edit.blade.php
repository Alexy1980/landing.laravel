@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">Редактирование роли пользователя # {{ \App\User::find($role_user->id)
                    ->name }}</div>
                    <div class="panel-body">
                        <a href="{{ url('/admin/role_user') }}" title="Back"><button class="btn btn-warning btn-xs"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif


                        {!! Form::model($role_user, [
                            'method' => 'PATCH',
                            'url' => ['/admin/role_user', $role_user->id],
                            'class' => 'form-horizontal',
                            'files' => true
                        ]) !!}

                            @include ('role_user.form', ['submitButtonText' => 'Update'])

                            {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
