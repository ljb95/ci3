<!-- ========================================================
/application/views/cart/cart_membert_subscribe.php 참고 페이지 
=========================================================  -->
<div class="sub-head product dendist">
    <div class="inner">
        <h2 class="h2">치과추천</h2>
        <div class="tabs">
            <div>

            </div>
        </div>
    </div>
</div>
<form id="frmCart" onsubmit="return false;">
    <div class="inner">
        <div class="cart-step">
            <!-- 1. 구독 -->
            <div class="step3">
                <div class="btn-box-common1" id="subscribe_1" style="display: none;">
                    <a href="/product/product_list" class="btn btn-type2">제품 추가</a>
                    <a href="javascript:void(0);" class="btn btn-type1" id="btn_step1">구독하기</a>
                </div>
                <div id="subscribe_2" style="display: none;">
                    <div class="calendar-cart">
                        <div class="tit">
                            <strong>원하는 배송날짜 선택</strong><br>
                        </div>
                        <div class="tit2">오후 3시(15시)이전까지 구독 신청 시 당일발송됩니다.<br>오후 3시(15시)이후는 익일부터 선택 가능합니다.</div>
                        <div class="box">
                            <div id="CalendarCart" class="calendar hasDatepicker"><div class="ui-datepicker-inline ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" style="display: block;"><div class="ui-datepicker-header ui-widget-header ui-helper-clearfix ui-corner-all"><a class="ui-datepicker-prev ui-corner-all ui-state-disabled" title="이전달"><span class="ui-icon ui-icon-circle-triangle-w">이전달</span></a><a class="ui-datepicker-next ui-corner-all" data-handler="next" data-event="click" title="다음달"><span class="ui-icon ui-icon-circle-triangle-e">다음달</span></a><div class="ui-datepicker-title"><span class="ui-datepicker-year">2022</span>&nbsp;<span class="ui-datepicker-month">3월</span></div></div><table class="ui-datepicker-calendar"><thead><tr><th class="ui-datepicker-week-end"><span title="일">일</span></th><th><span title="월">월</span></th><th><span title="화">화</span></th><th><span title="수">수</span></th><th><span title="목">목</span></th><th><span title="금">금</span></th><th class="ui-datepicker-week-end"><span title="토">토</span></th></tr></thead><tbody><tr><td class=" ui-datepicker-week-end ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-unselectable ui-state-disabled "><span class="ui-state-default">1</span></td><td class=" ui-datepicker-unselectable ui-state-disabled "><span class="ui-state-default">2</span></td><td class=" ui-datepicker-days-cell-over  ui-datepicker-current-day ui-datepicker-today" data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default ui-state-highlight ui-state-active ui-state-hover" href="#">3</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">4</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">5</a></td></tr><tr><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">6</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">7</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">8</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">9</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">10</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">11</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">12</a></td></tr><tr><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">13</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">14</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">15</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">16</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">17</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">18</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">19</a></td></tr><tr><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">20</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">21</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">22</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">23</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">24</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">25</a></td><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">26</a></td></tr><tr><td class=" ui-datepicker-week-end " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">27</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">28</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">29</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">30</a></td><td class=" " data-handler="selectDay" data-event="click" data-month="2" data-year="2022"><a class="ui-state-default" href="#">31</a></td><td class=" ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td><td class=" ui-datepicker-week-end ui-datepicker-other-month ui-datepicker-unselectable ui-state-disabled">&nbsp;</td></tr></tbody></table></div></div>
                            <div class="week">
                                <div class="btns">
                                    <p>
                                        <button class="btn-period btn-under active" id="select_period_4" onclick="javascript:fnSetPeriod(4);">4주 마다</button>
                                        <span class="desc2 desc_week" id="desc_4week">다음 결제일 : <label id="desc_4week_day">2022.03.31</label>(<label class="week_val">목</label>요일)</span>
                                    </p>
                                    <p>
                                        <button class="btn-period btn-under" id="select_period_12" onclick="javascript:fnSetPeriod(12);">12주 마다</button>
                                        <span class="desc2 desc_week" id="desc_12week" style="display:none;">다음 결제일 : <label id="desc_12week_day">2022.05.26</label>(<label class="week_val">목</label>요일)</span>
                                    </p>
                                    <p>
                                        <button class="btn-period btn-under" id="select_period_16" onclick="javascript:fnSetPeriod(16);">16주 마다</button>
                                        <span class="desc2 desc_week" id="desc_16week" style="display:none;">다음 결제일 : <label id="desc_16week_day">2022.06.23</label>(<label class="week_val">목</label>요일)</span>
                                    </p>
                                    <input type="hidden" name="delivery_day" value="4">
                                    <input type="hidden" name="delivery_period" value="4">
                                    <input type="hidden" name="start_date" value="2022-03-03">
                                </div>
                                <div class="end">결제일자 : <span id="show_delivery_day">2022.03.03</span>일(<label class="week_val">목</label>요일) / 
                                    <span id="show_delivery_period">4</span>주 마다 선택</div>
                                <span class="desc3">*결제일이 토요일/공휴일인 경우 익일(또는 가장 빠른 영업일) 발송됩니다.</span>
                            </div>
                        </div>

                    </div>
                    <div class="btn-box-common1">
                        <a href="javascript:void(0);" class="btn btn-type1" id="btn_step2">선택완료</a>
                    </div>
                </div>
