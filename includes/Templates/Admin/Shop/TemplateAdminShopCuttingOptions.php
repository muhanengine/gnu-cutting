<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: AM 11:48
 */

?>

<!-- 길이: 각목, 파이프 등 길이별 주문 이용(자투리 있음)\n판재: 판재 가로,세로 길이별 주문 이용 (자투리 사용 불가)\n자당: 판재 가로,세로 길이별 주문 이용 (자투리 사용하지 않음)\n맞춤사이즈: 맞춤사이즈 가로,세로,높이 길이별 주문 이용 -->
<tr class="ht">
	<th>재단(옵션)</th>
	<td colspan="2">
		<?php echo help("길이: 각목, 파이프 등 주문 이용\n길이(자투리): 각목, 파이프 등 주문시 자르고 남은 나머지(자투리)도 같이 배송\n판재: 판재 가로,세로 길이별 주문 이용 (자투리 사용 불가)"); ?>
        <input type="radio" name="it_cut_opt" class="it_cut_opt" value="1"<?php echo ( $it['it_cut_opt'] == 1 ? ' checked' : '' ); ?> title="길이" /> 길이
        &nbsp;&nbsp;
        <input type="radio" name="it_cut_opt" class="it_cut_opt cutting_input" value="3"<?php echo ( $it['it_cut_opt'] == 3 ? ' checked' : '' ); ?> title="자투리" /> 길이(자투리)
		&nbsp;&nbsp;
		<input type="radio" name="it_cut_opt" class="it_cut_opt cutting_input" value="2"<?php echo ( $it['it_cut_opt'] == 2 ? ' checked' : '' ); ?> title="판재" /> 판재
        &nbsp;&nbsp;
		<input type="radio" name="it_cut_opt" class="it_cut_opt cutting_input" value="0"<?php echo ( $it['it_cut_opt'] == 0 ? ' checked' : '' ); ?> title="옵션 사용 안함" /> 옵션 사용 안함
	</td>
</tr>
<!--<tr class="cut_lumber jatturi_use">
	<th>재단 자투리</th>
	<td colspan="2">
		<input type="checkbox" name="it_cut_jatturi_use" class="it_cut_jatturi_use cutting_input" value="1"<?php /*echo ( $it['it_cut_jatturi_use'] == 1 ? ' checked' : '' ); */?> title="자투리 사용" /> 자투리 사용
	</td>
</tr>-->
<tr class="cut_lumber sale_unit">
	<th>재단 판매단위</th>
	<td colspan="2">
		<?php echo help("기본판매단위 길이(mm) 가로, 세로 공통 사용\n판매 가격 = (구매길이 + 재단 손실길이(두께)) × 판매단위 금액 / 판매단위 길이"); ?>
        금액: <input type="text" class="frm_input cutting_input" name="it_sale_amount" value="<?php echo $it['it_sale_amount']; ?>" numeric="" size="10" title="기분금액(노출 고정금액)"> 원,
        길이: <input type="text" class="frm_input cutting_input" name="it_sale_unit" value="<?php echo $it['it_sale_unit']; ?>" numeric="" size="10" title="판재(자당) 판매단위"> mm
	</td>
</tr>
<tr class="cut_lumber cut_amount">
	<th>재단 비용</th>
	<td colspan="2">
		<?php echo help("재단1회 컷팅 추가비용 미입력시 쇼핑몰설정 금액 ".$default['de_cut_amount']."원 적용"); ?>
        <input type="hidden" id="de_cut_amount" value="<?=$default['de_cut_amount']?>" />
		<input type="text" class="frm_input cutting_input" name="it_cut_amount" value="<?php echo $it['it_cut_amount']; ?>" numeric="" title="원" size="10"> 원
		&nbsp;&nbsp;&nbsp;<input type="checkbox" name="it_cut_amount_basic" class="it_cut_amount_basic cutting_input" value="1"<?php echo ( $it['it_cut_amount_basic'] == 1 ? ' checked' : '' ); ?> title="기본설정 금액 사용" /> 쇼핑몰설정 금액 사용
	</td>
