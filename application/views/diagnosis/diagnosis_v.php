	<div class="sub-head diagnosis-head">
		<div class="inner">
			<h2 class="h2">진단</h2>
		</div>
	</div>
	
	<div class="inner">
		<div class="diag-main">
			<h3>지금. 내 치아 건강은?</h3>
			<div class="desc">클린디 진단은 간단한 질문과 답변을 통해 고객님의 구강상태를 파악하고 <br class="pc">맞춤 제품을 추천해드립니다. </div>
			<div class="tip-point">
				<p>클린디 진단에<br>참여해주신 분께는<br>1,000 포인트를<br>적립해드립니다.</p>
				<a href="/faq">포인트! 이렇게 쓰여요.</a>
			</div>
			<div class="process">
				<ul>
					<li><p>기본 정보</p></li>
					<li><p>양치 습관</p></li>
					<li><p>생활 습관</p></li>
					<li><p>구강 상태</p></li>
					<li><p>치아 상태</p></li>
				</ul>
			</div>
			<div class="start">
				<button class="btn btn-start">시작하기</button>
			</div>
		</div>
		
		<!-- 진단 -->
		<div class="survey-container" style="display: none; background-color:rgba(51,51,51,0.5); z-index:10;" id="input_user">
			<div class="survey-wrap">
				<div class="survey-box">
					<div class="box" style="margin-top:60px">
						<div class="step">
							<div class="in"  id="progress_step">
								<ul>
									<li id="progress_step1"><p>기본 정보</p></li>
									<li id="progress_step2"><p>양치 습관</p></li>
									<li id="progress_step3"><p>생활 습관</p></li>
									<li id="progress_step4"><p>구강 상태</p></li>
									<li id="progress_step5"><p>치아 상태</p></li>
								</ul>
							</div>
							<div class="bar"><em id="progress_bar" style="width:0"></em></div>
						</div>
						<div class="body">
							<div class="boxs">
                            	<form id="frmDiagnosis">
								<!-- 01 -->
								<div class="box1">
									<div class="in">
										<div class="tit">
											<em>01</em>
											<p><span>클린디</span>가 고객님을 부를 수 있게 이름을 알려주세요.</p>
										</div>
										<div class="inp-box text-center mb70">
											<input type="text" class="inp1 large" id="user_name" name="user_name" placeholder="이름" style="width:83%">
										</div>
									</div>
								</div>

								<!-- 02 -->
								<div class="box2" style="display: none">
									<div class="in">
										<div class="tit">
											<em>02</em>
											<p><span class="username">클린디</span>님의 성별은 무엇인가요?</p>
										</div>
										<div class="check-boxs mb70">
											<label><input type="radio" class="txt-radio1" name="user_sex" value="1"><span>여성</span></label>
											<label><input type="radio" class="txt-radio1" name="user_sex" value="2"><span>남성</span></label>
										</div>
									</div>
								</div>

								<!-- 03 -->
								<div class="box3" style="display: none">
									<div class="in">
										<div class="tit">
											<em>03</em>
											<p><span class="username"></span>님의 나이를 알려주세요.</p>
										</div>
										<div class="inp-box text-center mb70">
											<input type="tel" class="inp1 large" id="user_age" name="user_age" placeholder="나이" style="width:83%" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="3">
										</div>
									</div>
								</div>

								<!-- 04 -->
								<div class="box4" style="display: none">
									<div class="in">
										<div class="tit">
											<em>04</em>
											<p><span class="username">김클린</span>님의 키와 몸무게를 알려주세요 </p>
										</div>
										<div class="tit-desc">* 키와 몸무게는 구강형태를 파악하기 위한 과정입니다.  </div>
										<div class="inp-box2 mb50">
											<dl>
												<dt>키</dt>
												<dd><input type="tel" class="inp1 large" id="user_height" name="user_height" placeholder="키" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="3"></dd>
											</dl>
											<dl>
												<dt>몸무게</dt>
												<dd><input type="tel" class="inp1 large" id="user_weight" name="user_weight" placeholder="몸무게" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="3"></dd>
											</dl>
										</div>
									</div>
								</div>

								<!-- 05 -->
								<div class="box5" style="display: none">
									<div class="in">
										<div class="tit">
											<em>05</em>
											<p><span class="username">김클린</span>님과 가장 가까운 구강 구조를 골라주세요.</p>
										</div>
										<div class="check-boxs4 mb70">
											<div>
												<label><input type="radio" class="txt-radio2" name="structure" value="1"><span><i class="ic0-1"></i>좁은 구강구조</span>
												<div class="info">
													<p>- V 형태의 구강구조로 앞니
