$(function(){

    'use strict';

    const select = $('select');
    let optionsArray=$('select[name="column0"]>option[selected!="selected"]').map(function callback(currentValue,index,array){
        console.log('currentValue',currentValue,'index',index,'array',array);
        return index.value;
    });
    console.log(optionsArray);

    select.change(function(e){
        console.log('#DOS'+e.target.dataset.column);
        if ($(this).val()==='balance'){
            $('#DOS'+e.target.dataset.column).css('visibility','visible');
        }else{
            $('#DOS'+e.target.dataset.column).css('visibility','hidden');
        }
    });
});