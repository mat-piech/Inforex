/**
 * Part of the Inforex project
 * Copyright (C) 2013 Michał Marcińczuk, Jan Kocoń, Marcin Ptak
 * Wrocław University of Technology
 */
var user_cookie = "corpus_flag_history_user";
var flag_cookie = "corpus_flag_history_flag";

$(function(){
    //Changes the number of pages available in Datatables pagination
    // e.g. 1 ... 10 instead of 1,2,3,4,5 ... 10 when numbers_length = 3;
    $.fn.DataTable.ext.pager.numbers_length = 5;

    $('#flag_history').DataTable({
        "bInfo": false,
        "ordering": false
    });

    var selected_user = $.cookie(user_cookie);
    var selected_flag = $.cookie(flag_cookie);

    if(selected_user){
        $("#user_filter").val(selected_user);
    }

    if(selected_flag){
        $("#flag_filter").val(selected_flag);
    }

    $("#apply_history_filters").click(function(){
        var user_value = $('#user_filter').val();
        if(user_value !== "-"){
            $.cookie(user_cookie, user_value);
        } else{
            $.cookie(user_cookie, null);
        }

        var flag_value = $('#flag_filter').val();
        if(flag_value !== "-"){
            $.cookie(flag_cookie, flag_value);
        } else{
            $.cookie(flag_cookie, null);
        }

        location.reload();
    });
});
