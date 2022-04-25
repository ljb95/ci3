	<div class="modal fade" role="dialog" aria-labelledby="introHeader" aria-hidden="true" tabindex="-1" data-backdrop="static" id="modalUsepoint">
		<div class="modal-dialog" style="max-width:830px; margin-top:100px;">
			<div class="modal-content">
				<div class="modal-body">
					<div class="modal-msg1">
						<div class="h3 mb30">포인트사용</div>
						<div class="baesong-form">
							<div class="mypage-modify">
								<dl>
									<dt>보유 포인트</dt>
									<dd><input type="text" class="inp1 block" id="total_point" value="<?php echo number_format($point['mem_point']); ?>" readonly></dd>
								</dl>
								<dl>
									<dt>사용</dt>
									<dd>
                                    	<input type="text" class="inp1" id="popup_add_point" style="width:70%" oninput="this.value = this.value.replace(/[^0-9]/g, '');" >
                                        <a href="#" class="btn-under ml20" onclick="javascript:fnPopupUseAllPoint(); return false;">전액사용</a>
                                    </dd>
								</dl>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer text-center">
					<button class="btn btn-type2 w280" data-dismiss="modal">취소</button>
					<button class="btn btn-type1 w280" onclick="javascript:fnPopupUsePoint();">확인</button>
				</div>
			</div>
		</div>
	</div>
    
<script>
$(document).ready(function(e) {
	$('#modalUsepoint').on('show.bs.modal', function(){
		$('#popup_add_point').val('');
	});
	
    $('#popup_add_point').on('keyup', function() {
		if($(this).val() == '') return;
		var val = parseInt($(this).val().replace(/[,]/g, ""));
		var total = parseInt($('#org_total_price').val());
		var point = parseInt($('#total_point').val().replace(/[,]/g, ""));

		var max_val = point;
		if(point > total) max_val = total;
		
		if(val > max_val) {
			val = max_val	
		}
    	$(this).val(commify(val));
	});
});

function fnPopupUseAllPoint()
{
	var total = parseInt($('#org_total_price').val());
	var point = parseInt($('#total_point').val().replace(/[,]/g, ""));
	if(total > point) {
		$('#popup_add_point').val(commify(point));
	}
	else {
		$('#popup_add_point').val(commify(total));
	}
}

function fnPopupUsePoint()
{
	var val = parseInt($('#popup_add_point').val().replace(/[,]/g, ""));
	if($('#popup_add_point').val() == '') val = 0;
	
	$('#show_use_point').html((val > 0 ? '-' : '') + commify(val) + 'P');
	$('input[name=use_point]').val(val);
	fnCalcTotalPrice();
	$('#modalUsepoint').modal('hide');
}
</script>