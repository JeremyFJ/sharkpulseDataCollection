/**
 * Created by edsan on 3/19/15.
 */
$(document).ready(function () {
    //console.log($("input[name=dropbox_radio]:checked").val());
    fillDropdownMenu($("input[name=dropbox_radio]:checked").val());
    $("input[name=dropbox_radio]:radio").change(function(){
       //console.log($("input[name=dropbox_radio]:checked").val());
       if($("input[name=dropbox_radio]:checked").val() == "Scientific Name"){
           fillDropdownMenu($("input[name=dropbox_radio]:checked").val());
       }
       else{
           fillDropdownMenu($("input[name=dropbox_radio]:checked").val());
       }

    })
});

function fillDropdownMenu(value){
    //$("#species_select").append("<option value='val'>val</option>");
    $("#species_select").empty();
    $.ajax({
            url: "pulseMonitorFunctions.php",
            type: "GET",
            data: {
                value:value
            },
            success: function(json){
                //console.log(json);
                $.each($.parseJSON(json), function (index, val) {
                    $("#species_select").append("<option value='"+val.tsn+"'>"+val.species_name+"</option>")
                });
            },
            error: function (xhr, status, errorThrown) {
                //alert( "Sorry, there was a problem!" );
                console.log( "Error: " + errorThrown );
                console.log( "Status: " + status );
                console.dir( xhr );
            },
            complete: function( xhr, status ) {
                //alert( "The request is complete!" );
            }
        }
    )
}