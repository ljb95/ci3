	<div class="modal fade" role="dialog" aria-labelledby="introHeader" aria-hidden="true" tabindex="-1" id="modalCoupon" data-backdrop="static">
		<div class="modal-dialog" style="max-width:830px; margin-top:100px;">
			<div class="modal-content">
				<div class="modal-body">
					<div class="modal-msg1">
						<div class="h3 mb30">쿠폰 정보</div>
						<div class="modal-coupon-info">
							<dl style="display:flex">
								<dt>쿠폰명</dt>
								<dd><strong id="coupon_name" style="padding-left:10px">친구추천 쿠폰</strong></dd>
							</dl>
							<dl style="display:flex">
								<dt>혜택</dt>
								<dd class="blue" id="price_value" style="padding-left:10px">10% 할인 (최대할인금액 50,000원)</dd>
							</dl>
							<dl style="display:flex">
								<dt>유효기간</dt>
								<dd id="use_range" style="padding-left:10px">2020-03-06 ~ 2020-03-16 <br class="mobile">(279일 지남)</dd>
							</dl>
							<dl style="display:flex">
								<dt>사용조건</dt>
								<dd id="use_type" style="padding-left:10px"></dd>
							</dl>
							<dl id="product_list" style="display:flex">
								<dt>사용가능상품</dt>
								<dd style="padding-left:10px"></dd>
							</dl>
						</div>
					</div>
				</div>
				<div class="modal-footer text-center">
					<button class="btn btn-type1 w280" data-dismiss="modal">확인</button>
				</div>
			</div>
		</div>
	</div>

<script>
function fnPopupSetDetail(data) {
	data = data.replace(/(\n|\r\n)/g, '<br>').replace(/\'/gi, "\"");
	var data = JSON.parse(data); 
	$('#coupon_name').html(data.ccp_name);
	var price = '';
	if(data.price_type == '1') {
		price = data.ccp_val + '% 할인';
		if(data.use_max == 'y') {
			price += ' (' + commify(data.max_val) + '원)';	
		}
	}
	else {
		if(data.ccp_type == '3') {
			price = commify(data.ccp_val) + ' 마일리지 지급';
		}
		else {
			price = commify(data.ccp_val) + '원 할인';
		}
	}
	$('#price_value').html(price);
	
	var end_date = data.use_end_date.split('-');
	var now = new Date();
	var end = new Date(end_date[0], (end_date[1] - 1), end_date[2]);
	var range = dateDiff(now, end);
	$('#use_range').html(data.use_start_date + ' ~ ' + data.use_end_date + ' <br class="mobile">(' + range + '일 ' + (now > end ? '지남' : '남음') + ')');
console.log(data);
	if(data.use_min == 'y') {
		$('#use_type').html(commify(data.min_val) + '원 이상 구매시 사용');
	}
	else {
		$('#use_type').html('조건없음');
	}
console.log(data.product_name);
	if(data.product_name == null) {
		$('#product_list').hide();	
	}
	else {
		$('#product_list').show();	
		$('#product_list dd').html(data.product_name);	
	}
}

</script>