<script>
$(document).ready(function(e) {
	$('#CalendarCart').datepicker({
		dateForat : 'yyyy-mm-dd',
		minDate : '2022-03-03',
		onSelect:function(dateText,inst){
			var week = ['일', '월', '화', '수', '목', '금', '토'];
			var weekVal = new Date(dateText).getDay()
			var dayOfWeek = week[weekVal];
			
			var tmp = dateText.split('-');
			$('input[name=delivery_day]').val(weekVal);
			$('input[name=start_date]').val(dateText);
			$('#show_delivery_day').text(tmp.join('.'));
			$('.week_val').html(dayOfWeek);
			console.log(inst);
			
			var cur = new Date(dateText);
			var week4 = new Date(cur.setDate(cur.getDate() + 28));
			var week12 = new Date(cur.setDate(cur.getDate() + 56));
			var week16 = new Date(cur.setDate(cur.getDate() + 28));
			$('#desc_4week_day').html(week4.getFullYear() + '.' + (week4.getMonth() < 9 ? '0' : '') + (week4.getMonth() + 1) + '.' + (week4.getDate() < 10 ? '0' : '') + week4.getDate());
			$('#desc_12week_day').html(week12.getFullYear() + '.' + (week12.getMonth() < 9 ? '0' : '') + (week12.getMonth() + 1) + '.' + (week12.getDate() < 10 ? '0' : '') + week12.getDate());
			$('#desc_16week_day').html(week16.getFullYear() + '.' + (week16.getMonth() < 9 ? '0' : '') + (week16.getMonth() + 1) + '.' + (week16.getDate() < 10 ? '0' : '') + week16.getDate());
		}
	});	
});

