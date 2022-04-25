			<!-- 2. 날짜 선택 -->
			<div class="order-step2 order_step" style="display: none">
				<div class="head">
					<button class="btn-back"><span>뒤로</span></button>
				</div>
				<div class="body">
					<div class="calender-choice">
						
						<div class="tit before">원하는 배송날짜 선택</div>
						<div class="tit after">배송일자 : <span id="Date">10일</span><button class="btn-flip"></button></div>
						<div class="box">
							<div id="Calendar" class="calendar"></div>
						</div>
						
						<div class="week">
							<div class="btns">
								<button class="btn-under active">4주 마다</button>
								<button class="btn-under">12주 마다</button>
								<button class="btn-under">16주 마다</button>
							</div>
							<div class="end">배송일자 : 8일 / 4주마다 선택</div>
						</div>
					</div>
					<div class="btn-box">
						<button class="btn btn-type2">이전</button>
						<button class="btn btn-type1" id="BtnStep2">선택완료</button>
					</div>
				</div>
			</div>
			<!-- // 2. 날짜 선택 -->
			
			<!-- 3. 배송지 & 결제하기 -->
			<div class="order-step3 order_step" style="display: none">
				<div class="head">
					<button class="btn-back"><span>뒤로</span></button>
				</div>
				<div class="body">
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
					<div class="baesong">
						<div class="default">
							<h4>기본 배송지</h4>
							<div>
								<a href="#" class="btn-under">다른 배송지 선택</a>
							</div>
						</div>
						<!-- 배송지 -->
						<div class="my-addr">
							<p>김클린</p>
							<p class="gray9">(41065) 대구 동구 이노벨리로 44 명성</p>
						</div>
						
						
						<!-- [DEV] 최초 배송 --> 
						<div class="refund-addr" style="display: none">
							<div class="inp-box1"><input type="text" class="inp1 block" value="김클린 "></div>
							<div class="inp-box2"><input type="text" class="inp1 " value="06012"> <a href="#" class="btn-under">주소찾기</a></div>
							<div class="inp-box1"><input type="text" class="inp1 block" value="서울시 강남구 삼성로 123"></div>
							<div class="inp-box1"><input type="text" class="inp1 block" value="101-201호 "></div>
						</div>
						
						<hr class="hr">
						
						<div class="price-detail">
							<dl>
								<dt>배송비</dt>
								<dd>2,000원</dd>
							</dl>
							<dl>
								<dt>구독할인</dt>
								<dd>-1,500원</dd>
							</dl>
							<dl>
								<dt>포인트 <a href="#" class="btn-under">적용</a></dt>
								<dd>-1,000P</dd>
							</dl>
							<dl class="total">
								<dt>총 결제금액</dt>
								<dd>28,600원</dd>
							</dl>
						</div>
						
						<div class="payment-kind">
							<h5>결제수단 선택</h5>
							<div class="list">
								<div class="mb10"><button class="btn btn-kakaopay"><span>카카오 페이</span></button></div>
								<div class="mb10"><button class="btn btn-naverpay"><span>네이버 페이</span></button></div>
								<div class="mb10"><button class="btn btn-payco"><span>페이코</span></button></div>
								<div class="mb10"><button class="btn btn-card"><span>신용/체크카드</span></button></div>
								
								<div class="card-inputs">
									<h5>카드번호</h5>
									<div class="mb10"><input type="tel" class="inp1" style="width:100%" placeholder="1234-1234-1234-1234"></div>
									<h5>생년월일 앞 6자리 (또는 사업자등록번호 10자리)</h5>
									<div class="mb10"><input type="tel" class="inp1" style="width:100%" placeholder="900101"></div>
									<h5>카드만료기간 (월/년)</h5>
									<div class="mb10"><input type="tel" class="inp1" style="width:100%" placeholder="06 / 25"></div>
									<h5>비밀번호 앞자리 </h5>
									<div class="mb10"><input type="password" class="inp1" style="width:100%" placeholder="**"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="btn-box">
						<button class="btn btn-type1" id="BtnStep3">결제하기</button>
					</div>
				</div>
			</div>
			<!-- // 3. 배송지 & 결제하기 -->
			
<script>
					// 다음으로 이동
$(document).ready(function(e) {
	$('#BtnStep1').click(function(){
		var delivery = $('input[name=set_delivery]').val();
		if(delivery > 0) {
			alert(commify(freeVal) + '이상 선택하셔야 구독이 가능합니다.');
			return;	
		}
		
		var option = fnOptionCheck();
		if(option != '') {
			alert(option + '을(를) 선택해 주세요.');
			return;	
		}
		$('.order-step1').hide();
		$('.order-step2').fadeIn(300);
	})
	$('#BtnStep2').click(function(){
		$('.order-step2').hide();
		$('.order-step3').fadeIn(300);
	})
				
				// Calendar
	var date = "";
	$('#Calendar').datepicker({
		onSelect:function(dateText,inst){
						//console.log(dateText + ' / ' + inst);
			date = dateText.slice(-2);
			$('.calender-choice .tit.before').hide();
			$('.calender-choice .tit.after').show();
			$('.calender-choice #Date').text(date+'일');
			$('.calender-choice .box').slideUp(300);
		}
	});
				
	$('.aside-order .body .calender-choice .tit.after .btn-flip').click(function(){
		$('.calender-choice .box').slideDown(300);
		$('.calender-choice .tit.before').show();
		$('.calender-choice .tit.after').hide();
					
	})
	    
});
			
</script>