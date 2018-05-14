(function($) {

    $(document).ready(function() {
        // 선택사항보기
        $(".view_options").click(function() {
            var it_id = $(this).closest("tr").length ? $(this).closest("tr").find("input[name^=it_id]").val() : $(this).closest("li").find("input[name^=it_id]").val();
            var $this = $(this);
            // close_btn_idx = $(".mod_options").index($(this));
            console.log( it_id );

            $.post(
                "./cartoption.php",
                { it_id: it_id },
                function(data) {
                    $("#mod_option_frm").remove();
                    $this.after("<div id=\"mod_option_frm\"></div>");
                    $("#mod_option_frm").html(data);
                    $(".option_wr").css("display", "none");
                    // price_calculate();
                }
            );
        });
	});

})(jQuery);