돌출 등으로 뾰족한 구조</p>
													<div><img src="/res/img/new_icon/diagnosis_narrow.svg" style="width:120px"></div>
												</div></label>
											</div>
											<div>
												<label><input type="radio" class="txt-radio2" name="structure" value="2"><span><i class="ic0-2"></i>보통 구강구조</span>
												<div class="info">
													<p>- O 형태의 구강구조로
대중적인 구강구조</p>
													<div><img src="/res/img/new_icon/diagnosis_normal.svg" style="width:144px"></div>
												</div></label>
											</div>
											<div>
												<label><input type="radio" class="txt-radio2" name="structure" value="3"><span><i class="ic0-3"></i>넓은 구강구조</span>
												<div class="info">
													<p> - U형태의 구강구조로
양쪽 어금니 사이와 송곳니 
사이가 넓은 구조</p>
													<div><img src="/res/img/new_icon/diagnosis_wide.svg" style="width:178px"></div>
												</div></label>
											</div>
										</div>
									</div>
								</div>
								
								<!-- 05 -->
								<div class="box6" style="display: none">
									<div class="in">
										<div class="tit">
											<em>06</em>
											<p><span class="username">김클린</span>님은 하루에 양치질을 몇번 하시나요?</p>
										</div>
										<div class="check-boxs mb70">
											<label><input type="radio" class="txt-radio1" name="brush_cnt" value="1"><span>1회</span></label>
											<label><input type="radio" class="txt-radio1" name="brush_cnt" value="2"><span>2회</span></label>
											<label><input type="radio" class="txt-radio1" name="brush_cnt" value="3"><span>3회 이상</span></label>
										</div>
									</div>
								</div>

								<!-- 06 -->
								<div class="box7" style="display: none">
									<div class="in">
										<div class="tit">
											<em>07</em>
											<p><span class="username">김클린</span>님의 평균적인 양치 시간을 알려주세요 </p>
										</div>
										<div class="check-boxs mb70">
											<label><input type="radio" class="txt-radio1" name="brush_time" value="1"><span>약 1분</span></label>
											<label><input type="radio" class="txt-radio1" name="brush_time" value="2"><span>약 2분</span></label>
											<label><input type="radio" class="txt-radio1" name="brush_time" value="3"><span>3분 이상</span></label>
										</div>
									</div>
								</div>

								<!-- 07 -->
								<div class="box8" style="display: none">
									<div class="in">
										<div class="tit">
											<em>08</em>
											<p><span class="username">김클린</span>님의 생활 습관은 어떻게 되시나요?</p>
										</div>
										<div class="tit-desc">* 해당사항 모두 선택해주세요 </div>
										<div class="check-boxs2 mb70">
											<label><input type="checkbox" name="life[]" class="txt-radio2" value="1"><span><i class="ic1"></i>흡연</span></label>
											<label><input type="checkbox" name="life[]" class="txt-radio2" value="2"><span><i class="ic2"></i>주2회 이상<br>음주</span></label>
											<label><input type="checkbox" name="life[]" class="txt-radio2" value="3"><span><i class="ic3"></i>스트레스</span></label>
											<label><input type="checkbox" name="life[]" class="txt-radio2" value="4"><span><i class="ic4"></i>하루 2회<br>이상 간식</span></label>
											<!--<label><input type="checkbox" class="txt-radio2"><span><i class="ic5"></i>임신</span></label>-->
											<label><input type="checkbox" name="life[]" class="txt-radio2" value="5"><span><i class="ic10"></i>탄산음료 섭취</span></label>
											<label><input type="checkbox" name="life[]" class="txt-radio2" value="6"><span><i class="ic7"></i>비염,<br>코골이</span></label>
											<label><input type="checkbox" name="life[]" class="txt-radio2" value="7"><span><i class="ic8"></i>이를 가는<br>습관</span></label>
											<label><input type="checkbox" name="life[]" class="txt-radio2" value="8"><span><i class="ic9"></i>특이사항<br>없음</span></label>
										</div>
									</div>
								</div>

								<!-- 08 -->
								<div class="box9" style="display: none">
									<div class="in">
										<div class="tit">
											<em>09</em>
											<p><span class="username">김클린</span>님의 건강상태는 어떠신가요?</p>
										</div>
										<div class="tit-desc">* 해당사항 모두 선택해주세요 </div>
										<div class="check-boxs2 mb70">
											<label><input type="checkbox" name="health[]" class="txt-radio2" value="1"><span><i class="ic2-1"></i>고혈압</span></label>
											<label><input type="checkbox" name="health[]" class="txt-radio2" value="2"><span><i class="ic2-2"></i>고지혈증</span></label>
											<label><input type="checkbox" name="health[]" class="txt-radio2" value="3"><span><i class="ic2-3"></i>당뇨</span></label>
											<label><input type="checkbox" name="health[]" class="txt-radio2" value="4"><span><i class="ic2-4"></i>우울증</span></label>
											<label><input type="checkbox" name="health[]" class="txt-radio2" value="5"><span><i class="ic2-5"></i>골다골증</span></label>
											<label><input type="checkbox" name="health[]" class="txt-radio2" value="6"><span><i class="ic2-6"></i>간질환</span></label>
											<label><input type="checkbox" name="health[]" class="txt-radio2" value="7"><span><i class="ic2-7"></i>신장질환</span></label>
											<label><input type="checkbox" name="health[]" class="txt-radio2" value="8"><span><i class="ic2-8"></i>뇌질환</span></label>
											<label><input type="checkbox" name="health[]" class="txt-radio2" value="9"><span><i class="ic2-9"></i>구취</span></label>
											<label style="width:100%"><input type="checkbox" name="health[]" value="10" class="txt-radio1"><span>특이사항 없음</span></label>
										</div>
									</div>
								</div>

								<!-- 09 -->
								<div class="box10" style="display: none">
									<div class="in">
										<div class="tit">
											<em>10</em>
											<p><span class="username">김클린</span>님의 현재 구강상태는 어떠신가요? </p>
										</div>
										<div class="tit-desc">* 3개월 이내 / 중복선택 가능  </div>
										<div class="check-boxs mb30">
											<label><input type="checkbox" name="blood[]" value="1" class="txt-radio1"><span>잇몸 출혈</span></label>
											<label><input type="checkbox" name="blood[]" value="2" class="txt-radio1"><span>잇몸 부음</span></label>
											<label><input type="checkbox" name="blood[]" value="3" class="txt-radio1"><span>잇몸 고름</span></label>
										</div>
										<div class="check-boxs mb70">
											<label><input type="checkbox" name="blood[]" value="4" class="txt-radio1"><span>해당사항 없음</span></label>
										</div>
										
									</div>
								</div>


								<!-- 10 -->
								<div class="box11" style="display: none">
									<div class="in">
										<div class="tit">
											<em>11</em>
											<p><span class="username">김클린</span>님은 최근 1년내 치아 스케일링을 받았나요?</p>
										</div>
										<div class="check-boxs mb70">
											<label><input type="radio" class="txt-radio1" name="scaling" value="1"><span>예</span></label>
											<label><input type="radio" class="txt-radio1" name="scaling" value="2"><span>아니오</span></label>
										</div>
									</div>
								</div>

								<!-- 10 -->
								<div class="box12" style="display: none">
									<div class="in">
										<div class="tit">
											<em>12</em>
											<p><span class="username">김클린</span>님의 현재 치아상태는 어떠신가요?</p>
										</div>
										<div class="tit-desc flex">
											<p>* 해당되는 증상을 선택 후, 치아 위치를 선택해주세요.</p>
