$(function(){

    'use strict';

    const select = $('select');
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
    });
});