function fnSetPeriod(week) {
	$('input[name=delivery_period]').val(week);
	$('#show_delivery_period').html(week);
	$('.btn-period').removeClass('active');
	$('#select_period_' + week).addClass('active');	
	
	$('.desc_week').hide();
	$('#desc_' + week + 'week').show();
}
</script>
<div class="col-box" id="subscribe_3" style="">
    <div>
        <div class="default">
            <h4>회원정보입력</h4>
        </div>
        <div class="refund-addr mb60">
            <div class="inp-box1 btw phone">
                <input type="text" name="mem_phone" class="inp1 block" placeholder="휴대폰번호 (”-”제외)" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="11">
                <a href="#" onclick="javascript:fnSendAuth(); return false;" class="btn-under" id="auth_btn1">인증</a>
            </div>
            <div class="inp-box1" id="sms_auth_wrap" style="display:none">
                <input type="text" class="inp1 block" name="auth_number" placeholder="인증번호를 입력해 주세요." oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="6">
                <input type="hidden" name="auth">
                <a href="#" onclick="javascript:fnCheckAuth(); return false;" class="btn-under" id="auth_btn2">확인</a>
                <span class="red btn-under" id="auth_timer" style="right:60px; border-bottom:none;">3:00</span>
            </div>
            <div class="inp-box1"><input type="text" name="mem_name" class="inp1 block" placeholder="이름"></div>
            <div class="inp-box1"><input type="text" name="mem_email" class="inp1 block" placeholder="이메일주소(아이디)"></div>
            <div class="inp-box1"><input type="password" name="mem_password" class="inp1 block" placeholder="비밀번호 (6~15자의 영문 대소문자, 숫자 혼합)"></div>
            <div class="inp-box1"><input type="password" name="password_confirm" class="inp1 block" placeholder="비밀번호 재입력 "></div>
            <div class="join-wrap">
                <div class="join-desc">
                    *여기에 입력된 정보로 회원가입이 됩니다.<br>
                    이후 재주문, 배송조회 및 교환/반품은 입력된 정보로 생성된 계정으로 진행 가능합니다.
                </div>
            </div>
        </div>

        <div class="default">
            <h4>배송지</h4>
        </div>
        <div class="refund-addr">
            <div class="inp-box1">
                <input type="text" class="inp1 block" placeholder="수령인" name="recipient_name" value="">
            </div>
            <div class="inp-box1">
                <input type="text" class="inp1 block" placeholder="수령인연락처" name="recipient_phone" value="" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="11">
            </div>
            <div class="inp-box2 btw addres">
                <input type="text" class="inp1 " name="zipcode" value="" placeholder="우편번호" readonly=""> 
                <a href="#" onclick="javascript:execDaumPostcode($('input[name=zipcode]'), $('input[name=road_addr]'), $('input[name=jibun_addr]') ); return false;" class="btn-under">주소찾기</a>
            </div>
            <div class="inp-box1">
                <input type="text" class="inp1 block" name="road_addr" value="" placeholder="기본주소" readonly="">
                <input type="hidden" name="jibun_addr" value="">
            </div>
            <div class="inp-box1">
                <input type="text" class="inp1 block" name="addr2" value="" placeholder="상세주소">
            </div>
            <div class="inp-box1">
                <input type="text" class="inp1 block" name="memo" value="" placeholder="배송요청사항">
            </div>
        </div>


    </div>
    <div>
        <div class="payment-kind">
            <h5>결제수단 선택</h5>
            <div class="list mb40">
                <div class="mb10"><button class="btn2 pay3" a="card"><span>신용/체크카드</span></button></div>
                <div class="mb10"><button class="btn2 pay4" a="kakao"><span>카카오페이</span></button></div>
                <div class="mb10"><button class="btn2 pay5" a="naver"><span>네이버페이</span></button></div>
            </div>
        </div>
        <div class="pay-card-info">
            <div class="inp-box1">
                <label for="">카드번호</label>
                <input type="text" class="inp1 block" placeholder="1234-1234-1234-1234" name="recipient_phone" value="" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="11">
            </div>
            <div class="inp-box1">
                <label for=""> 생년월일 앞 6자리 (또는 사업자등록번호 10자리)</label>
                <input type="text" class="inp1 block" placeholder="900101" name="recipient_phone" value="" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="11">
            </div>
            <div class="inp-box1">
                <label for="">카드만료기간 (월/년)</label>
                <input type="text" class="inp1 block" placeholder="06/25" name="recipient_phone" value="" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="11">
            </div>
            <div class="inp-box1">
                <label for=""> 비밀번호 앞자리 </label>
                <input type="password " class="inp1 block" placeholder="**" name="recipient_phone" value="" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="2">
            </div>
        </div>
        <div class="agree-wrap">
            <div class="mb20"><label class="label-checkbox"><input type="checkbox" class="checkbox" id="AllCheck"><em></em><span><strong>전체동의, 선택항목도 포함합니다</strong></span></label></div>
            <div class="in">
                <div class="mb20"><label class="label-checkbox"><input type="checkbox" name="terms" class="checkbox"><em></em><span>(필수) 이용약관에 모두 동의 합니다. </span></label></div>
                <div class="mb20"><label class="label-checkbox"><input type="checkbox" name="private" class="checkbox"><em></em><span> (필수) 개인정보 수집 및 이용에 동의 합니다.  </span></label></div>
                <div class="view">
                    <dl>
                        <dt>서비스 이용약관</dt>
                        <dd><a href="#">내용보기</a></dd>
                    </dl>
                    <dl>
                        <dt>개인정보 수집 및 이용 동의</dt>
                        <dd><a href="#">내용보기</a></dd>
                    </dl>
                </div>
                <div class="mb20"><label class="label-checkbox"><input type="checkbox" name="overage" class="checkbox"><em></em><span>(필수) 본인은 만 14세 이상입니다. </span></label></div>
                <div class="mb20"><label class="label-checkbox"><input type="checkbox" name="event" class="checkbox"><em></em><span>(선택) 정보/이벤트 메일, SMS수신에 동의합니다.  </span></label></div>
                <div class="msg"><p>* SMS, 이메일 수신에  동의해주시면 클린디 맞춤 알림을 받아 보실 수 있습니다.</p> </div>
            </div>
        </div>

        <button class="btn btn-type1 block" id="btn_step3">주문하기</button>
    </div>
