@extends('layouts')

@section('title', 'Confirm Facilities')

@section('content')

    <div class="row">   
        <div class="xs-col-12">
{!! Form::open(['url' => '/updateNewFacilities']) !!}
{!! Form::submit('Confirm') !!}
            <table class="table">
                <thead>
                    <th>Uploaded Facility
                    </th>
                    <th>Matched to Quickbase
                    </th>
                </thead>
                <tbody>
 @foreach($facilityMatcher->getMatcheds() as $key=>$matchedFacility)
               <tr>
                    <td>{{$key}}</td>
                    <td>                   
    @if($matchedFacility['object']->getShortName()=='unmatched')  
        {!! Form::select($matchedFacility['strippedName'],$facilityMatcher->getReferenceRepo()->getCollection()->mapWithKeys(function($item){
        return [$item->getRecordId()=>$item->getShortName()];
    })->all()) !!} 
    @elseif($matchedFacility['object']->getShortName()=='multiple matched')
                        <select class="bg-danger" name={{$matchedFacility['strippedName']}} >  
        @foreach($facilityMatcher->getMultipleMatchedsArray($key)  as $id=>$facilityName)
                            <option value={{$id}} >
                            {{$facilityName}}
                            </option>
        @endforeach 
        @foreach($facilityMatcher->getReferenceRepo()->getArrayForMultipleMatched($facilityMatcher->getMultipleMatchedsArray($key))  as $id=> $facilityName)
                            <option value={{$id}}>
                            {{$facilityName}}
                            </option>
        @endforeach   
                       </select>
    @else
                        <select name={{$matchedFacility['strippedName']}}>
                            <option value={!!$matchedFacility['object']->getRecordId()!!}>
                            {!!$matchedFacility['object']->getShortName()!!}
                            </option>
        @foreach($facilityMatcher->getReferenceRepo()->getArrayForMatched($matchedFacility['object'])  as $id=> $facilityName)
                            <option value={{$id}}>
                            {{$facilityName}}
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
