(function($) {

    $(document).ready(function() {

		var count = 1;

		$(".button-cutting").on("click", function(){ // 재단 저장후 목록 출력
			price_cut_calculate();
			save_cutting();
		});

		function save_cutting() {
			var dataString = $("form[name=fitem]").serialize();
			var qty        = parseInt( $(".cutting_qty").val() );
			var message    = [];
			var msgAlert   = "";
			var tmpLength  = 0;

            dataString += "&action=itemCuttingUpdate";

			if ( isNaN(qty) || qty === 0 ) {
				alert("재단 길이를 먼저 입력하세요.");
				return false;
			}

			$(".cutting_count").each(function(){
			    if ( ! $.isNumeric( $(this).val() ) ) {
                    message.push( "재단 수량을 먼저 입력하세요." );
                }
            });

			// console.log( dataString );
            if ( $.isNumeric(it_min_height) && $.isNumeric(it_max_height) ) {
                $(".cutting_height").each(function(){
                    tmpLength = parseInt( $(this).val() );

                    if ( ! $.isNumeric( $(this).val() ) || tmpLength < it_min_height || tmpLength > it_max_height ) {
                        message.push( "재단길이(세로) 최소, 최대 길이을 확인 하세요." );
                    }
                });
            }

            if ( $.isNumeric(it_min_width) && $.isNumeric(it_max_width) ) {
                $(".cutting_width").each(function(){
                    tmpLength = parseInt( $(this).val() );

                    if ( ! $.isNumeric( $(this).val() ) || tmpLength < it_min_width || tmpLength > it_max_width ) {
                        message.push( "재단길이(가로) 최소, 최대 길이을 확인 하세요." );
                    }
                });
            }

            if ( message.length ) {
                $.each(message, function( index, value ) {
                    msgAlert += value +"\n";
                });
                alert( msgAlert );
                return false;
            }

			$.ajax({
				type: "POST",
				url: cutting_url,
				data: dataString,
				dataType: "json",
				success: function(data) {
				    // console.log( data );
					switch(data) {
						case 100 : get_cutting_list(); break;
						case 904 : alert('최소, 최대 구매 길이를 확인 하세요.'); break;
						case 903 : alert('이미 추가하신 옵션상품 입니다.'); break;
						case 902 : alert('현재 판매가능한 상품이 아닙니다.'); break;
						case 901 : alert('자료가 없습니다.'); break;
						case 900 : alert('브라우저의 쿠키 허용을 사용하지 않음으로 설정한것 같습니다.\n\n브라우저의 인터넷 옵션에서 쿠키 허용을 사용으로 설정해 주십시오.'); break;
						default : alert( '잘못된 접근입니다.\n\n' + data ); break;
					}
				},
				error: function(data){
                    console.log("failed: "+data);
				}
			});
			return false;
		}

		$(document).on("click", ".sit_opt_delete", function(){ // 재단 삭제후 목록 출력
			var cutting_id = $(this).attr("cutting_id");
			var dataString = $("form[name=fitem]").serialize();

			dataString += "&cutting_id="+cutting_id+"&action=itemCuttingDelete";

			if ( !confirm( "재단을 삭제 하시겠습니까?" ) ) {
				return false;
			}

			$.ajax({
				type: "POST",
				url: cutting_url,
				data: dataString,
				dataType: "json",
				success: function(data) {
					switch(data) {
						case 101 : get_cutting_list(); break;
						case 903 : alert('이미 추가하신 옵션상품 입니다.'); break;
						case 902 : alert('현재 판매가능한 상품이 아닙니다.'); break;
						case 901 : alert('자료가 없습니다.'); break;
						case 900 : alert('브라우저의 쿠키 허용을 사용하지 않음으로 설정한것 같습니다.\n\n브라우저의 인터넷 옵션에서 쿠키 허용을 사용으로 설정해 주십시오.'); break;
						default : alert( '잘못된 접근입니다.\n\n' + data ); break;
					}
				},
				error: function(data){
                    console.log("failed:"+data);
				}
			});
			return false;
		});

		// 재단 목록 출력
		function get_cutting_list() {
			var it_id = $("input[name='it_id[]']").val();
			var nonce = $("input[name='nonce']").val();

			$('.list-cutting').hide();
			$('.list-cutting').fadeIn("slow");
			$('.list-cutting').show();

            $.ajax({
                type: "POST",
                url: cutting_url,
                data: { it_id: it_id, ajax_action: "action_cart_cutting", nonce: nonce, action: "itemCuttingList" },
                dataType: "html",
                cache: false,
                async: false,
                success: function(data) {
                    if ( data ) {
                        $('.list-cutting').html( data );
                        it_cut_opt = parseInt(it_cut_opt);

                        switch (it_cut_opt) {
                            case 1:
                            case 2:
                                //__길이(자투리 없음)_판재__//
                                price_cutting_unit();
                                break;

                            case 3:
                                //__길이(자투리 있음)__//
                                price_cutting_calculate();
                                break;

                            case 4:
                                //__맞춤사이즈__//
                                // price_cutting_custom();
                                break;
                        }
                    }
                },
                error: function(data){
                    console.log("failed: "+data);
                }
            });

            $(".sit_opt_delete").on("click", function(){ // 재단 삭제후 목록 출력
                var cutting_id = $(this).attr("cutting_id");
                var dataString = $("form[name=fitem]").serialize();

                dataString += "&cutting_id="+cutting_id+"&action=itemCuttingDelete";

                if ( !confirm( "재단을 삭제 하시겠습니까?" ) ) {
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: cutting_url,
                    data: dataString,
                    dataType: "json",
                    success: function(data) {
                        switch(data) {
                            case 100 : get_cutting_list(); break;
                            case 101 : get_cutting_list(); break; // 삭제완료
                            case 903 : alert('이미 추가하신 옵션상품 입니다.'); break;
                            case 902 : alert('현재 판매가능한 상품이 아닙니다.'); break;
                            case 901 : alert('자료가 없습니다.'); break;
                            case 900 : alert('브라우저의 쿠키 허용을 사용하지 않음으로 설정한것 같습니다.\n\n브라우저의 인터넷 옵션에서 쿠키 허용을 사용으로 설정해 주십시오.'); break;
                            default : alert( '잘못된 접근입니다.\n\n' + data ); break;
                        }
                    },
                    error: function(data){
                        console.log("failed:"+ data);
                    }
                });
                return false;
            });
		}

		// 재단 추가입력 만들기
	    $(".add-cutting").on("click", function() {
	    	var copyProt = $(".fields-cutting:eq(0)").clone();
	        var copyRow  = copyProt.find(".delete-cutting").attr("cutting");
	        var rowTotal = $(".shop_table tr").length;

	        count++;

	        copyProt.fadeIn('1000', function(){ });
	        copyProt.removeClass("cutting"+copyRow);
			copyProt.addClass("cutting"+count);
			copyProt.find(".delete-cutting").attr("cutting", count);
			copyProt.find("input").val("");
			copyProt.find("select").attr("value", "");
			$(".tb_cut_cutting").append( copyProt );

	        return false;
	    });

	    // 재단 입력란 삭제하기
	    $(document).on("click", ".delete-cutting", function() {
	    	var rowTotal = $(".tb_cut_cutting tr").length;
	    	var trClick = $(this).attr("cutting");

	    	if ( rowTotal <= 2 ) {
	    		return false;
	    	}

	    	$(".tb_cut_cutting").find("tr.cutting"+trClick).fadeOut("fast", function(){
	    		$(this).remove();

		    	rowTotal = $(".tb_cut_cutting tr").length;

		        for (var i = 0; i < rowTotal; i++) {
		        	$(".tb_cut_cutting").find(".cutting-no").eq(i).text( i+1 );
		        }
	    	});

            // get_list_cutting();
	    });

	    // 재단길이수량 입력 (컷팅비+재단가격)
	    $(document).on("keyup", ".cutting", function() {
	    	$(document).focus();
	    	price_cut_calculate();
	    });

	    // 재단 옵션값 변경시
	    $(document).on("change", ".it_cut_option", function(e) {
	        var val = $(this).val();

	        if ( val === "" ) {
		        $(".cutting_options").css("display", "none");
		        $("#sit_cut_cutting").css("display", "none");
			}
			else {
				$(".cutting_options").css("display", "block");
				$("#sit_cut_cutting").css("display", "block");
			}

			// 컷팅입력 필드 삭제
			var $rowTr = $(".tb_cut_cutting tr");
			$rowTr.each(function(index, el) {
				if ( index > 1 ) {
					el.remove();
				}
			});
			// 1개 남은 컷팅입력 필드 값
			$(".cutting_height").val("");
			$(".cutting_count").val("");
			$(".cutting_amount").val("");
	    });

	    // 재단(길이(자투이 없음)) 상품 전체 가격계산
		function price_cutting_calculate() {
		    var it_price = parseInt($("input#it_price").val());

            if ( typeof it_sale_amount === "number" ) {
                it_price = it_sale_amount;
            }

		    if( isNaN(it_price) ) {
                return;
            }

		    var io_qty = []; // 재단 옵션별 수량
		    var total = 0;
		    var qty = 0;
		    var io_opt_price = []; // 재단 옵션비용
		    var io_cutting_price = 0; // 재단 컷팅비용

		    $("input[name^=io_qty]").each(function() {// 재단 옵션별 수량
		        // io_qty = parseInt( $(this).val() );
		        io_qty.push( parseInt( $(this).val() ) );
		    });

		    $("input[name^=io_opt_price]").each(function() {// 재단 옵션비용
		        // io_opt_price = parseInt( $(this).val() );
		        io_opt_price.push( parseInt( $(this).val() ) );
		    });

		    $("input[name^=io_cutting_price]").each(function() {// 재단 직각컷팅비용
                io_cutting_price += parseInt( $(this).val() );
		    });

		    for (var c = 0; c < io_qty.length; c++) {
		    	total += (it_price + io_opt_price[c]) * io_qty[c];
		    	qty += io_qty[c];
		    }

	        total += io_cutting_price;

	        $("#ct_tot_qty").val(qty);
		    $("#sit_tot_price").empty().html("<span>총 금액 </span> <strong>"+ number_format(String(total)) +"</strong>원");
		}

	    // 재단(길이(자투이 없음), 판재) 상품 전체 가격계산
		function price_cutting_unit() {
		    var it_price = parseInt($("input#it_price").val());

		    if ( typeof it_sale_amount === "number" ) {
                it_price = it_sale_amount;
            }

		    if ( isNaN(it_price) ) {
                return;
            }

		    var io_qty = []; // 재단 옵션별 수량
		    var total = 0;
		    var qty = 0;
		    var io_opt_price = []; // 재단 옵션비용
		    var io_cut_price = 0; // 재단 컷팅비용
		    var io_cutting_price = []; // 재단 비용

		    $("input[name^=io_qty]").each(function() {// 재단 옵션별 수량
		        // io_qty = parseInt( $(this).val() );
		        io_qty.push( parseInt( $(this).val() ) );
		    });

		    $("input[name^=io_opt_price]").each(function() {// 재단 옵션비용
		        io_opt_price.push( parseInt( $(this).val() ) );
		    });

		    $("input[name^=io_cutting_price]").each(function() {// 재단 옵션비용
		        io_cutting_price.push( parseInt( $(this).val() ) );
		    });

		    $("input[name^=io_cut_price]").each(function() {// 재단 직각컷팅비용
		        io_cut_price += parseInt( $(this).val() );
		    });

		    for (var c = 0; c < io_qty.length; c++) {
		    	total += (io_cutting_price[c] + io_opt_price[c]) * io_qty[c];
		    	qty += io_qty[c];
		    }

	        total += io_cut_price;

	        $("#ct_tot_qty").val(qty);
		    $("#sit_tot_price").empty().html("<span>총 금액 </span> <strong>"+ number_format(String(total)) +"</strong>원");
		}

		// 재단(자투리 있음) 컷팅길이, 수량 = 컷팅비, 자투리, 수량, 총금액 계산하기
		function calculate_cutting_length() {
			//__재단길이 컷팅계산__//
			var qtyCutting 		 = 0; // 재단갯수
			var cuttingCut       = 0; // 재단컷팅비용
			var cuttingChk 		 = false; // 재단갯수
			var saveCuttingChk   = true; // 재단갯수 없는 경우 재단 DB 저장하지 않음
			var cutting          = ""; // +길이,수량,컷팅비+길이,수량,컷팅비=|자투리^갯수|자투리^갯수
			var array_jatturi 	 = []; // 자투리 입력
			var array_width 	 = []; // 컷팅길이
			var array_cut        = []; // 수량
			var array_cut_amount = []; // 직각컷팅비

			if ( typeof it_cut_amount === "number" )
				cuttingCut = it_cut_amount;
			else
				cuttingCut = cut_amount;

			$(".cutting_width").each(function(){
				array_width.push( $(this).val() ); // 재단길이
			});

			$(".cutting_count").each(function(){
				array_cut.push( $(this).val() ); // 수량
				if ( !$(this).val() ) // 수량이 없는 경우
					saveCuttingChk = false;
			});

			var w_width 	 = 0;
			var w_cut 		 = 0;
			var w_cut_amount = 0; // 컷팅길이별 직각컷팅비
			var t_cut_amount = 0; // 직각컷팅비 총합

			for (var h = 0; h < array_width.length; h++) {
				w_width 	 = parseInt( array_width[h] ) + cut_lose;
				w_cut 		 = parseInt( array_cut[h] );
				w_cut_amount = 0; // 컷팅길이별 직각컷팅비
				t_cut_amount = 0; // 직각컷팅비 총합

				if ( w_width < it_min_width || !array_width[h] || !array_cut[h] ) // 재단길이, 수량이 없는 경우
					continue;

				array_jatturi.sort();

				if ( w_width >= it_min_width && w_width < it_max_width ) {
					// 컷팅길이 최소, 최대사이
					cuttingChk = true;

					for (var c = 0; c < w_cut; c++) { // 컷팅수량만큼 반복
						var jatturi_count = array_jatturi.length;
						var wood_cut_use  = false; // 재단컷팅 체크

						if ( jatturi_count === 0 ) {
							array_jatturi.push( it_max_width ); // 자투리에 1개의 재단 저장
							qtyCutting++; // 추가 재단숫자
							jatturi_count = array_jatturi.length;
						}

						for (var j = 0; j < jatturi_count; j++) {
							var jatturi_height = parseInt( array_jatturi[j] ); // 배열안 자투리

							if ( jatturi_height >= w_width ) {
								array_jatturi.splice( $.inArray( jatturi_height, array_jatturi ) ,1 ); // 사용된 자투리 삭제

								if ( ( jatturi_height - w_width ) > 0 ) {
									array_jatturi.push( jatturi_height - w_width ); // 남은 자투리 저장
									w_cut_amount += cuttingCut; // 재단커팅비 추가
								}

								wood_cut_use = true;
							}

							if ( wood_cut_use ) {
								j = jatturi_count;
							}
						}

						if ( !wood_cut_use ){
							// 새로운 재단추가
							if ( ( it_max_width - w_width ) > 0 ) {;
								array_jatturi.push( it_max_width - w_width ); // 남은 자투리 저장
								w_cut_amount += cuttingCut; // 재단커팅비 추가
								qtyCutting++; // 추가 재단숫자
							}
						}
					}

					array_cut_amount.push( w_cut_amount ); // 재단커팅비 입력

					cutting += "+"+array_width[h]+","+array_cut[h]+","+w_cut_amount; // +길이,수량,컷팅비
				}
				else if ( ( w_width - cut_lose ) <= it_max_width ) {
					// 컷팅길이 재단최대길이와 같은 경우
					for (var c = 0; c < w_cut; c++) { // 컷팅수량만큼 반복
						w_cut_amount += 0; // 재단커팅비 추가
						qtyCutting++; // 추가 재단숫자
					}

					array_cut_amount.push( w_cut_amount ); // 재단커팅비 입력
					cuttingChk = true;
					cutting += "+"+array_width[h]+","+array_cut[h]+","+w_cut_amount; // +길이,수량,컷팅비
				}
				else {
					// 컷팅길이 최소길이, 최대길이 벗어남
					cuttingChk = false;
					return;
				}
			};


			if ( cuttingChk === true ) {
				var cnt = 0;

				$(".cutting_amount").each(function(){
					$(this).val( array_cut_amount[cnt] ); // 재단커팅비 적용
					t_cut_amount += parseInt( array_cut_amount[cnt] ); // 총 컷팅비
					cnt++;
				});

				// +길이,수량,컷팅비+길이,수량,컷팅비=|자투리^갯수|자투리^갯수
				if ( it_cut_wood_cut_use = "1" && array_jatturi.length > 0 ) {
					array_jatturi.sort( descending );
					// cutting += "=" + array_jatturi.join(","); // |자투리^갯수|자투리^갯수
					$("#cut_cutting_jatturi").empty().html( "자투리: " + array_jatturi.join("mm, ") + "mm");
					$(".cut_jatturi").val( $("#cut_cutting_jatturi").html() );
				}

				$(".cutting_qty").val( qtyCutting );
				$("input#cut_price").val( t_cut_amount );

				var $el_option = $("select.it_cut_option");
				var io_id = ""; // 옵션
				var price = 0;
			    $el_option.each(function(index) {
			    	var info = $(this).val().split(",");
			        price += parseInt( info[1] );
			        io_id += info[0];
			    });
			    $("#cut_option").val(io_id);
			    $("#opt_price").val(price);

				if ( saveCuttingChk === true ) {
					// save_cutting(); // 수량이 있는 경우 재단 자동저장
				}
			}

		}

		function descending( a, b ) {
		    return b - a;
		}

        //__재단판재 컷팅계산__//
		function calculate_cutting_board() {
			var cuttingCut       = it_cut_amount; // 재단컷팅비용
			var cuttingChk 		 = false; // 재단갯수
			var saveWoodenChk    = true; // 재단갯수 없는 경우 재단 DB 저장하지 않음
			var array_width 	 = []; // 컷팅가로길이
			var array_height 	 = []; // 컷팅세로길이
			var array_cut        = []; // 수량
			var array_cut_amount = []; // 직각컷팅비

			$(".cutting_qty").val(1);

			$(".cutting_width").each(function(){
				array_width.push( parseInt( $(this).val() ) + cut_lose ); // 재단길이 가로
			});
			$(".cutting_height").each(function(){
				array_height.push( parseInt( $(this).val() ) + cut_lose ); // 재단길이 세로
			});

			$(".cutting_count").each(function(){
				array_cut.push( $(this).val() ); // 수량
				if ( !$(this).val() ) // 수량이 없는 경우
					saveWoodenChk = false;
			});

			var w_width 	 = 0;
			var w_height 	 = 0;
			var w_cut 	     = 0;
			var w_cut_amount = 0; // 컷팅길이별 직각컷팅비
			var t_cut_amount = 0; // 직각컷팅비 총합
			var tot_amount   = 0; // 총 재단+컷팅손실분 비용
			var unit_amount  = Math.ceil( it_sale_amount ); // 판매단위당 가격

			for (var h = 0; h < array_height.length; h++) {
				w_width 	 = parseInt( array_width[h] );
				w_height 	 = parseInt( array_height[h] );
				w_cut 		 = parseInt( array_cut[h] );
				w_cut_amount = 0; // 컷팅길이별 직각컷팅비

                if ( w_width > it_max_width || w_height > it_max_height ) {
                    alert("최대길이를 초과했습니다.");
                    return false;
                }

				if ( ( (w_height < it_min_height || !array_height[h] ) && ( w_width < it_min_height || !array_width[h] ) ) || !array_cut[h] ) { // 재단길이, 수량이 없는 경우
					array_cut_amount.push( w_cut_amount ); // 재단커팅비 입력
				}
				else {
					w_cut_amount = cuttingCut * w_cut;
					array_cut_amount.push( w_cut_amount ); // 재단커팅비 입력
				}

				cuttingChk = true;
			}


			if ( cuttingChk === true ) {
				var cnt   = 0;
				var io_id = ""; // 옵션정보
				var price = 0; // 옵션 추가비용

				var $el_option = $("select.it_cut_option");

			    $el_option.each(function(index) {
			    	var info = $(this).val().split(",");
			        price += parseInt( info[1] );
			        io_id += info[0];
			    });

				$(".cutting_amount").each(function(){
					$(this).val( array_cut_amount[cnt] ); // 재단커팅비 적용
					t_cut_amount += parseInt( array_cut_amount[cnt] ); // 총 컷팅비

					w_height = parseInt( array_height[cnt] );
					w_width  = parseInt( array_width[cnt] );
					w_cut    = parseInt( array_cut[cnt] );
					tot_amount += getCeil( parseInt( w_height * w_width * (unit_amount + price) ) / ( it_sale_unit * it_sale_unit ) ) * w_cut;

					cnt++;
				});

				$("input#cut_price").val( t_cut_amount );
				if ( tot_amount ) {
                    $("input[name=it_cutting_total_price]").val(tot_amount);
                }

			    $("#cut_option").val(io_id);
			    $("#opt_price").val(0);

				if ( saveWoodenChk === true ) {
					// save_cutting(); // 수량이 있는 경우 재단 자동저장
				}
			}
		}

        //__재단자당(자투리 없음) 컷팅계산__//
		function calculate_cutting_unit() {
			var cuttingCut       = it_cut_amount; // 재단컷팅비용
			var cuttingChk 		 = false; // 재단갯수
			var saveWoodenChk    = true; // 재단갯수 없는 경우 재단 DB 저장하지 않음
			var array_width 	 = []; // 컷팅길이
			var array_cut        = []; // 수량
			var array_cut_amount = []; // 직각컷팅비

			$(".cutting_qty").val(1);

			$(".cutting_width").each(function(){
				array_width.push( parseInt( $(this).val() ) + cut_lose ); // 재단길이
			});

			$(".cutting_count").each(function(){
				array_cut.push( $(this).val() ); // 수량
				if ( ! $(this).val() ) // 수량이 없는 경우
					saveWoodenChk = false;
			});

			var w_width 	 = 0;
			var w_cut 	     = 0;
			var w_cut_amount = 0; // 컷팅길이별 직각컷팅비
			var t_cut_amount = 0; // 직각컷팅비 총합
			var tot_amount   = 0; // 총 재단+컷팅손실분 비용
			var unit_amount  = Math.ceil( it_sale_amount ); // 판매단위당 가격

			for (var h = 0; h < array_width.length; h++) {
				w_width 	 = parseInt( array_width[h] );
				w_cut 		 = parseInt( array_cut[h] );
				w_cut_amount = 0; // 컷팅길이별 직각컷팅비

				if ( w_width > it_max_width ) {
					alert("최대길이를 초과했습니다.");
					return false;
				}

				if ( w_width < it_min_height || !array_width[h] || !array_cut[h] ) { // 재단길이, 수량이 없는 경우
					array_cut_amount.push( w_cut_amount ); // 재단커팅비 입력
				}
				else {
					w_cut_amount = cuttingCut * w_cut;
					array_cut_amount.push( w_cut_amount ); // 재단커팅비 입력
				}

				cuttingChk = true;
			}


			if ( cuttingChk === true ) {
				var cnt   = 0;
				var io_id = ""; // 옵션정보
				var price = 0; // 옵션 추가비용

				var $el_option = $("select.it_cut_option");

			    $el_option.each(function(index) {
			    	var info = $(this).val().split(",");
			        price += parseInt( info[1] );
			        io_id += info[0];
			    });

				$(".cutting_amount").each(function(){
					$(this).val( array_cut_amount[cnt] ); // 재단커팅비 적용
					t_cut_amount += parseInt( array_cut_amount[cnt] ); // 총 컷팅비

					w_width    = parseInt( array_width[cnt] );
					w_cut      = parseInt( array_cut[cnt] );
					tot_amount += getCeil( Math.ceil( parseInt( w_width * (unit_amount + price) ) / it_sale_unit ) ) * w_cut;

					cnt++;
				});

				$("input#cut_price").val( t_cut_amount );
				if ( tot_amount ) {
                    $("input[name=it_cutting_total_price]").val(tot_amount);
                }

			    $("#cut_option").val(io_id);
			    $("#opt_price").val(0);

				if ( saveWoodenChk === true ) {
					// save_cutting(); // 수량이 있는 경우 재단 자동저장
				}
			}
		}

		/**
		 * 최소, 최대 구매 길이 체크(검토중)
		 */
		function cuttingLengthCheck() {
            var array_width  = []; // 컷팅가로길이
            var array_height = []; // 컷팅세로길이
            var w_width 	 = 0;
            var w_height 	 = 0;
            var height_use 	 = false;

            $(".cutting_width").each(function(){
            	if ( $(this).val() ) {
                    array_width.push(parseInt($(this).val()) + cut_lose); // 재단길이 가로
                }
            });

            if ( ! array_width.length ) {
            	alert("재단길이를 입력하세요!");
            	return false;
			}

            if ( $(".cutting_height").length > 0 ) {
				height_use = true;

				$(".cutting_height").each(function(){
                    if ( $(this).val() ) {
                        array_height.push(parseInt($(this).val()) + cut_lose); // 재단길이 세로
                    }
				});

                if ( ! array_height.length ) {
                    alert("재단길이를 입력하세요!");
                    return false;
				}
            }

            for (var c = 0; c < array_width.length; c++) {
                w_width  = parseInt( array_width[c] );
                w_height = parseInt( array_height[c] );

                if ( w_width > it_max_width ) {
                    alert("최대 구매 길이를 확인하세요!");
                    return false;
                }
                if ( w_width < it_min_width ) {
                    alert("최소 구매 길이를 확인하세요!");
                    return false;
                }

				console.log( w_width +"="+ it_min_width +"+"+ it_max_width );
                if ( height_use === true ) {
                    if ( w_height > it_max_height ) {
                        alert("최대 구매 길이를 확인하세요!");
                        return false;
                    }
                    if ( w_height < it_min_height ) {
                        alert("최소 구매 길이를 확인하세요!");
                        return false;
                    }
				}
            }

            return true;
        }

		function calculate_cutting_custom() {
			//__재단맞춤사이즈 컷팅계산__//
		}

		function getCeil( val ) {
            var no;
            no = Math.ceil( val / 10 ) * 10;
            return no;
        }

		// 재단 컷팅 가격 계산
	    function price_cut_calculate() {
	    	switch ( it_cut_opt ) {
	    		case 1:
                    //__자당__//
                    calculate_cutting_unit();
	    		break;

	    		case 2:
	    			//__판재__//
	    			calculate_cutting_board();
	    		break;

	    		case 3:
	    			//__길이__//
                    calculate_cutting_length();
	    		break;

	    		case 4:
	    			//__맞춤사이즈__//
	    			calculate_cutting_custom();
	    		break;
	    	}

	    	// price_calculate();
	    }

		price_cut_calculate(); // 재단 컷팅길이, 수량으로 = 컷팅비, 자투리, 수량, 총금액 계산하기
		get_cutting_list(); // 재단 목록 출력하기
	});


})(jQuery);