			<!-- 3. 배송지 & 결제하기 -->
			<div class="order-step2 order_step" style="display: none">
				<div class="head">
					<button class="btn-back"><span>뒤로</span></button>
				</div>
				<div class="body">
                	<?php
						if($info['is_subscribe'] === 'y') {
					?>
                        <div class="subscrip">
                            <div class="t1">슬기로운 양치생활 -<br>클린디 정기구독</div>
                            <div class="t2">정기구독으로 클린디 서비스를 쉽고 편하게! <br>
											배송비에 진단 이벤트 1,000P 추가 할인까지! <br><br>
                                            슬기로운 양치생활의 기회 <br>
                                            놓치지 마세요! </div>
                            <div class="btns">
                                <a href="#" class="btn btn-type1 btn-m">정기구독 혜택받기</a>
                            </div>
                        </div>
                    <?php
						}
					?>
					<div class="baesong">
                    <?php
						if(isset($user)) {
							if(isset($delivery)) {
					?>
						<div class="default">
							<h4>기본 배송지</h4>
							<div>
								<a href="#" class="btn-under">다른 배송지 선택</a>
							</div>
						</div>
						<!-- 배송지 -->
						<div class="my-addr">
							<p id="show_recipient"><?php echo $delivery['recipient_name']; ?></p>
							<p id="show_recipient_tel"><?php echo $delivery['recipient_phone']; ?></p>
							<p class="gray9" id="show_recipient_addr"><?php echo '(' . $delivery['zipcode'] . ')' . $delivery['road_addr'] . ' ' . $delivery['addr2']; ?></p>
							<p class="gray9" id="show_recipient_memo"><?php echo $delivery['meno']; ?></p>
                           	<input type="hidden" name="recipient_name" value="<?php echo $delivery['recipient_name']; ?>">
                           	<input type="hidden" name="recipient_phone" value="<?php echo $delivery['recipient_phone']; ?>" >
                           	<input type="hidden" name="zipcode" value="<?php echo $delivery['zipcode']; ?>"  /> 
                           	<input type="hidden" name="road_addr" value="<?php echo $delivery['road_addr']; ?>"  />
                           	<input type="hidden" name="jibun_addr" value="<?php echo $delivery['jibun_addr']; ?>">
                           	<input type="hidden" name="addr2" value="<?php echo $delivery['addr2']; ?>" />
                           	<input type="hidden" name="memo" value="<?php echo $delivery['memo']; ?>" />
						</div>
						
						
                    <?php
							}
							else {
					?>
						<!-- [DEV] 최초 배송 --> 
						<div class="refund-addr">
							<h5 style="font-size: 22px; margin-bottom: 18px; font-weight: 500;">수령인정보</h5>
							<div class="inp-box1">
                            	<input type="text" class="inp1 block" placeholder="수령인" name="recipient_name" value="">
                            </div>
							<div class="inp-box1">
                            	<input type="text" class="inp1 block" placeholder="수령인연락처" name="recipient_phone" value="" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="11">
                            </div>
							<div class="inp-box2">
                            	<input type="text" class="inp1 " name="zipcode" value="" placeholder="우편번호" readonly /> 
                            	<a href="#" onclick="javascript:execDaumPostcode($('input[name=zipcode]'), $('input[name=road_addr]'), $('input[name=jibun_addr]') ); return false;" class="btn-under">주소찾기</a>
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
					<?php
							}
						}
						else {
					?>
						<div class="refund-addr">
                        	<div class="join-wrap">
                            	<div>회원가입을 하신 적이 있으시다면<br>
									<a href="#">여기를 눌러 로그인</a>을 하세요.</div>
								<div class="join-desc">
                                	*여기에 입력된 정보로 회원가입이 됩니다.<br>
									이후 재주문, 배송조회 및 교환/반품은 입력된 정보로 생성된 계정으로 진행 가능합니다.
                                </div>
                            </div>
							<h5 style="font-size: 22px; margin-bottom: 18px; font-weight: 500;">회원정보입력</h5>
							<div class="inp-box1"><input type="text" name="mem_phone" class="inp1 block" placeholder="휴대폰번호 (”-”제외)"  oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="11"><a href="#" class="btn-under">인증</a></div>
							<div class="inp-box1"><input type="text" name="mem_name" class="inp1 block" placeholder="이름"></div>
							<div class="inp-box1"><input type="text" name="mem_email" class="inp1 block" placeholder="이메일주소(아이디)"></div>
							<div class="inp-box1"><input type="password" name="mem_password" class="inp1 block" placeholder="비밀번호 (6~15자의 영문 대소문자, 숫자 혼합)"></div>
							<div class="inp-box1"><input type="password" name="password_confirm" class="inp1 block" placeholder="비밀번호 재입력 "></div>

                            <div class="agree-wrap">
                                <div class="mb10"><label class="label-checkbox"><input type="checkbox" class="checkbox" id="AllCheck" ><em></em><span><strong>전체동의, 선택항목도 포함합니다</strong></span></label></div>
                                <div class="in">
                                    <div class="mb10"><label class="label-checkbox"><input type="checkbox" name="terms" class="checkbox chk" ><em></em><span>(필수) 이용약관에 모두 동의 합니다. </span></label></div>
                                    <div class="mb10"><label class="label-checkbox"><input type="checkbox" name="private" class="checkbox chk" ><em></em><span> (필수) 개인정보 수집 및 이용에 동의 합니다.  </span></label></div>
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
                                    <div class="mb10"><label class="label-checkbox"><input type="checkbox" name="overage" class="checkbox chk"><em></em><span>(필수) 본인은 만 14세 이상입니다. </span></label></div>
                                    <div class="mb10"><label class="label-checkbox"><input type="checkbox" name="event" class="checkbox chk" ><em></em><span>(선택) 정보/이벤트 메일, SMS수신에 동의합니다.  </span></label></div>
                                    <div class="msg" style="text-align:left; padding:5px;"><p>* SMS, 이메일 수신에  동의해주시면 클린디 맞춤 알림을 받아 보실 수 있습니다.</p> </div>
                                </div>
                            </div>

						</div>
						<hr class="hr">

						<div class="refund-addr">
							<h5 style="font-size: 22px; margin-bottom: 18px; font-weight: 500;">수령인정보</h5>
							<div class="inp-box1">
                            	<input type="text" class="inp1 block" placeholder="수령인" name="recipient_name" value="">
                            </div>
							<div class="inp-box1">
                            	<input type="text" class="inp1 block" placeholder="수령인연락처" name="recipient_phone" value="" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="11">
                            </div>
							<div class="inp-box2">
                            	<input type="text" class="inp1 " name="zipcode" value="" placeholder="우편번호" readonly /> 
                            	<a href="#" onclick="javascript:execDaumPostcode($('input[name=zipcode]'), $('input[name=road_addr]'), $('input[name=jibun_addr]') ); return false;" class="btn-under">주소찾기</a>
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
                    
                    <?php
						}
					?>
						
						<hr class="hr">
						
						<div class="price-detail">
							<dl>
								<dt>배송비</dt>
								<dd id="step2_delivery_price">2,000원</dd>
							</dl>
                            <?php
								if(isset($user)) {
    						?>                        
							<dl>
								<dt>포인트 <a href="#" class="btn-under">적용</a></dt>
								<dd>0P</dd>
							</dl>
                            <?php
								}
							?>	
							<dl class="total">
								<dt>총 결제금액</dt>
								<dd id="step2_total_price">28,600원</dd>
							</dl>
						</div>
						
						<div class="payment-kind">
							<h5>결제수단 선택</h5>
							<div class="list">
								<div class="mb10"><button class="btn btn-card"><span>신용/체크카드</span></button></div>
							</div>
						</div>
					</div>
					<div class="btn-box">
						<button class="btn btn-type1" id="BtnStep2">결제하기</button>
					</div>
				</div>
			</div>
			<!-- // 3. 배송지 & 결제하기 -->
