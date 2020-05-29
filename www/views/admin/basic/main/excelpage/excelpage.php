<?
	$date2 = date("Y-m-d");
	$timestamp = strtotime("{$now} -1 month");
  $date1 = date("Y-m-d", $timestamp);
?>

<style>
.box{width:400px;}
.btn-success{margin-top:15px;width:100%;}
.form-control{margin-top:5px;}
</style>
<div class="box">
	<?= form_open("/admin/main/excelprint");?>
		<span class="mr10">
	    다운받을 기간을 선택하세요
	    <input type="text" class="form-control input-small datepicker" value="<?=$date1?>" name="date1" readonly="readonly" required> ~
	    <input type="text" class="form-control input-small datepicker" value="<?=$date2?>" name="date2"  readonly="readonly" required>
		</span>
		<button type="submit" class="btn btn-success ">엑셀다운</button>
	</form>
</div>
