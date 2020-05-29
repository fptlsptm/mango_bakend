<style>
.box{width:400px;}
.btn-success{margin-top:15px;width:100%;}
.form-control{margin-top:5px;}
</style>
<div class="box">
	<form>
		<span class="mr10">
	    다운받을 기간을 선택하세요
	    <input type="text" class="form-control input-small datepicker " name="start_date" readonly="readonly"> ~
	    <input type="text" class="form-control input-small datepicker" name="end_date"  readonly="readonly">
		</span>
		<button type="button" class="btn btn-success " onclick="fdate_submit('d');">엑셀다운</button>
	</form>
</div>
