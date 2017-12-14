@extends('layouts')

@section('title', 'Confirm Facilities')

@section('content')

{!! Form::open(['url' => '/updateNewFacilities']) !!}
    <div class="row">   
        <div class="xs-col-12">
            <table class="table">
                <thead>
                    <th>Uploaded Facility
                    </th>
                    <th>Matched to Quickbase
                    </th>
                </thead>
                <tbody>
@foreach($facilityMatcher->getToMatchArray() as $key=>$matchedFacility)
                <tr>
                    <td>{{$key}}</td>
    @if($matchedFacility=='unmatched')
                    <td>unmatched</td>
    @elseif($matchedFacility=='multiple matched')   
                    <td>
                    {!! Form::select($key,$facilityMatcher->getMultipleMatched($key)) !!}
                    </td>
    @else             
                    <td>{{$facilityMatcher->getReferenceCollection()->first(function($value,$key)use($matchedFacility){
                        return $value->getRecordId()==$matchedFacility;
                    })->getRecordId()}}</td>
    @endif
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
