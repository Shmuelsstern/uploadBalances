$(function(){

    'use strict';

    const selectGroupDropdown = $('.select-group-dropdown'),
          selectFacilityList = $("#selectFacilityList"),
          dropdownMenu = $(".dropdown-menu");

    var id, name, selected;

    dropdownMenu.click(function(e)
    {
        var subject = $(e.target).parent().attr('id').replace('select', '').replace('List', '');
        console.log(subject);
        id=e.target.dataset.id;
        name = e.target.innerHTML;
        selected=$('#selected'+subject);
        console.log(selected);
        /*selected.html(name);*/

        $.getJSON('layouts/'+subject.toLowerCase()+'Settings',{'id':id, 'name':name} ,function(data)
        {
            if (subject==='Group') {
                let facilityListHtml = '';
                data.forEach(function (facility) {
                    facilityListHtml += '<a class="dropdown-item" href="#"' +
                        '                                                        data-id="' +
                        facility.record_id_ +
                        '                                                                                       ">' +
                        facility.short_name +
                        '</a>';
                });
                selectFacilityList.html(facilityListHtml);
                $('#selectedFacility').html('All Facilities');
            }
            console.log('line36',name);
            selected.html(name);
        });
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /*const select = $('select');
    let optionsArray=$('select[name="column0"]>option[selected!="selected"]').map(function callback(currentValue,index,array){
        console.log('currentValue',currentValue,'index',index,'array',array);
        return index.value;
    });
    console.log(optionsArray);

    select.change(function(e){
        let DOSColumn=$('#DOS'+e.target.dataset.column);
        console.log(DOSColumn);
        if ($(this).val()==='balance'){
            DOSColumn.css('visibility','visible');
        }else{
            DOSColumn.css('visibility','hidden');
        }
    });*/
});