<!--											<div class="btns">
												<a href="#" class="btn-under" id="BtnTeethChoice">치아선택 레이어 임시 버튼</a>
												<a href="#" class="btn-under">선택 초기화</a>
											</div> -->
										</div>
										<div class="check-boxs5 mb30">
											<label><input type="checkbox" class="txt-radio3" name="tooth[]" value="1"><span>사랑니</span></label>
											<label><input type="checkbox" class="txt-radio3" name="tooth[]" value="2"><span>시린이</span></label>
											<label><input type="checkbox" class="txt-radio3" name="tooth[]" value="3"><span>삐뚠이</span></label>
											<label><input type="checkbox" class="txt-radio3" name="tooth[]" value="4"><span>흔들림</span></label>
											<label><input type="checkbox" class="txt-radio3" name="tooth[]" value="5"><span>충치</span></label>
											<label><input type="checkbox" class="txt-radio3" name="tooth[]" value="6"><span>임플란트</span></label>
										</div>
										<div class="check-boxs mb70">
											<label><input type="checkbox" class="txt-radio1" name="tooth[]" value="7"><span>특이사항 없음</span></label>
										</div>
									</div>
								</div>

								<!-- 11 -->
								<div class="box13" style="display: none">
									<div class="in">
										<div class="tit">
											<em>13</em>
											<p><span class="username">김클린</span>님이 우선적으로 해결하고 싶은 것은 어떤 것인가요? </p>
										</div>
										<div class="check-boxs type2 mb70">
											<label><input type="radio" class="txt-radio1 " name="concern" value="1"><span class="text-left">충치 예방</span></label>
											<label><input type="radio" class="txt-radio1 " name="concern" value="2"><span class="text-left">잇몸 질환</span></label>
											<label><input type="radio" class="txt-radio1 " name="concern" value="3"><span class="text-left">시린니</span></label>
											<label><input type="radio" class="txt-radio1 " name="concern" value="4"><span class="text-left">미백</span></label>
										</div>
									</div>
								</div>
								</form>
							</div>
							<div class="btn-box">
								<button class="btn btn-type2" onClick="javascript:fN_survey('back');">이전</button>
								<button class="btn btn-type1" onClick="javascript:fN_survey('next');">다음</button>
							</div>

						</div>
						
						<!-- 치아선택 -->
						<div class="teeth-choice">
							<div class="head">
								<div class="box1">
									<a href="#" class="btn-back"></a>
									<strong>치아선택</strong>
									<span>&lt;사랑니&gt;</span>
									<div class="desc">
										<p>* 사랑니에 해당하는 치아 부위를 선택해주세요.</p>
										<div class="btns">
											<a href="#" class="btn-under">선택 초기화</a>
										</div>
									</div>
								</div>
							</div>
							<div class="img"><img src="/res/img/diagnosis/teeth_off.png"></div>
							<div class="box2">
								<span>치아<br>선택</span>
								<input type="text" class="inp1" value="윗니 / 좌측 / 어금니,  아랫니 / 우측 / 어금니" readonly>
							</div>
							
							<div class="btn-box">
								<button class="btn btn-type2" id="BtnTeethChoiceBack">취소</button>
								<button class="btn btn-type1" id="BtnTeechChoiceEnd">선택완료</button>
							</div>
							
							
							<button class="btn-close"></button>
						</div>
						<!-- // 치아선택 -->

					</div>
				</div>
				<div class="bg"></div>
			</div>
		</div>
		<!-- // 진단 -->
	</div>

