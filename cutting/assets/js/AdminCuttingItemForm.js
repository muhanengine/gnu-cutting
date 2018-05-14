$(document).ready(function(){
    $(".it_cut_opt").click(function(){
        var cutOpt = $(this).val();

        setCuttingOptions( cutOpt );
    });

    $(".it_cut_amount_basic").click(function(){
        var cut_amount = parseInt( $("#de_cut_amount").val() );
        var cut_check  = $(this).val();

        if ( cut_check === "1" ) {
            $("input[name=it_cut_amount]").val( cut_amount );
        }
    });

    $(".it_cut_lose_basic").click(function(){
        var cut_lose = parseInt( $("#de_cut_lose").val() );
        var cut_check  = $(this).val();

        if ( cut_check === "1" ) {
            $("input[name=it_cut_lose]").val( cut_lose );
        }
    });

    function setCuttingOptions( cutOpt ) {
        $(".cut_lumber").slideUp("fast");

        switch ( cutOpt ) {
            case "1":  case "3": // 길이, 자투리
                $(".cut_amount").slideDown("slow");
                $(".cut_lose").slideDown("slow");
                $(".cut_width").slideDown("slow");
                $(".sale_unit").slideDown("slow");
                break;

            case "2": // 판재
                $(".cut_amount").slideDown("slow");
                $(".cut_lose").slideDown("slow");
                $(".cut_width").slideDown("slow");
                $(".cut_height").slideDown("slow");
                $(".sale_unit").slideDown("slow");
                break;
        }
    }

    setCuttingOptions( $(":input:radio[name=it_cut_opt]:checked").val() );

});