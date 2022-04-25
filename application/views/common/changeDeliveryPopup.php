	<div class="modal fade" role="dialog" aria-labelledby="introHeader" aria-hidden="true" tabindex="-1" id="modalChangeDelivery" data-backdrop="static">
		<div class="modal-dialog" style="max-width:830px; margin-top:100px;">
			<div class="modal-content">
				<div class="modal-body">
					<div class="modal-msg1">
						<div class="h3 mb30">배송지 선택</div>
						<div class="baesong-form">
                           	<input type="hidden" id="selected_idx" value="" />
							<div class="mypage-modify" style="max-height:450px; overflow:auto;" id="addr_list">

							</div>
						</div>

						<div class="h3 mb30">배송지 입력</div>
						<div class="baesong-form">
							<div class="mypage-modify">
								<dl>
									<dt>받는분</dt>
									<dd><input type="text" class="inp1" id="add_recipient" style="width:60%"></dd>
								</dl>
								<dl class="hp">
									<dt>휴대폰</dt>
									<dd><input type="text" class="inp1" id="add_phone" style="width:60%" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="11"></dd>
								</dl>
								<dl class="addr">
									<dt>주소</dt>
									<dd>
										<div class="addr-box">
											<div class="inp-box1">
                                            	<input type="text" class="inp1" id="add_zip" value="" readonly> 
                                            	<a href="#" onclick="javascript:execDaumPostcode($('#add_zip'), $('#add_road'), $('#add_jibun') ); return false;" class="btn-under ml20">주소찾기</a>
                                            </div>
											<div class="inp-box2">
                                            	<input type="text" class="inp1 block" id="add_road" value="" readonly>
                                            	<input type="hidden" class="inp1 block" id="add_jibun" >
                                            </div>
											<div class="inp-box2"><input type="text" id="add_addr2" class="inp1 block" value=""></div>
										</div>
									</dd>
								</dl>
								<dl>
									<dt>배송요청</dt>
									<dd><input type="text" class="inp1 block" id="add_memo" ></dd>
								</dl>
							</div>
							<div class="text-center mb40">
								<label><input type="checkbox" id="add_default" class="checkbox" checked><em></em><span>기본 배송지로 지정합니다.</span></label>
							</div>
						</div>

					</div>
				</div>
				<div class="modal-footer text-center">
					<button class="btn btn-type2 w280" data-dismiss="modal">취소</button>
					<button class="btn btn-type1 w280" onclick="javascript:fnPopupSetAddr();">확인</button>
				</div>
			</div>
		</div>
	</div>
<style>
#addr_list li {padding:20px; border: 1px solid #003ca6; border-radius:5px; margin-bottom:10px;}
.addr_list_wrap {display:inline-block; width:85%; font-size:16px;}
.addr_list_wrap div:first-child {margin-bottom:10px;}
.addr_list_wrap span.recipient_name{display:inline-block; width:150px;}
.addr_btn_wrap {display:inline-block; width:15%; font-size:14px; vertical-align:top; }
.addr_btn_wrap button {border:1px solid #003ca6; padding:5px 10px; color:#003ca6; margin:0 auto; display:block;}
.addr_btn_wrap button.default_btn {background-color:#003ca6; color:#fff;}
</style>
<script>
$(document).ready(function(e) {
	$('#modalChangeDelivery').on('show.bs.modal', function(){
		$('#selected_idx').val('');
		$('#add_recipient').val('');
		$('#add_phone').val('');
		$('#add_zip').val('');
		$('#add_road').val('');
		$('#add_jibun').val('');
		$('#add_addr2').val('');
		$('#add_memo').val('');
		$('#add_default').prop('checked', false);
		$('#addr_list').html('');
		ajaxAddressList($('#mde_id').val());
	});

	$('.inp1').on('focus', function() {
		$('#selected_idx').val('');
		$('.delivery_select').removeClass('default_btn');
	});
});

var addr_list = new Array();
function ajaxAddressList(mde_id)
{
	addr_list = new Array();
	$.ajax({
		type:'POST',
		url:'/cart/ajaxAddressList',
		data : {mem_id : '<?php echo $user['mem_id']; ?>' },
		dataType:"json",
		success:function(data){
			addr_list = data;
			var str = '<ul>';
			for(var i = 0; i < data.length; i++) {
				str += '<li>'
					+ '<div class="addr_list_wrap">'
					+ '		<div>'
					+ '			<span class="recipient_name">' + data[i].recipient_name + '</span><span class="recipient_phone">' + data[i].recipient_phone + '</span>'
					+ '		</div>'
					+ '		<div>'
					+ '			<span class="addr">(' + data[i].zipcode + ') ' + data[i].road_addr + ' ' + data[i].detail_addr + '</span>'
					+ '		</div>'
					+ '</div>'
					+ '<div class="addr_btn_wrap">';
				if(data[i].mde_id == mde_id) {
					str += '<button class="delivery_select default_btn" type="button" onclick="javascript:fnPopupSelectAddr(' + i + ', this);">선택</button>';
					$('#selected_idx').val(i);
				}
				else {
					str += '<button class="delivery_select" type="button" onclick="javascript:fnPopupSelectAddr(' + i + ', this);">선택</button>';
				}
				str	+= '</div>'
					+ '</li>';
			}
			str += '</ul>';
			
			$('#addr_list').html(str);
		},
		error:function(data){
			alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
		}
   });
}

function fnPopupSelectAddr(idx, obj)
{
	$('#selected_idx').val(idx);
	$('.delivery_select').removeClass('default_btn');
	$(obj).addClass('default_btn');

	$('#add_recipient').val('');
	$('#add_phone').val('');
	$('#add_zip').val('');
	$('#add_road').val('');
	$('#add_jibun').val('');
	$('#add_addr2').val('');
	$('#add_memo').val('');
}

function fnPopupSetAddr()
{
	var idx = $('#selected_idx').val();
	var param = null
	if(idx == '') {
		var param = {mem_id : '<?php echo $user['mem_id']; ?>',
					order_id : '<?php echo $info['order_id']; ?>',
					mde_id : '', 
					recipient_name : $('#add_recipient').val(),
					recipient_phone : $('#add_phone').val(),
					zipcode : $('#add_zip').val(),
					road_addr : $('#add_road').val(),
					jibun_addr : $('#add_jibun').val(),
					detail_addr : $('#add_addr2').val(),
					memo : $('#add_memo').val(),
					is_default : $('#add_default').is(':checked') ? 'y' : 'n' };
	}
	else {
		var data = addr_list[idx];
		var param = {mem_id : '<?php echo $user['mem_id']; ?>', 
					order_id : '<?php echo $info['order_id']; ?>',
					mde_id : data.mde_id,
					recipient_name : data.recipient_name,
					recipient_phone : data.recipient_phone,
					zipcode : data.zipcode,
					road_addr : data.road_addr,
					jibun_addr : data.jibun_addr,
					detail_addr : data.detail_addr,
					memo : data.memo,
					is_default : $('#add_default').is(':checked') ? 'y' : 'n' };
	}

	$.ajax({
		type:'POST',
		url:'/my/order/ajaxChangeDelivery',
		data : param,
		dataType:"json",
		success:function(data){
			if(data.status == 'succ') {
				location.reload();
			}
			else {
				showAlert('error', data.msg);
			}
		},
		error:function(data){
			alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
		}
   });
}
</script>