</div>
   </div>
            </div>
        </div>
</form>
<?php $this->load->view('common/deliveryPopup'); ?>
<script>
$(document).ready(function(e) {
	$('input[name=pay_type]').val('card');
	$('#btn_step1').on('click', function() {
		$('.btn-plus').hide();
		$('.btn-minus').hide();
		$('.tabs1').hide();
		$.ajax({
			type:'POST',
			url:'/cart/ajaxCheckSubscribe',
			data : {type : 'subscribe' },
			dataType:"json",
			success:function(data){
				if(data.status == 'succ') {
					$('#subscribe_1').hide();
					$('#subscribe_2').show();
				}
				else {
					showAlert('error', data.msg);
				}
			},
			error:function(data){
				alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
			}
	   });
	});
        $('input[name=pay_type]').val('card');
	$('#AllCheck').click(function(){
		if($(this).prop('checked')){
			$('.checkbox').prop('checked', true);
		}else{
			$('.checkbox').prop('checked', false);
		}
	})
	$('.checkbox').click(function(){
		var bChecked = true;
		$('.checkbox').each(function(index, element) {
            if(!$(this).is(':checked')) {
				bChecked = false;	
			}
        });
		$('#AllCheck').prop('checked', bChecked);
	})
	
	$('#btn_step1').on('click', function() {
		$('.btn-plus').hide();
		$('.btn-minus').hide();
		$('#subscribe_1').hide();
		$('#subscribe_3').show();
		$('.tabs1').hide();
	});

	$('#btn_step2').on('click', function() {
		$('#subscribe_2').hide();
		$('#subscribe_3').show();
	});

	$('#btn_step3').on('click', function() {
		<?php
			if(empty($delivery)) echo 'fnAddDeliveryAddress();';
			else echo 'fnPaymentRequest($("#frmCart"));';
		?>
	});

	$('.payment-kind .pay3').on('click', function(){
		$('.pay-card-info').addClass('show');
	});
	$('.payment-kind .pay3').parent('div').siblings().find('.btn2').on('click', function(){
		$('.pay-card-info').removeClass('show');
	});
});

function fnSetDelivery(data)
{
	$('input[name=recipient_name]').val(data.recipient_name);
	$('input[name=recipient_phone]').val(data.recipient_phone);
	$('input[name=zipcode]').val(data.zipcode);
	$('input[name=road_addr]').val(data.road_addr);
	$('input[name=jibun_addr]').val(data.jibun_addr);
	$('input[name=addr2]').val(data.detail_addr);
	$('input[name=memo]').val(data.memo);
	
	$('#show_recipient').html(data.recipient_name);
	$('#show_recipient_tel').html(data.recipient_phone);
	$('#show_recipient_addr').html('(' + data.zipcode + ')' + data.road_addr + ' ' + data.detail_addr);
	$('#show_recipient_memo').html(data.memo);
}

function fnAddDeliveryAddress()
{
	$.ajax({
		type:'POST',
		url:'/cart/ajaxAddAddress',
		data : {mem_id : '<?php echo $user['mem_id']; ?>', 
				recipient_name : $('input[name=recipient_name]').val(),
				recipient_phone : $('input[name=recipient_phone]').val(),
				zipcode : $('input[name=zipcode]').val(),
				road_addr : $('input[name=road_addr]').val(),
				jibun_addr : $('input[name=jibun_addr]').val(),
				detail_addr : $('input[name=addr2]').val(),
				memo : $('input[name=memo]').val(),
				is_default : 'y' },
		dataType:"json",
		success:function(data){
			if(data.status == 'succ') {
				fnPaymentRequest($("#frmCart"));
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