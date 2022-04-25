	<div class="modal" id="modalSubscribe">
		<div class="modal-dialog" style="max-width:830px; margin-top:20px;">
			<div class="modal-content">
				<div class="modal-body">
					<div class="modal-msg1">
						<div class="h3 mb30">구독해지</div>
						<div class="modal-txt1 bold mb35">배송주기가 짧아, 제품이 많이 남았나요?</div>
					</div>
					<div class="subscribe-date">
						<button class="btn" onclick="javascript:fnDelayDay();"><img src="/res/img/mypage/ico_cal_large.png"><span>다음 결제일을 미룰게요.</span></button>
					<div class="no">아니에요. 그만 받아볼래요.</div>
					</div>
				</div>
				<div class="modal-footer text-center">
					<button class="btn btn-type2 " style="width:280px" data-dismiss="modal">취소하기</button>
					<button class="btn btn-type1 " style="width:280px" onclick="javascript:fnCancelSubscribe(); return false;">해지하기</button>
				</div>
			</div>
		</div>
	</div>

<script>
function fnDelayDay() {
	$('#modalSubscribe').modal('hide');
	if($('#new_date').val() == '') fnPopupShowDay($('#org_date').val());
	else fnPopupShowDay($('#new_date').val());
}

</script>