<script>
$(document).ready(function(e) {
	$('#BtnStep1').click(function(){
		var option = fnOptionCheck();
		if(option != '') {
			alert(option + '을(를) 선택해 주세요.');
			return;	
		}
		$('#step2_delivery_price').html($('#step1_delivery_price').html());
		$('#step2_total_price').html($('#step1_total_price').html());
		$('.order-step1').hide();
		$('.order-step2').fadeIn(300);
		$('.aside-order').scrollTop(0)
	});
	$('#BtnStep2').click(function(){
//		$('.order-step2').hide();
//		$('.order-step3').fadeIn(300);
		<?php 
			if(isset($user)) echo 'fnPaymentRequest($("#order_data"));';
			else echo 'fnCheckUser($("#order_data"));';
		?>
	});
		// 동의 스크립트(임시)
	$('#AllCheck').click(function(){
		if($(this).prop('checked')){
			$('.checkbox').prop('checked', true);
		}else{
			$('.checkbox').prop('checked', false);
		}
	})
	$('.checkbox').click(function(){
		var checked = $(this).is(':checked');
		if(!checked){
			$('#AllCheck').prop('checked', false);
		}
	})
});

</script>
<style>
.agree-wrap .checkbox + em + span {font-size:14px !important; }
.agree-wrap .checkbox + em {width:20px !important; height:20px !important;}
.agree-wrap .in .view dl dt {font-size:14px !important;}
.agree-wrap .in .view dl dd a {font-size:14px !important; }
.agree-wrap .msg {text-align:left !important;padding:5px !important;font-size: 14px !important;}
.agree-wrap .in .view {padding:20px 20px;}
.agree-wrap .in {padding:20px }
.join-wrap {font-size:18px; font-weight:bold; text-align:center;}
.join-wrap div a {text-decoration:underline !important; text-underline-position: under;}
.join-wrap .join-desc {font-size:14px; text-align:left; padding:10px;}
</style>