<script>
$(window).on("beforeunload", function(){
	if($('#input_user').css('display') == 'block') {
		return "진단을 중단하고 페이지를 이동하시겠습니까?";
	}
});

$(document).ready(function(e) {
    $('#user_name').on('keyup', function() {
		$('.username').html($(this).val());
	});
	
	$('input[name="life[]"]').on('click', function() {
		if($(this).val() == '8') {
			$('input[name="life[]"]').prop('checked', false);
			$(this).prop('checked', true);	
		}
		else {
			$('input[name="life[]"]').eq(7).prop('checked', false);	
		}
	});

	$('input[name="health[]"]').on('click', function() {
		if($(this).val() == '10') {
			$('input[name="health[]"]').prop('checked', false);
			$(this).prop('checked', true);	
		}
		else {
			$('input[name="health[]"]').eq(9).prop('checked', false);	
		}
	});

	$('input[name="blood[]"]').on('click', function() {
		if($(this).val() == '4') {
			$('input[name="blood[]"]').prop('checked', false);
			$(this).prop('checked', true);	
		}
		else {
			$('input[name="blood[]"]').eq(3).prop('checked', false);	
		}
	});

	$('input[name="tooth[]"]').on('click', function() {
		if($(this).val() == '7') {
			$('input[name="tooth[]"]').prop('checked', false);
			$(this).prop('checked', true);	
		}
		else {
			$('input[name="tooth[]"]').eq(6).prop('checked', false);	
		}
	});
	
});
		/* 
			화면 이동 방식은 임의로 제가 임시로 처리했습니다.
			상단 스텝(기본정보 - 양치습관 - .....) active 클래스 넣는것과 프로그레스바(퍼센트)는 개발자분께 부탁드립니다~
		*/
			
		// 진단 임시 스크립트
			
