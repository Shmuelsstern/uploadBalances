$(function(){

    'use strict';

    const select = $('select');

    select.change(function(e){
        console.log('#DOS'+e.target.dataset.column);
        if ($(this).val()==='balance'){
            $('#DOS'+e.target.dataset.column).css('visibility','visible');
        }else{
            $('#DOS'+e.target.dataset.column).css('visibility','hidden');
        }
    });
});