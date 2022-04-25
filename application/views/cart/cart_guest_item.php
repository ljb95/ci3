                <div class="subscrip">
  					<div class="t1">슬기로운 양치생활 - <br class="mobile">클린디 정기구독</div>
                    <div class="t2">정기구독으로 클린디 서비스를 쉽고 편하게! <br class="mobile">배송비에 진단 이벤트 1,000P <br class="mobile">추가 할인까지! <br>슬기로운 양치생활의 기회를 <br class="mobile">놓치지 마세요! </div>
                    <div class="btns">
                        <a href="/cart/change_cart" class="btn btn-type1 btn-m">정기구독 혜택받기</a>
                    </div>
                </div>
				<div class="btn-box-common1" id="subscribe_1">
					<a href="/product/product_list" class="btn btn-type2">제품 추가</a>
					<a href="javascript:void(0);" class="btn btn-type1" id="btn_step1">주문하기</a>
				</div>
				<div class="col-box" id="subscribe_3" style="display:none">
					<div>
                       	<div class="join-wrap">
                           	<div>회원가입을 하신 적이 있으시다면<br>
								<a href="/member/login">여기를 눌러 로그인</a>을 하세요.
                            </div>
                       </div>
						<div class="default">
							<h4>회원정보입력</h4>
							<!--
							<div>
								<a href="#" class="btn-under">다른 배송지 선택</a>
							</div>
							-->
						</div>
						<div class="refund-addr mb60">
							<div class="inp-box1 btw phone">
                            	<input type="text" name="mem_phone" class="inp1 block" placeholder="휴대폰번호 (”-”제외)"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="11">
                                <a href="#" onclick="javascript:fnSendAuth(); return false;" class="btn-under" id="auth_btn1">인증</a>
                            </div>
                            <div class="inp-box1" id="sms_auth_wrap" style="display:none">
	                            <input type="text" class="inp1 block" name="auth_number" placeholder="인증번호를 입력해 주세요." oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="6" />
                                <input type="hidden" name="auth" />
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
                            	<input type="text" class="inp1 " name="zipcode" value="" placeholder="우편번호" readonly /> 
                            	<a href="#" onclick="javascript:execDaumPostcode2($('input[name=zipcode]'), $('input[name=road_addr]'), $('input[name=jibun_addr]'), fnCheckDelivery); return false;" class="btn-under">주소찾기</a>
                            </div>
							<div class="inp-box1">
                            	<input type="text" class="inp1 block" name="road_addr" value="" placeholder="기본주소" readonly />
                            	<input type="hidden" name="jibun_addr" value="">
                            </div>
							<div class="inp-box1">
                            	<input type="text" class="inp1 block" name="addr2" value="" placeholder="상세주소" />
                            </div>
							<div class="inp-box1">
                            	<input type="text" class="inp1 block" name="memo" value="" placeholder="배송요청사항" />
                            </div>
						</div>
						
						
					</div>
					<div>
						<div class="payment-kind">
							<h5>결제수단 선택</h5>
							<div class="list mb40">
								<div class="mb10"><button class="btn2 pay2" a="direct"><span>실시간계좌이체</span></button></div>
								<div class="mb10"><button class="btn2 pay3" a="card"><span>신용/체크카드</span></button></div>
								<div class="mb10"><button class="btn2 pay4" a="kakao"><span>카카오페이</span></button></div>
								<div class="mb10"><button class="btn2 pay5" a="naver"><span>네이버페이</span></button></div>
								<div class="mb10"><button class="btn2 pay6" a="samsung"><span>삼성페이</span></button></div>
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
										<dd><a href="#" data-toggle="modal" data-target="#modalTerms">내용보기</a></dd>
									</dl>
									<dl>
										<dt>개인정보 수집 및 이용 동의</dt>
										<dd><a href="#" data-toggle="modal" data-target="#modalPrivate">내용보기</a></dd>
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
<script>
$(document).ready(function(e) {
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

	$('#btn_step3').on('click', function() {
		fnCheckUser($("#frmCart"));
	});

	$('.payment-kind .pay3').on('click', function(){
		$('.pay-card-info').addClass('show');
	});
	$('.payment-kind .pay3').parent('div').siblings().find('.btn2').on('click', function(){
		$('.pay-card-info').removeClass('show');
	});
});
</script>
<style>
.join-wrap {font-size:18px; font-weight:bold; text-align:center; margin-bottom:20px;}
.join-wrap div a {text-decoration:underline !important; text-underline-position: under;}
.join-wrap .join-desc {font-size:14px; text-align:left; padding:10px;}
</style>