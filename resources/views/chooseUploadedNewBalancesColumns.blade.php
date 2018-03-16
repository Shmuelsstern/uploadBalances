@extends('layouts')

@section('title', 'Set Columns')

@section('content')


    <div class="row">   
        <div class="xs-col-12">
            <table class="table">
            {!! Form::open(['url' => '/setColumns']) !!} 
@php 
$count=0;
$columnsAmount= count($uploadedFile->getParsedFileArray()[0]); 
@endphp  
                <thead>
                    <tr> 
@for($i=0;$i<$columnsAmount;$i++) 
                    <th>
    @if(($i==0)||($i==($columnsAmount-1)))
                    {!! Form::submit('confirmed columns',['class' => 'submitButton']) !!}    
    @endif 
                    </th>                                 
@endfor                        
                    </tr>
                    <tr>
@for($i=0;$i<$columnsAmount;$i++)
                        <th>
{!! Form::select('column'.$i , ParsedFile::$COLUMNS_TO_CHOOSE['newBalances'], null, ['placeholder' => 'do not import','data-column'=> $i]) !!}
                        </th>
@endfor
                    </tr>
                    <tr id='DOSrow' >
@for($i=0;$i<$columnsAmount;$i++)
                        <th>
{!! Form::date('DOS'.$i, null,['style'=>'visibility:hidden','id'=>'DOS'.$i]) !!}
                        </th>
@endfor                    
                    </tr>
@if($headerRow)
                    <tr>
    @foreach($headerRow as $data)
                    <th>
                    {{ $data }}
                    </th>
    @endforeach
                    </tr>
@endif
                </thead>

@foreach($uploadedFile->getParsedFileArray() as $parsedBalance)
    @if(($count++<5)||($count>($loop->count - 5))) 
                    <tbody>
                        <tr>
        @foreach($parsedBalance as $data)
                        <td>
            {{ $data }}
                        </td>
        @endforeach
                        </tr>
                    </tbody>
    @endif

@endforeach
            {!! Form::close() !!}
            </table>
        </div>
    </div>    
@endsection

@section('scripts')
    <script src='js/chooseUploadedNewBalancesColumns.25.js?';></script>
@endsection