$('.btn-start').click(function(){
	gtag('event', 'conversion', {'send_to': 'AW-10803290940/EDz-CJnl6pEDELzGtJ8o'});
	
	$('.survey-container').fadeIn(300);
	$('body').addClass('survey-on'); // 배경 스크롤 없애줌
	$('#progress_bar').css('width','0');
	$('#progress_step1').addClass('active');
})
		
var idx = 0;
function fN_survey(control){
	if(control == "back") {
		// 이전
		idx = idx - 1;
	}
	else{
		if(idx == 0 && $('#user_name').val() == '') {
			showAlert('error', '이름을 입력해 주세요.');
			return;	
		}
		else if(idx == 1 && (typeof($('input[name=user_sex]:checked').val()) == 'undefined' || $('input[name=user_sex]:checked').val() == '')) {
			showAlert('error', '성별을 선택해 주세요.');
			return;	
		}
		else if(idx == 2) {
			if($('#user_age').val() == '') {
				showAlert('error', '나이를 입력해 주세요.');
				return;	
			}
			else if($('#user_age').val() < 10 || $('#user_age').val() > 120) {
				showAlert('error', '나이는 10세에서 120세까지만 입력가능합니다.');
				return;	
			}
		}
		else if(idx == 3) {
			if($('#user_height').val() == '') {
				showAlert('error', '키를 입력해 주세요.');
				return;	
			}
			else if($('#user_height').val() < 100 || $('#user_height').val() > 300) {
				showAlert('error', '키는 100cm에서 300cm까지 입력가능합니다.');
				return;	
			}
			else if($('#user_weight').val() == '') {
				showAlert('error', '몸무게를 입력해 주세요.');
				return;	
			}
			else if($('#user_weight').val() < 20 || $('#user_weight').val() > 200) {
				showAlert('error', '몸무게는 20kg 에서 200kg까지 입력가능합니다.');
				return;	
			}
		}
		else if(idx == 4 && typeof($('input[name=structure]:checked').val()) == 'undefined') {
			showAlert('error', '구강구조를 선택해 주세요.');
			return;	
		}
		else if(idx == 5 && typeof($('input[name=brush_cnt]:checked').val()) == 'undefined') {
			showAlert('error', '양치 횟수를 선택해 주세요.');
			return;	
		}
		else if(idx == 6 && typeof($('input[name=brush_time]:checked').val()) == 'undefined') {
			showAlert('error', '양치 시간을 선택해 주세요.');
			return;	
		}
		else if(idx == 7) {
			var bCheck = false;
			$('input[name="life[]"]').each(function(index, element) {
                if($(this).is(':checked')) bCheck = true;
            });
			
			if(!bCheck) {
				showAlert('error', '생활습관을 선택해 주세요.');
				return;	
			}
		}
		else if(idx == 8) {
			var bCheck = false;
			$('input[name="health[]"]').each(function(index, element) {
                if($(this).is(':checked')) bCheck = true;
            });
			
			if(!bCheck) {
				showAlert('error', '건강상태를 선택해 주세요.');
				return;	
			}
		}
		else if(idx == 9) {
			var bCheck = false;
			$('input[name="blood[]"]').each(function(index, element) {
                if($(this).is(':checked')) bCheck = true;
            });
			
			if(!bCheck) {
				showAlert('error', '구강상태를 선택해 주세요.');
				return;	
			}
		}
		else if(idx == 10 && typeof($('input[name=scaling]:checked').val()) == 'undefined') {
			showAlert('error', '스케일링 여부를 선택해 주세요.');
			return;	
		}
		else if(idx == 11) {
			var bCheck = false;
			$('input[name="tooth[]"]').each(function(index, element) {
                if($(this).is(':checked')) bCheck = true;
            });
			
			if(!bCheck) {
				showAlert('error', '치아상태를 선택해 주세요.');
				return;	
			}
		}
		else if(idx == 12 && typeof($('input[name=concern]:checked').val()) == 'undefined') {
			showAlert('error', '우선적으로 해결하고 싶으신 항목을 선택해 주세요.');
			return;	
		}

		// 다음
		idx = idx + 1;
	};
	
	if(idx < 0){
		// 첫번째일때
		idx = 0;
		var msg = {msg: '진단을 취소하시겠습니까?', cancel:'닫기', confirm:'진단취소'};
		showConfirm(msg
					, function() {	
									$('.survey-container').fadeOut(300);
									$('body').removeClass('survey-on'); // 배경 스크롤 없애줌
					});
		return false;
	} else if(idx > 12) {
		// 마지막일때
		idx = 12;
		fnSave();
	}
	
	var per = Math.round(idx / 12 * 100);
	$('#progress_bar').css('width', per + '%');
	$('#progress_step ul li').removeClass('active');
	if(idx <= 4) {
		$('#progress_step1').addClass('active');
	}
	else if(idx > 4 && idx <= 6) {
		$('#progress_step2').addClass('active');
	}
	else if(idx > 6 && idx <= 7) {
		$('#progress_step3').addClass('active');
	}
	else if(idx > 7 && idx <= 9) {
		$('#progress_step4').addClass('active');
	}
	else if(idx > 9) {
		$('#progress_step5').addClass('active');
	}
	$('.survey-wrap .boxs > form > div').hide();
	$('.survey-wrap .boxs > form > div').eq(idx).fadeIn(300);
	$('.survey-wrap').addClass('on'+idx);
			//console.log(idx);
}
			
