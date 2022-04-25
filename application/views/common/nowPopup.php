	<div class="modal" id="modalNow">
		<div class="modal-dialog" style="max-width:830px; margin-top:20px;">
			<div class="modal-content">
				<div class="modal-body">
					<div class="modal-msg1">
						<div class="h3 mb30">즉시 당겨받기 </div>
						<div class="modal-txt1 bold mb50" id="org_set_date"></div>
					</div>
				</div>
				<div class="modal-footer text-center">
					<button class="btn btn-type2 " style="width:280px" onclick="javascript:fnSetNowCancel();">취소하기</button>
					<button class="btn btn-type1 " style="width:280px" onclick="javascript:fnSetNow();">당겨받기</button>
				</div>
			</div>
		</div>
	</div>
<script>
function fnShowSetNow(day, product, price) {
	var date = new Date();
	date.setDate(date.getDate() + 1);
	var yy = date.getFullYear();
	var mm = date.getMonth() + 1;
	var dd = date.getDate();
	var now = yy + '년 ' + mm + '월 ' + dd + '일';
	if(day == now) {
		showAlert('error', '가장 최근 날짜 입니다. 더이상 당길 수 없습니다.');
		return;		
	}
	var param = {msg : '즉시 당겨받기', msg2 : day + '날 받으시는 ' + product + '을(를) <br>즉시 당겨 받으시겠습니까? <br> <span style="color:red; font-weight:bold">결제 금액 ' + price + '원은 익일 ' + now + ' 오후 3시 결제됩니다</span>', cancel : '취소하기', confirm : '당겨받기' };
	showConfirm(param, fnSetNow, fnSetNowCancel);
}

</script>