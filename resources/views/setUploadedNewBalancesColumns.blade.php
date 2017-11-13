@extends('layouts')

@section('title', 'Set Columns')

@section('content')


    <div class="row">   
        <div class="xs-col-12">
            <table class="table">
@foreach($uploadedFile->getParsedFileArray() as $parsedBalance)

    
    @if($loop->first)
                <thead>
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

    @endforeach
            </table>
        </div>
    </div>

@endsection
