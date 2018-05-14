<?php
/**
 * Created by PhpStorm.
 * User: MuhanEngine
 * Date: 2017. 12. 26.
 * Time: AM 11:48
 */

?>

<tr>
    <th scope="row"><label for="de_cut_amount">기본 커팅비용</label></th>
    <td>
		<?php echo help('1회 컷팅시 추가비용'); ?>
        <input type="text" name="de_cut_amount" value="<?php echo $default['de_cut_amount']; ?>" id="de_cut_amount" class="frm_input cutting_input" size="6">원
    </td>
</tr>
<tr>
    <th scope="row"><label for="de_cut_lose">손실길이(톱날두께)</label></th>
    <td>
		<?php echo help('1회 컷팅시 톱날에 의해 사라지는 길이'); ?>
        <input type="text" name="de_cut_lose" value="<?php echo $default['de_cut_lose']; ?>" id="de_cut_lose" class="frm_input cutting_input" size="6">mm
    </td>
</tr>
