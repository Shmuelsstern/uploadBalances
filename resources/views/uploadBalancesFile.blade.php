@extends('layouts')

@section('title', 'Upload New Balances')

@section('content')

<div >
        {!! Form::open(['url' => 'uploadNewBalances', 'files' => true]); !!}
        <h2 class='p-3 text-center'>Select the file with new balances(csv)</h2><br><br>
        {!! Form::file('newBalancesFile') !!}
        <button type="submit" class="btn btn-primary">Upload</button>
        {!! Form::close() !!}
</div>

@endsection
