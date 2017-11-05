@extends('layouts')

@section('title', 'Upload New Balances')

@section('content')
<!-- The data encoding type, enctype, MUST be specified as below -->
<div class='container padding-top'>
    <div class='row'>
        <div class='col-xs-12'>
            <form enctype="multipart/form-data" action="http://uploadBalances/uploadNewBalances" method="POST">
                {{ csrf_field() }}
                <!-- MAX_FILE_SIZE must precede the file input field -->
                <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                Select the file with new balances(csv) with the following column headings<br><br>
            
                <!-- Name of input element determines name in $_FILES array -->
                <input name="newBalancesfile" type="file" /><hr>
                <input type="submit" value="Upload New Balance File" />

            </form>
        </div>
    </div>
</div>

@endsection