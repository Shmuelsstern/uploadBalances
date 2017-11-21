@extends('layouts')

@section('title', 'Set Columns')

@section('content')


    <div class="row">   
        <div class="xs-col-12">
            <table class="table">
            {!! Form::open(['url' => '/setColumns']) !!} 
@php 
$count=0; 
@endphp  

@foreach($uploadedFile->getParsedFileArray() as $parsedBalance)
    @if(($count++<5)||($count>($loop->count - 5)))
        @if($loop->first)
                    <thead>
                        <tr>    
                            <th>{!! Form::submit('confirmed columns',['id' => 'submitButton']) !!}</th>
                            <th>There are {{$loop->count}} rows</th>
                        </tr>
                        <tr>
            @for($i=0;$i<count($parsedBalance);$i++)
                            <th>
            {!! Form::select('column'.$i , ParsedFile::$COLUMNS_TO_CHOOSE['newBalances'], null, ['placeholder' => 'do not import','data-column'=> $i]) !!}
                            </th>
            @endfor
                        </tr>
                        <tr id='DOSrow' >
            @for($i=0;$i<count($parsedBalance);$i++)
                            <th>
            {!! Form::date('DOS'.$i, null,['style'=>'visibility:hidden','id'=>'DOS'.$i]) !!}
                            </th>
            @endfor                    
                        </tr>
        @elseif($loop->iteration==2)  
                    <tbody>
        @endif                
                        <tr>
        @foreach($parsedBalance as $data)
            @if($loop->parent->first)
                        <th>
            @else  
                        <td>
            @endif
            {{ $data }}
            @if($loop->parent->first)
                        </th>
            @else  
                        </td>
            @endif

        @endforeach
                        </tr>
        @if($loop->first)
                </thead>
        @elseif($loop->last)  
                </tbody>
        @endif
    @endif

@endforeach
            {!! Form::close() !!}
            </table>
        </div>
    </div>    
@endsection

@section('scripts')
    <script src='js/chooseUploadedNewBalancesColumns.9.js?';></script>
@endsection