function fnSave() {
	$.ajax({
		url: "/diagnosis/ajaxSaveResult",
		type: 'POST',
		dataType : 'json',
		async: true,
		data: $('#frmDiagnosis').serialize(),
		success: function(res, textStatus, jqXHR){
			if(res.status == 'succ') {
				$('#input_user').hide();
				location.href = '/diagnosis/detail?seq=' + res.msg;
				$('.survey-container').fadeOut(300);
				$('body').removeClass('survey-on'); // 배경 스크롤 없애줌
			}
			else {
				showAlert('error', res.msg);	
			}
		},
		error: function(request,status,error){
				alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
		}
	});
}
		// 윗니 선택
function teethUp(){
	$('#Teeth').attr('src', '/res/img/diagnosis/teeth_up.png');
}
			
		// 아랫니 선택
function teethDown(){
	$('#Teeth').attr('src', '/res/img/diagnosis/teeth_down.png');
}
			
$('.teeth-choice .btn-close, .teeth-choice .btn-back, #BtnTeethChoiceBack, #BtnTeechChoiceEnd').click(function(){
	$('.teeth-choice').hide();
	$('.survey-box .body').show();
	$('.survey-box .step').show();
	return false;
})
$('#BtnTeethChoice').click(function(){
	$('.teeth-choice').show();
	$('.survey-box .body').hide();
	$('.survey-box .step').hide();
	return false;
})
			
</script>
