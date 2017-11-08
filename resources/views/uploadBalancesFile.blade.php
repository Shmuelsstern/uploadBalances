@extends('layouts')

@section('title', 'Upload New Balances')

@section('content')

<div class='container padding-top'>
    <div class='row'>
        <div class='col-xs-12'>
        {!! Form::open(['url' => 'uploadNewBalances']); !!}
        {!! Form::text('testfield'); !!}
        Select the file with new balances(csv) <br><br>
        {!! Form::submit('upload'); !!}
        {!! Form::close() !!}

        </div>
    </div>
</div>

@endsection
