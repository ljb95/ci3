<style>
#modalTerms p span {line-height:25px !important;};
</style>
	<div class="modal fade" role="dialog" aria-labelledby="introHeader" aria-hidden="true" tabindex="-1" id="modalTerms" data-backdrop="static">
		<div class="modal-dialog" style="max-width:1200px; margin-top:3%; ">
			<div class="modal-content" style="height:100%; position:relative;">
            <i class="fas fa-times" style="font-size:25px; color:#000; top:3%; right:3%; position:absolute; cursor:pointer;" data-dismiss="modal"></i>
				<div class="modal-body">
					<div class="modal-msg1">
						<div class="h3 mb30">이용약관</div>
						<div style="max-height:600px; overflow:auto; font-size:14px; line-height:20px; text-align:left;" id="terms_desc">


						</div>
					</div>
				</div>
				<div class="modal-footer text-center">
					<button class="btn btn-type2 w280" data-dismiss="modal">확인</button>
				</div>
			</div>
		</div>
	</div>

<script>
$(document).ready(function(e) {
    $('#modalTerms').on('show.bs.modal', function() {
		$.ajax({
			type:'POST',
			url:'/common/ajaxTerms',
			data : {type: 'terms'},
			dataType:"json",
			success:function(res){
				$('#terms_desc').html(res.data);
			},
			error:function(data){
				alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
			}
		});
		
	});
});
</script>