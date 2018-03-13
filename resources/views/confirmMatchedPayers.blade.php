@extends('layouts')

@section('title', 'Confirm Payers')

@section('content')

    <div class="row">   
        <div class="xs-col-12">
{!! Form::open(['url' => '/updateNewPayers']) !!}
{!! Form::submit('Confirm') !!}
            <table class="table">
                <thead>
                    <th>Uploaded Payer
                    </th>
                    <th>Matched to Quickbase
                    </th>
                </thead>
                <tbody>
 @foreach($payerMatcher->getMatcheds() as $key=>$matchedPayer)
               <tr>
                    <td>{{$matchedPayer['object']->getName()}}</td>
                    <td>                   
    @if($matchedPayer['object']->getRecordID()=='unmatched')  
        {!! Form::select($matchedPayer['strippedName'],$payerMatcher->getReferenceRepo()->getCollection()->mapWithKeys(function($item){
        return [$item->getRecordId()=>$item->getName()];
    })->all()) !!} 
    @elseif($matchedPayer['object']->getName()=='multiple matched')
                        <select class="bg-danger" name={{$matchedPayer['strippedName']}} >  
        @foreach($payerMatcher->getMultipleMatchedsArray($key)  as $id=>$payerName)
                            <option value={{$id}} >
                            {{$payerName}}
                            </option>
        @endforeach 
        @foreach($payerMatcher->getReferenceRepo()->getArrayForMultipleMatched($payerMatcher->getMultipleMatchedsArray($key))  as $id=> $payerName)
                            <option value={{$id}}>
                            {{$payerName}}
                            </option>
        @endforeach   
                       </select>
    @else
                        <select name={{$matchedPayer['strippedName']}}>
                            <option value={!!$matchedPayer['object']->getRecordId()!!}>
                            {!!$matchedPayer['object']->getName()!!}
                            </option>
        @foreach($payerMatcher->getReferenceRepo()->getArrayForMatched($matchedPayer['object'])  as $id=> $payerName)
                            <option value={{$id}}>
                            {{$payerName}}
                            </option>
        @endforeach            
                        </select>
    @endif          
                    </td>
                </tr>
@endforeach                
                </tbody>
                
            </table>
        </div>
    </div>   
    {!! Form::close() !!}
@endsection

@section('scripts')
    <script src='';></script>
@endsection