</tr>
<tr class="cut_lumber cut_lose">
	<th>재단 손실길이(두께)</th>
	<td colspan="2">
		<?php echo help("재단1회 컷팅 손실길이(두께) 미입력시 쇼핑몰설정 손실길이 ".$default['de_cut_lose']."mm 적용"); ?>
        <input type="hidden" id="de_cut_lose" value="<?=$default['de_cut_lose']?>" />
		<input type="text" class="frm_input cutting_input" name="it_cut_lose" value="<?php echo $it['it_cut_lose']; ?>" numeric="" title="로스값" size="10"> mm
		&nbsp;&nbsp;&nbsp;<input type="checkbox" name="it_cut_lose_basic" class="it_cut_lose_basic cutting_input" value="1"<?php echo ( $it['it_cut_lose_basic'] == 1 ? ' checked' : '' ); ?> title="기본설정 손실길이 사용" /> 쇼핑몰설정 손실길이(두께) 사용
	</td>
</tr>
<tr class="cut_lumber cut_width">
	<th>재단 가로길이</th>
	<td colspan="2">
        최소 구매길이: <input type="text" class="frm_input cutting_input" name="it_min_width" value="<?php echo $it['it_min_width']; ?>" numeric="" size="10" title="최소 구매길이"> mm,
		최대 길이: <input type="text" class="frm_input cutting_input" name="it_max_width" value="<?php echo $it['it_max_width']; ?>" numeric="" size="10" title="최대 가로길이"> mm
	</td>
</tr>
<tr class="cut_lumber cut_height">
	<th>재단 세로길이</th>
	<td colspan="2">
        최소 구매길이: <input type="text" class="frm_input cutting_input" name="it_min_height" value="<?php echo $it['it_min_height']; ?>" numeric="" size="10" title="최소 구매길이"> mm,
		최대 길이: <input type="text" class="frm_input cutting_input" name="it_max_height" value="<?php echo $it['it_max_height']; ?>" numeric="" size="10" title="최대 세로길이"> mm
	</td>
</tr>
<!--tr class="cut_lumber custom_size">
	<th>최소구매길이</th>
	<td colspan="2">
		(가로): <input type="text" class="frm_input" name="it_height_min" value="<?php echo $it['it_height_min']; ?>" size="5" title="최소구매 가로"> mm&nbsp;&nbsp;
		(세로): <input type="text" class="frm_input" name="it_width_min" value="<?php echo $it['it_width_min']; ?>" size="5" title="최소구매 세로"> mm&nbsp;&nbsp;
		(높이): <input type="text" class="frm_input" name="it_high_min" value="<?php echo $it['it_high_min']; ?>" size="5" title="최소구매 높이"> mm
	</td>
</tr>
<tr class="cut_lumber unit_amount">
	<th>최대길이(100mm단위금액)</th>
	<td colspan="2">
		(가로): <input type="text" class="frm_input" name="it_height_max" value="<?php echo $it['it_height_max']; ?>" size="5" title="최대길이 가로"> mm × (세로): <input type="text" class="frm_input" name="it_width_max" value="<?php echo $it['it_width_max']; ?>" size="5" title="최대길이 세로"> mm&nbsp;&nbsp; = &nbsp;&nbsp;(<input type="text" class="frm_input" name="it_unit_amount" value="<?php echo $it['it_unit_amount']; ?>" numeric="" size="5" title="최대길이 금액">원)&nbsp;&nbsp;&nbsp;&nbsp;
		(높이): <input type="text" class="frm_input" name="it_high_max" value="<?php echo $it['it_high_max']; ?>" size="5" title="최대길이 높이"> mm&nbsp;&nbsp; = &nbsp;&nbsp;(<input type="text" class="frm_input" name="it_high_unit_amount" value="<?php echo $it['it_high_unit_amount']; ?>" numeric="" size="5" title="최대길이 금액"> 원)
	</td>
</tr -->
