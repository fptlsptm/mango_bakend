<style>
.box-table{padding-top:10px;}
.header{margin-bottom: 10px ;}
.input-group .input-group-btn{width: 50px !important;}
.pull-left{width:100px;}
</style>
<div class="box">
	<div class="box-table">
		<?php
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="box-table-header">
        <form name="fsearch" id="fsearch" action="<?php echo current_full_url(); ?>" method="get">
      		<div class="box-search">
  					<select class="form-control" name="sfield" >
  						<?php echo element('search_option', $view); ?>
  					</select>
  					<div class="input-group">
  						<input type="text" class="form-control" name="skeyword" value="<?php echo html_escape(element('skeyword', $view)); ?>" placeholder="검색어를 적어주세요" />
  						<span class="input-group-btn">
  							<button class="btn btn-default btn-sm" name="search_submit" type="submit">검색!</button>
  						</span>
      			</div>
      		</div>
      	</form>
			</div>
			<div class="row">전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
			<div class="table-responsive">
				<table class="table table-hover table-striped table-bordered">
					<thead>
						<tr>
							<th><a href="<?php echo element('mem_id', element('sort', $view)); ?>">번호</a></th>
							<th><a href="<?php echo element('mem_userid', element('sort', $view)); ?>">아이디</a></th>


							<th><a href="<?php echo element('mem_email', element('sort', $view)); ?>">이메일</a></th>
							<?php if ($this->cbconfig->item('use_selfcert')) { ?>
								<th>본인인증</th>
							<?php } ?>
							<?php if ($this->cbconfig->item('use_sociallogin')) { ?>
								<th>소셜연동</th>
							<?php } ?>
							<th><a href="<?php echo element('mem_point', element('sort', $view)); ?>">포인트</a></th>
							<th><?php echo $this->cbconfig->item('deposit_name') ? html_escape($this->cbconfig->item('deposit_name')) : '예치금'; ?></th>
							<th><a href="<?php echo element('mem_register_datetime', element('sort', $view)); ?>">가입일</a></th>
							<th>승인</th>
							<th>상세정보</th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', element('data', $view))) {
						foreach (element('list', element('data', $view)) as $result) {
					?>
						<tr>
							<td><?php echo number_format(element('num', $result)); ?></td>
							<td><?php echo html_escape(element('mem_userid', $result)); ?></td>


							<td><?php echo html_escape(element('mem_email', $result)); ?></td>
							<?php if ($this->cbconfig->item('use_selfcert')) { ?>
								<td>
									<?php
									echo (element('selfcert_type', element('meta', $result)) === 'phone') ? '<span class="label label-success">휴대폰</span>' : '';
									echo (element('selfcert_type', element('meta', $result)) === 'ipin') ? '<span class="label label-success">IPIN</span>' : '';
									echo is_adult(element('selfcert_birthday', element('meta', $result))) ? '<span class="label label-danger">성인</span>' : '';
									?>
								</td>
							<?php } ?>
							<?php if ($this->cbconfig->item('use_sociallogin')) { ?>
								<td>
									<?php if ($this->cbconfig->item('use_sociallogin_facebook') && element('facebook_id', element('social', $result))) { ?>
										<a href="javascript:;" onClick="social_open('facebook', '<?php echo element('mem_id', $result); ?>');"><img src="<?php echo base_url('assets/images/social_facebook.png'); ?>" width="15" height="15" alt="페이스북 로그인" title="페이스북 로그인" /></a>
									<?php } ?>
									<?php if ($this->cbconfig->item('use_sociallogin_twitter') && element('twitter_id', element('social', $result))) { ?>
										<a href="javascript:;" onClick="social_open('twitter', '<?php echo element('mem_id', $result); ?>');"><img src="<?php echo base_url('assets/images/social_twitter.png'); ?>" width="15" height="15" alt="트위터 로그인" title="트위터 로그인" /></a>
									<?php } ?>
									<?php if ($this->cbconfig->item('use_sociallogin_google') && element('google_id', element('social', $result))) { ?>
										<a href="javascript:;" onClick="social_open('google', '<?php echo element('mem_id', $result); ?>');"><img src="<?php echo base_url('assets/images/social_google.png'); ?>" width="15" height="15" alt="구글 로그인" title="구글 로그인" /></a>
									<?php } ?>
									<?php if ($this->cbconfig->item('use_sociallogin_naver') && element('naver_id', element('social', $result))) { ?>
										<a href="javascript:;" onClick="social_open('naver', '<?php echo element('mem_id', $result); ?>');"><img src="<?php echo base_url('assets/images/social_naver.png'); ?>" width="15" height="15" alt="네이버 로그인" title="네이버 로그인" /></a>
									<?php } ?>
									<?php if ($this->cbconfig->item('use_sociallogin_kakao') && element('kakao_id', element('social', $result))) { ?>
										<a href="javascript:;" onClick="social_open('kakao', '<?php echo element('mem_id', $result); ?>');"><img src="<?php echo base_url('assets/images/social_kakao.png'); ?>" width="15" height="15" alt="카카오 로그인" title="카카오 로그인" /></a>
									<?php } ?>
								</td>
							<?php } ?>
							<td class="text-right"><?php echo number_format(element('mem_point', $result)); ?></td>
							<td class="text-right"><?php echo number_format((int) element('total_deposit', element('meta', $result))); ?></td>
							<td><?php echo display_datetime(element('mem_register_datetime', $result), 'full'); ?></td>
							<td><?php echo element('mem_denied', $result) ? '<span class="label label-danger">차단</span>' : '승인'; ?></td>
							<td><a href="/admin/main/info/<?=element('mem_userid', $result)?>" class="btn btn-outline btn-default btn-xs">상세정보</a></td>

						</tr>
					<?php
						}
					}
					if ( ! element('list', element('data', $view))) {
					?>
						<tr>
							<td colspan="17" class="nopost">자료가 없습니다</td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
			<div class="box-info">
				<?php echo element('paging', $view); ?>
				<div class="pull-left ml20"><?php echo admin_listnum_selectbox();?></div>
				<?php echo $buttons; ?>
			</div>
		<?php echo form_close(); ?>
	</div>

</div>

<script type="text/javascript">
//<![CDATA[
function social_open(stype, mem_id) {
	var pop_url = cb_admin_url + '/member/members/socialinfo/' + stype + '/' + mem_id;
	window.open(pop_url, 'win_socialinfo', 'left=100,top=100,width=730,height=500,scrollbars=1');
	return false;
}

$(document).on('click', '#export_to_excel', function(){
	exporturl = '<?php echo admin_url($this->pagedir . '/excel' . '?' . $this->input->server('QUERY_STRING', null, '')); ?>';
	document.location.href = exporturl;
})

//]]>
</script>
