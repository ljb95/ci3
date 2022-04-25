	<div class="sub-head diagnosis-head">
		<div class="inner">
			<h2 class="h2">진단</h2>
		</div>
	</div>
	
	<div class="inner">
		<div class="dia-end">
			<div class="box2">
				<h3><span><?php echo $info['user_name']; ?></span>님의  구강 건강 상태는?</h3>
				<div class="h3-desc">
					<?php echo $info['user_name']; ?>님은 치아의 관리를 위해 많은 노력을 기울이고 있으시네요. <br>
					하지만 생활 습관의 불균형에 의해 치아에도 좋지 않은 영향이 끼쳐질수 있습니다.   
				</div>
		
				<div class="in">
					<div class="left">
						<dl>
							<dt>구강관리</dt>
							<dd>
								<div class="progress-wrap">
									<div class="progress-box">
										<div class="pos">
											<em style="width:<?php echo $info['score_oral']; ?>%"></em>
										</div>
									</div>
									<div class="val <?php echo $info['score_oral'] == 100 ? 'last' : ($info['score_oral'] == 0 ? 'first' : ''); ?>" style="left: <?php echo $info['score_oral']; ?>%">
										<strong><?php echo $info['score_oral']; ?></strong>
										<p style="background:none">
                                         <?php
											if($info['score_oral'] >= 100) echo '완벽해요';
											else if($info['score_oral'] >= 80) echo '양호';
											else if($info['score_oral'] >= 60) echo '개선필요';
											else if($info['score_oral'] >= 40) echo '집중관리필요';
											else if($info['score_oral'] >= 20) echo '경고';
											else echo '위험';
                                        ?>
                                        </p>
									</div>
									<div class="start">0</div>
									<div class="end">100</div>
								</div>
							</dd>
						</dl>
						<dl>
							<dt>잇몸건강</dt>
							<dd>
								<div class="progress-wrap">
									<div class="progress-box">
										<div class="pos">
											<em style="width:<?php echo $info['score_gum']; ?>%"></em>
										</div>
									</div>
											<!-- 100% 는 last 클래스 0% 는 first 클래스를 넣어서 디자인 이슈 해결 -->
									<div class="val <?php echo $info['score_gum'] == 100 ? 'last' : ($info['score_gum'] == 0 ? 'first' : ''); ?>" style="left: <?php echo $info['score_gum']; ?>%">
										<strong><?php echo $info['score_gum']; ?></strong>
										<p style="background:none">
                                        <?php
											if($info['score_gum'] >= 100) echo '완벽해요';
											else if($info['score_gum'] >= 80) echo '양호';
											else if($info['score_gum'] >= 60) echo '개선필요';
											else if($info['score_gum'] >= 40) echo '집중관리필요';
											else if($info['score_gum'] >= 20) echo '경고';
											else echo '위험';
                                        ?>
                                        </p>
									</div>
									<div class="start">0</div>
									<div class="end">100</div>
								</div>
							</dd>
						</dl>
						<dl>
							<dt>치아건강</dt>
							<dd>
								<div class="progress-wrap">
									<div class="progress-box">
										<div class="pos">
											<em style="width:<?php echo $info['score_tooth']; ?>%"></em>
										</div>
									</div>
									<div class="val <?php echo $info['score_tooth'] == 100 ? 'last' : ($info['score_tooth'] == 0 ? 'first' : ''); ?>" style="left: <?php echo $info['score_tooth']; ?>%">
										<strong><?php echo $info['score_tooth']; ?></strong>
										<p style="background:none">
                                        <?php
											if($info['score_tooth'] >= 100) echo '완벽해요';
											else if($info['score_tooth'] >= 80) echo '양호';
											else if($info['score_tooth'] >= 60) echo '개선필요';
											else if($info['score_tooth'] >= 40) echo '집중관리필요';
											else if($info['score_tooth'] >= 20) echo '경고';
											else echo '위험';
                                        ?>
                                        </p>
									</div>
									<div class="start">0</div>
									<div class="end">100</div>
								</div>
							</dd>
						</dl>
					</div>
					<div class="right">
						<h4>치아점수표</h4>
						<div class="list">
							<dl>
								<dt>100점</dt>
								<dd>
									<h5>완벽한 치아</h5>
									<p>치아관리를 잘 하고 있습니다. 지금처럼 유지하는 것이 중요해요</p>
								</dd>
							</dl>
							<dl>
								<dt>80 ~ 100점</dt>
								<dd>
									<h5>양호</h5>
									<p>좋은 습관을 가지고 있습니다. 지금도 좋지만 완벽한 치아를 위해선 개선할 부분이 있어요.</p>
								</dd>
							</dl>
							<dl>
								<dt>60 ~ 80점</dt>
								<dd>
									<h5>개선필요</h5>
									<p>더 좋은 습관을 만들어갈 필요가 있어보여요.</p>
								</dd>
							</dl>
							<dl>
								<dt>40 ~ 60점</dt>
								<dd>
									<h5>집중관리필요</h5>
									<p>아슬아슬. 안 좋은 습관들이 많이 보입니다. <br>
하나씩 좋은 습관들로 바꿔볼까요?</p>
								</dd>
							</dl>
							<dl>
								<dt>20 ~ 40점</dt>
								<dd>
									<h5 class="red">경고</h5>
									<p>이대로면 점점 안좋아질 거에요. 확실한 관리가 필요합니다.</p>
								</dd>
							</dl>
							<dl>
								<dt>20점 이하</dt>
								<dd>
									<h5 class="red">위험</h5>
									<p>큰일입니다. 상황이 안 좋지만 지금이라도 늦지 않았습니다. <br>함께 관리해봐요.</p>
								</dd>
							</dl>
						</div>
					</div>
				</div>
			</div>
			
			
			
			<div class="box1">
				<div class="tit">클린디의 <span>추천</span>은?</div>
				<div class="in-box info_brush">
					<div class="img">
                    	<img src="<?php echo CDN_URL . 'prod/diagnosis/1. 클린디의 추천 - 칫솔 (완)/' . $info['brush_line_name'] . ' ' . $info['brush_strong_name'] . ' ' . $info['brush_shape_name'] . '.jpg'; ?>" style="width:40%">
                    	<img src="<?php echo CDN_URL . 'prod/diagnosis/1. 클린디의 추천 - 칫솔 (완)/' . $info['brush_shape_name'] . '.png'; ?>" style="width:50%; margin-left:5%;">
                    </div>
					<div class="info">
						<div class="tit2">
							<strong>클린디 칫솔</strong>
							<span>
								<?php echo $info['brush_line_name']; ?>  -  <?php echo $info['brush_strong_name']; ?>  -  <?php echo $info['brush_shape_name']; ?>
                            </span>
						</div>
						<ul>
                        <?php
							$brush_line = $info['brush_line_name'];
							$brush_shape1 = $info['brush_shape_name'];
							$brush_shape2 = '';
							$brush_id1 = '';
							$brush_id2 = '';
							if($info['brush_shape_name'] == '탄력모') {
								$brush_shape2 = '기능모';
								$brush_id1 = '2';
								$brush_id2 = '1';
						?>
							<li><span>·</span><span>모의 한쪽은 미세하게, 한쪽은 둥글게 라운딩 처리하는 듀얼 기술 적용</span></li>
							<li><span>·</span><span>치아와 잇몸 사이 케어 + 치아 표면 세정 (치아면은 시원하게, 잇몸라인은 부드럽게 케어)</span></li>
							<li><span>·</span><span>구취 제거, 프라그, 치석제거에 용이</span></li>
                        <?php
							}
							else if($info['brush_shape_name'] == '미세모') {
								$brush_shape2 = '초극세모';
								$brush_id1 = '3';
								$brush_id2 = '4';
						?>
							<li><span>·</span><span>양쪽 모 끝을 0.01mm로 얇고 가늘게 가공하여 미세화</span></li>
							<li><span>·</span><span>일반 둥근모와 달리 미세모는 모 끝이 좁아 치주 포켓에 자극없이 도달하여 케어</span></li>
							<li><span>·</span><span>부드러운 세정은 물론 마사지 기능까지 더함</span></li>
                        <?php
							}
							else if($info['brush_shape_name'] == '초극세모') {
								$brush_shape2 = '미세모';
								$brush_id1 = '4';
								$brush_id2 = '3';
						?>
							<li><span>·</span><span>기존 칫솔모와 달리 모 끝이 4가닥으로 나누어지는 테트라팁 모를 적용</span></li>
							<li><span>·</span><span>끝이 4가닥으로 갈라지는 초극세 특수 미세모가 양치 중에는 부드럽지만 양치 후에는 스케일링 한 듯 강력한 개운함을 느낌</span></li>
							<li><span>·</span><span>치약을 머금는 특수 패턴이 적용되어 치약 유효 성분을 효과적으로 전달</span></li>
                        <?php
							}
							else if($info['brush_shape_name'] == '기능모') {
								$brush_shape2 = '탄력모';
								$brush_id1 = '1';
								$brush_id2 = '2';
						?>
							<li><span>·</span><span>일반적인 원형이 아닌, 마름모의 단면 모인 특수 미세모와 일반 미세모를 이중으로 사용</span></li>
							<li><span>·</span><span>특수 가공된 미세모와 일반 미세모가 이중으로 식모되어 빈틈없이 세정</span></li>
							<li><span>·</span><span>기능적으로 설계된 칫솔모가 복잡한 구강구조에도 부드럽고 확실하게 치태 제거</span></li>
                        <?php
							}
						?>
						</ul>
					</div>
				</div>
				<div class="in-box">
                    <?php
						$toothpaste1 = '';
						$toothpaste2 = '';
						$toothpaste3 = '';
						$toothpaste4 = '';
						$toothpaste1_name = '';
						$toothpaste2_name = '';
						$toothpaste3_name = '';
						$toothpaste4_name = '';
						if($info['is_concern'] == '1') {
							$toothpaste1 = '1. 활짝치약 충치예방 100g.png';
							$toothpaste2 = '6. 살짝치약 잇몸질환 30g.png';
							$toothpaste3 = '7. 반짝치약 미백 20g.png';
							$toothpaste4 = '8. 달짝치약 시린이 30g.png';
							$toothpaste1_name = '활짝치약 100g';
							$toothpaste2_name = '살짝치약 30g';
							$toothpaste3_name = '반짝치약 20g';
							$toothpaste4_name = '달짝치약 30g';
					?>
					<div class="img">
                    	<img src="<?php echo CDN_URL . 'prod/diagnosis/2. 클린디의 추천 - 치약 (완)/1-1. 활짝치약 충치예방.png'; ?>" style="width:15%">
                    	<img src="<?php echo CDN_URL . 'prod/diagnosis/2. 클린디의 추천 - 치약 (완)/1-2. 활짝치약 충치예방.png'; ?>" style="width:60%; margin-left:15%;">
                    </div>
					<div class="info">
						<div class="tit2">
							<strong>활짝치약</strong>
							<span>충치예방</span>
						</div>
						<ul>
							<li><span>·</span><span>불소 함유량 1000ppm 으로 충치예방!</span></li>
							<li><span>·</span><span>L-멘톨, 박하유가 첨가되어 더욱 청량하고 상쾌하며 구취제거에도 효과적</span></li>
						</ul>
					</div>
                    <?php
						}
						else if($info['is_concern'] == '2') {
							$toothpaste1 = '2. 살짝치약 잇몸질환 100g.png';
							$toothpaste2 = '5. 활짝치약 충치예방 30g.png';
							$toothpaste3 = '7. 반짝치약 미백 20g.png';
							$toothpaste4 = '8. 달짝치약 시린이 30g.png';
							$toothpaste1_name = '살짝치약 100g';
							$toothpaste2_name = '활짝치약 30g';
							$toothpaste3_name = '반짝치약 20g';
							$toothpaste4_name = '달짝치약 30g';
					?>
					<div class="img">
                    	<img src="<?php echo CDN_URL . 'prod/diagnosis/2. 클린디의 추천 - 치약 (완)/2-1. 살짝치약 잇몸질환.png'; ?>" style="width:15%">
                    	<img src="<?php echo CDN_URL . 'prod/diagnosis/2. 클린디의 추천 - 치약 (완)/2-2. 살짝치약 잇몸질환.png'; ?>" style="width:60%; margin-left:15%;">
                    </div>
					<div class="info">
						<div class="tit2">
							<strong>살짝치약</strong>
							<span>잇몸질환</span>
						</div>
						<ul>
							<li><span>·</span><span>잇몸질환 예방 및 치석침착 예방에 효과적인 성분 함유</span></li>
							<li><span>·</span><span>토코페롤아세테이트 : 비타민E, 잇몸질환 예방 효과</span></li>
							<li><span>·</span><span>피로인산나트륨 : 치석침착예방</span></li>
							<li><span>·</span><span>프로폴리스추출물 : 향균작용과 항염증 효과</span></li>
						</ul>
					</div>
                    <?php
						}
						else if($info['is_concern'] == '3') {
							$toothpaste1 = '4. 달짝치약 시린이 100g.png';
							$toothpaste2 = '5. 활짝치약 충치예방 30g.png';
							$toothpaste3 = '6. 살짝치약 잇몸질환 30g.png';
							$toothpaste4 = '7. 반짝치약 미백 20g.png';
							$toothpaste1_name = '달짝치약 100g';
							$toothpaste2_name = '활짝치약 30g';
							$toothpaste3_name = '살짝치약 30g';
							$toothpaste4_name = '반짝치약 20g';
					?>
					<div class="img">
                    	<img src="<?php echo CDN_URL . 'prod/diagnosis/2. 클린디의 추천 - 치약 (완)/4-1. 달짝치약 시린이.png'; ?>" style="width:15%">
                    	<img src="<?php echo CDN_URL . 'prod/diagnosis/2. 클린디의 추천 - 치약 (완)/4-2. 달짝치약 시린이.png'; ?>" style="width:60%; margin-left:15%;">
                    </div>
					<div class="info">
						<div class="tit2">
							<strong>달짝치약</strong>
							<span>시린이예방</span>
						</div>
						<ul>
							<li><span>·</span><span>시린이 예방 및 완화에 효과적인 성분 함유</span></li>
							<li><span>·</span><span>하이트록시아파타이트 : 뼈와 치아의 주성분으로 치아표면을 메꿔줌</span></li>
							<li><span>·</span><span>인산삼칼슘 : 시린이 보호</span></li>
					</ul>
					</div>
                    <?php
						}
						else if($info['is_concern'] == '4') {
							$toothpaste1 = '3. 반짝치약 미백 90g.png';
							$toothpaste2 = '5. 활짝치약 충치예방 30g.png';
							$toothpaste3 = '6. 살짝치약 잇몸질환 30g.png';
							$toothpaste4 = '8. 달짝치약 시린이 30g.png';
							$toothpaste1_name = '반짝치약 90g';
							$toothpaste2_name = '활짝치약 30g';
							$toothpaste3_name = '살짝치약 30g';
							$toothpaste4_name = '달짝치약 30g';
					?>
					<div class="img">
                    	<img src="<?php echo CDN_URL . 'prod/diagnosis/2. 클린디의 추천 - 치약 (완)/3-1. 반짝치약 미백.png'; ?>" style="width:15%">
                    	<img src="<?php echo CDN_URL . 'prod/diagnosis/2. 클린디의 추천 - 치약 (완)/3-2. 반짝치약 미백.png'; ?>" style="width:60%; margin-left:15%;">
                    </div>
					<div class="info">
						<div class="tit2">
							<strong>반짝치약</strong>
							<span>미백</span>
						</div>
						<ul>
							<li><span>·</span><span>치과에서도 치아미백을 위해 사용하는 원료 함유</span></li>
							<li><span>·</span><span>과산화수소 (35% 함유) : 식약처 허가 치아 미백 성분으로 변색층을 제거하여 치아를 하얗게 유지하는데 도움을 줌</span></li>
						</ul>
					</div>
					<?php
						}
					?>
				</div>
			</div>
			
            <form id="frmSave" onSubmit="return false;">
			<?php
				$color1 = rand(1, 4);
				$color2 = rand(1, 4);
				if($member['is_starter'] == 'n') {
			?>
			<div class="box3 starter_wrap">
				<div class="title">체험하기(스타터패키지)</div>
				<div class="body">
					<div class="desc1">스타터패키지는 최소 1회만 구매 가능합니다.<br>스타터패키지를 구매하신 적이 있으시다면 <a href="#" onclick="javascript:fnRemoveStarter(); return false;">여기</a>를 눌러주세요.</div>
					<div class="prd-list">
						<div class="item">
							<div class="num">
								<em>1</em>
								<span>추천 칫솔1</span>
							</div>
							<div class="img">
								<span><img src="<?php echo CDN_URL . 'prod/diagnosis/3. 체험하기 - 추천칫솔 (완)/' . $brush_line . ' ' . $brush_shape1 ?>.png"></span>
							</div>
							<div class="name"><?php echo $brush_line; ?> / <?php echo $brush_shape1; ?></div>
						</div>
						<div class="item">
							<div class="num">
								<em>2</em>
								<span>추천 칫솔2</span>
							</div>
							<div class="img">
								<span><img src="<?php echo CDN_URL . 'prod/diagnosis/3. 체험하기 - 추천칫솔 (완)/' . $brush_line . ' ' . $brush_shape2 ?>.png"></span>
							</div>
							<div class="name"><?php echo $brush_line; ?> / <?php echo $brush_shape2; ?></div>
						</div>
						<div class="item">
							<div class="num">
								<em>3</em>
								<span>추천 치약</span>
							</div>
							<div class="img img_toothpaste">
								<span><img src="<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/' . $toothpaste1; ?>"></span>
								<span class="img_toothpaste"><img src="<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/' . $toothpaste2; ?>"></span>
								<span class="img_toothpaste"><img src="<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/' . $toothpaste3; ?>"></span>
								<span class="img_toothpaste"><img src="<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/' . $toothpaste4; ?>"></span>
							</div>
							<div class="name">
								<span><?php echo $toothpaste1_name; ?></span>
								<span><?php echo $toothpaste2_name; ?></span>
								<span><?php echo $toothpaste3_name; ?></span>
								<span><?php echo $toothpaste4_name; ?></span>
							</div>
						</div>
					</div>
					<div class="prd-colors">
						<label><input type="radio" class="radio-color white" name="ra1" diabled><em></em></label>
						<label><input type="radio" class="radio-color gray" name="ra1" diabled><em></em></label>
						<label><input type="radio" class="radio-color black" name="ra1" diabled><em></em></label>
						<label><input type="radio" class="radio-color blue" name="ra1" diabled><em></em></label>
					</div>
					<div class="desc2">
                    	색상은 랜덤 발송됩니다.<br>
						색상 선택 및 제품 구성 변경은 아래의 옵션 변경하기에서 바꿀 수 있습니다.<br>
						스타터패키지 구성은 1인 기준 <strong>2개월 치</strong> 입니다.
                    </div>
					<div class="flip">
						<a href="#" class="btn-flip">옵션 변경하기</a>
					</div>

					<div class="prd-opt">
						<div class="item">
                            <div class="img">
                                <img src="/res/img/diagnosis/prd1.png" id="start_brush_img1" style="max-height:80%;">
                            </div>
							<div class="info">
								<div class="name">칫솔</div>
								<div class="opt1">
									<label><input type="radio" class="radio-txt" name="ra2" value="강한모" <?php echo $info['brush_strong_name'] == '강한모' ? 'checked' : ''; ?>><span>강한모</span></label>
									<label><input type="radio" class="radio-txt" name="ra2" value="부드러운모" <?php echo $info['brush_strong_name'] == '부드러운모' ? 'checked' : ''; ?>><span>부드러운모</span></label>
								</div>
								<div class="s-tit">
									<span id="start_brush_name1"><?php echo $brush_shape1; ?></span>
                                    <input type="hidden" name="brush_name1" value="<?php echo $brush_shape1; ?>" />
                                    <input type="hidden" name="brush_id1" value="<?php echo $brush_id1; ?>" />
                                </div>
								<div class="opt2">
									<label>
                                    	<input type="radio" class="radio-mo radio-mo1" name="ra3" value="S 4줄" <?php echo $info['brush_line_name'] == '4줄' ? 'checked' : ''; ?>>
                                    	<span class="mo1" id="mo11" checked><em>S</em></span>
                                    </label>
									<label>
                                    	<input type="radio" class="radio-mo radio-mo1" name="ra3" value="M 5줄" <?php echo $info['brush_line_name'] == '5줄' ? 'checked' : ''; ?>>
                                        <span class="mo2" id="mo12"><em>M</em></span>
                                    </label>
									<label>
                                    	<input type="radio" class="radio-mo radio-mo1" name="ra3" value="L 6줄" <?php echo $info['brush_line_name'] == '6줄' ? 'checked' : ''; ?>>
                                    	<span class="mo3" id="mo13"><em>L</em></span>
                                    </label>
								</div>
								<div class="s-tit">
                                	<span>색상 -</span>
                                    <span id="color1">
                                    <?php 
										if($color1 == 1) echo '화이트';
										else if($color1 == 2) echo '그레이';
										else if($color1 == 3) echo '블랙';
										else if($color1 == 4) echo '블루';	
									?>
                                    </span>
                                </div>
								<div class="prd-colors">
									<label><input type="radio" class="radio-color white" name="ra4" value="화이트" <?php echo $color1 == 1 ? 'checked' : ''; ?>><em></em></label>
									<label><input type="radio" class="radio-color gray" name="ra4" value="그레이" <?php echo $color1 == 2 ? 'checked' : ''; ?>><em></em></label>
									<label><input type="radio" class="radio-color black" name="ra4" value="블랙"  <?php echo $color1 == 3 ? 'checked' : ''; ?>><em></em></label>
									<label><input type="radio" class="radio-color blue" name="ra4" value="블루" <?php echo $color1 == 4 ? 'checked' : ''; ?>><em></em></label>
								</div>
							</div>
                            <div class="img">
                                <img src="/res/img/diagnosis/prd1.png" id="start_brush_img2" style="max-height:80%">
                            </div>
                            <div class="info">
								<div class="s-tit">
									<span id="start_brush_name2"><?php echo $brush_shape2; ?></span>
                                    <input type="hidden" name="brush_name2" value="<?php echo $brush_shape2; ?>" />
                                    <input type="hidden" name="brush_id2" value="<?php echo $brush_id2; ?>" />
                                </div>
								<div class="opt2">
									<label>
                                    	<input type="radio" class="radio-mo radio-mo2" name="ra5" value="S 4줄" <?php echo $info['brush_line_name'] == '4줄' ? 'checked' : ''; ?>>
                                    	<span class="mo1" id="mo21" checked><em>S</em></span>
                                    </label>
									<label>
                                    	<input type="radio" class="radio-mo radio-mo2" name="ra5" value="M 5줄" <?php echo $info['brush_line_name'] == '5줄' ? 'checked' : ''; ?>>
                                        <span class="mo2" id="mo22"><em>M</em></span>
                                    </label>
									<label>
                                    	<input type="radio" class="radio-mo radio-mo2" name="ra5" value="L 6줄" <?php echo $info['brush_line_name'] == '6줄' ? 'checked' : ''; ?>>
                                    	<span class="mo3" id="mo23"><em>L</em></span>
                                    </label>
								</div>
								<div class="s-tit">
                                	<span>색상 -</span>
                                    <span id="color2">
                                    <?php 
										if($color2 == 1) echo '화이트';
										else if($color2 == 2) echo '그레이';
										else if($color2 == 3) echo '블랙';
										else if($color2 == 4) echo '블루';	
									?>
                                    </span>
                                </div>
								<div class="prd-colors">
									<label><input type="radio" class="radio-color white" name="ra6" value="화이트" <?php echo $color2 == 1 ? 'checked' : ''; ?>><em></em></label>
									<label><input type="radio" class="radio-color gray" name="ra6" value="그레이" <?php echo $color2 == 2 ? 'checked' : ''; ?>><em></em></label>
									<label><input type="radio" class="radio-color black" name="ra6" value="블랙" <?php echo $color2 == 3 ? 'checked' : ''; ?>><em></em></label>
									<label><input type="radio" class="radio-color blue" name="ra6" value="블루" <?php echo $color2 == 4 ? 'checked' : ''; ?>><em></em></label>
								</div>
								
							</div>
						</div>
						<div class="item">
							<div class="img"><img src="<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/' . $toothpaste1; ?>" id="toothpaste_main"></div>
							<div class="info">
								<div class="name">치약</div>
								<div class="opt-desc">치약은 100g 1개, 30g 3개로 클린디 치약 4종이 각각 1개씩 모두 포함됩니다.<br>100g치약을 선택하시면 나머지 30g 치약은 자동으로  선택됩니다.<br>(반짝치약은 90g, 20g입니다.)</div>
								<div class="opt1">
									<label><input type="radio" class="radio-txt" name="ra8" value="1" <?php echo $info['is_concern'] == '1' ? 'checked' : ''; ?>><span>활짝(충치예방)</span></label>
									<label><input type="radio" class="radio-txt" name="ra8" value="2" <?php echo $info['is_concern'] == '2' ? 'checked' : ''; ?>><span>살짝(잇몸)</span></label>
									<label><input type="radio" class="radio-txt" name="ra8" value="3" <?php echo $info['is_concern'] == '3' ? 'checked' : ''; ?>><span>달짝(시린이)</span></label>
									<label><input type="radio" class="radio-txt" name="ra8" value="4" <?php echo $info['is_concern'] == '4' ? 'checked' : ''; ?>><span>반짝(미백)</span></label>
								</div>
								<div class="opt-thums">
									<span><img src="<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/' . $toothpaste1; ?>" id="toothpaste_sel1"></span>
									<span><img src="<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/' . $toothpaste2; ?>" id="toothpaste_sel2"></span>
									<span><img src="<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/' . $toothpaste3; ?>" id="toothpaste_sel3"></span>
									<span><img src="<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/' . $toothpaste4; ?>" id="toothpaste_sel4"></span>
								</div>
							</div>
						</div>
					</div>
					
					<div class="total">
						<dl>
							<dt>총 6개 상품</dt>
							<dd class="per">32%</dd>
						</dl>
						<dl>
							<dt>이번 결제 금액</dt>
							<dd>
								<del>13,000<small>원</small></del>
								<strong>10,900원</strong>
							</dd>
						</dl>
					</div>
                    
                    
				</div>

                <div class="btn-box" id="starter_btn_wrap">
                    <a href="#" class="btn btn-type1 " onclick="javascipt:fnShowSubscribe(); return false;">정기배송 구독하기</a>
                    <a href="#" class="btn btn-type1 starter_wrap" onclick="javascript:fnShowPayment('starter'); return false;">체험하기만 구매하기</a>
                </div>
			</div>
			<!-- // box2 -->
            <?php } ?>
			
			<div class="box3" id="subscribe_wrap" style="display:none">
				<div class="title">구독하기</div>
				<div class="body">
					<div class="desc1">구독은 15,000원 이상 부터 가능합니다.
					<div class="prd-list">
						<div class="item">
							<div class="num">
								<em>1</em>
								<span>추천 칫솔</span>
							</div>
							<div class="img">
								<img src="<?php echo CDN_URL . 'prod/diagnosis/7. 칫솔 3개 (완)/' . $info['brush_line_name'] . ' ' . $info['brush_shape_name'] . '.png'; ?>" id="brush_img_3">
							</div>
							<div class="name"><?php echo $info['brush_line_name']; ?> / <?php echo $info['brush_shape_name']; ?> 3EA</div>
						</div>
						<div class="item">
							<div class="num">
								<em>2</em>
								<span>추천 치약</span>
							</div>
							<div class="img">
								<img src="<?php echo CDN_URL . 'prod/diagnosis/8. 치약 3개 (완)/' . $toothpaste1; ?>" id="toothpaste_img_3">
							</div>
							<div class="name"><?php echo $toothpaste1_name; ?> 3EA</div>
						</div>
						
					</div>
					<div class="prd-colors">
						<label><input type="radio" class="radio-color white" name="ra1" diabled><em></em></label>
						<label><input type="radio" class="radio-color gray" name="ra1" diabled><em></em></label>
						<label><input type="radio" class="radio-color black" name="ra1" diabled><em></em></label>
						<label><input type="radio" class="radio-color blue" name="ra1" diabled><em></em></label>
					</div>
					<div class="desc2">
                    	색상은 랜덤 발송됩니다.<br>
						색상 선택 및 제품 구성 변경은 아래의 옵션 변경하기에서 바꿀 수 있습니다.<br>
						스타터패키지 구성은 1인 기준 <strong>3개월 치</strong> 입니다.<br>
						<span style="color:red">(배송 주기 및 일자는 언제든지 자유롭게 변경가능합니다.)</span>
                    </div>
					<div class="flip">
						<a href="#" class="btn-flip" style="text-decoration:none !important; font-weight:normal;">옵션 변경하기</a>
					</div>
					
					
					<div class="prd-opt">
						<div class="item">
							<div class="img"><img src="" id="subscribe_brush_img"></div>
							<div class="info">
								<div class="name">칫솔</div>
								<div class="opt1">
									<label><input type="radio" class="radio-txt" name="ra10" value="기능모" <?php echo $info['brush_shape_name'] == '기능모' ? 'checked' : ''; ?>><span>기능모</span></label>
									<label><input type="radio" class="radio-txt" name="ra10" value="탄력모" <?php echo $info['brush_shape_name'] == '탄력모' ? 'checked' : ''; ?>><span>탄력모</span></label>
									<label><input type="radio" class="radio-txt" name="ra10" value="미세모" <?php echo $info['brush_shape_name'] == '미세모' ? 'checked' : ''; ?>><span>미세모</span></label>
									<label><input type="radio" class="radio-txt" name="ra10" value="초극세모" <?php echo $info['brush_shape_name'] == '초극세모' ? 'checked' : ''; ?>><span>초극세모</span></label>
								</div>
								<div class="opt2">
									<label>
                                    	<input type="radio" class="radio-mo radio-mo3" name="ra11" value="S 4줄" <?php echo $info['brush_line_name'] == '4줄' ? 'checked' : ''; ?>>
                                    	<span class="mo1" id="mo21" checked><em>S</em></span>
                                    </label>
									<label>
                                    	<input type="radio" class="radio-mo radio-mo3" name="ra11" value="M 5줄" <?php echo $info['brush_line_name'] == '5줄' ? 'checked' : ''; ?>>
                                        <span class="mo2" id="mo22"><em>M</em></span>
                                    </label>
									<label>
                                    	<input type="radio" class="radio-mo radio-mo3" name="ra11" value="L 6줄" <?php echo $info['brush_line_name'] == '6줄' ? 'checked' : ''; ?>>
                                    	<span class="mo3" id="mo23"><em>L</em></span>
                                    </label>
								</div>
								<div class="s-tit"><span>색상 -</span>
	                                <span id="color3">
                                    <?php 
										$set_color = '';
										if($color2 == 1) $set_color =  '화이트';
										else if($color2 == 2) $set_color =  '그레이';
										else if($color2 == 3) $set_color =  '블랙';
										else if($color2 == 4) $set_color =  '블루';	
										echo $set_color;
									?>
                                    </span>
                                </div>
								<div class="prd-colors">
									<label><input type="radio" class="radio-color white" value="화이트" name="ra12" <?php echo $color1 == '1' ? 'checked' : ''; ?>><em></em></label>
									<label><input type="radio" class="radio-color gray" value="그레이" name="ra12" <?php echo $color1 == '2' ? 'checked' : ''; ?>><em></em></label>
									<label><input type="radio" class="radio-color black" value="블랙" name="ra12"  <?php echo $color1 == '3' ? 'checked' : ''; ?>><em></em></label>
									<label><input type="radio" class="radio-color blue" value="블루" name="ra12" <?php echo $color1 == '4' ? 'checked' : ''; ?>><em></em></label>
								</div>
								
								
							</div>
							<div class="option-box brush_add_wrap">
								<div class="add">
									<button class="btn-add" onclick="javascript:fnAddBrush(1);">+ 추가</button>
								</div>
							</div>
						</div>
						
						<div class="item">
							<div class="img"><img src="" id="subscribe_toothpaste_img"></div>
							<div class="info">
								<div class="name">치약</div>
								<div class="opt1">
									<label><input type="radio" class="radio-txt" name="ra13" value="1" <?php echo $info['is_concern'] == '1' ? 'checked' : ''; ?>><span>활짝(충치예방)</span></label>
									<label><input type="radio" class="radio-txt" name="ra13" value="2" <?php echo $info['is_concern'] == '2' ? 'checked' : ''; ?>><span>살짝(잇몸)</span></label>
									<label><input type="radio" class="radio-txt" name="ra13" value="3" <?php echo $info['is_concern'] == '3' ? 'checked' : ''; ?>><span>달짝(시린이)</span></label>
									<label><input type="radio" class="radio-txt" name="ra13" value="4" <?php echo $info['is_concern'] == '4' ? 'checked' : ''; ?>><span>반짝(미백)</span></label>
								</div>
								<div class="s-tit"><span>용량</span></div>
								<div class="opt1">
									<label><input type="radio" class="radio-txt" name="ra14" value="1" checked><span>100g(90g)</span></label>
									<label><input type="radio" class="radio-txt" name="ra14" value="2" ><span>30g(20g)</span></label>
								</div>
								
								
							</div>
							<div class="option-box toothpaste_add_wrap">
								<div class="add">
									<button class="btn-add" onclick="javascript:fnAddToothpaste(1);">+ 추가</button>
								</div>
							</div>
						</div>
						
						<div class="item">
							<div class="img"><img src="<?php echo CDN_URL . 'prod/diagnosis/9. 가글 (완)/가글.png'; ?>"></div>
							<div class="info">
								<div class="name">가글</div>
								<div class="s-tit"><span>용량</span></div>
								<div class="opt1">
									<label><input type="radio" class="radio-txt" name="ra15" checked><span>300ml</span></label>
								</div>
								
								
							</div>
							<div class="option-box gargle_add_wrap">
								<div class="add">
									<button class="btn-add" onclick="javascript:fnAddGargle(); ">+ 추가</button>
								</div>
							</div>
						</div>
						
					</div>
					
					<div class="total">
						<dl>
							<dt>총 <span id="subscribe_total_qty">6</span>개 상품</dt>
							<dd class="per" id="subscribe_total_per">32%</dd>
						</dl>
						<dl>
							<dt>이번 결제 금액</dt>
							<dd>
								<del><span id="subscribe_total_price">13,000</span><small>원</small></del>
								<strong><span id="subscribe_total_price2">10,900</span>원</strong>
							</dd>
						</dl>
					</div>
				</div>
                </div>
                <div class="btn-box">
                    <a href="#" class="btn btn-type1 btn-subscribe" id="subscribe_is_starter_n" onclick="javascript:fnShowPayment('with'); return false;">체험하기 + 정기배송 구독하기</a>
                    <a href="#" class="btn btn-type1 btn-subscribe" id="subscribe_is_starter_y" onclick="javascript:fnShowPayment('subscribe'); return false;">정기배송 구독하기</a>
					<div class="subscribe_alarm">
							* 15,000원 이상부터 구독이 가능합니다.
					</div>
                    
                </div>
                <div class="btn-box-desc">주문 내용을 확인하였으며,<br>
    구독서비스 가입 및 추후 구독 결제에 동의합니다.</div>
            </div>
            	<input type="hidden" name="order_type" value="" />
                <input type="hidden" name="use_point" value="0" />
                <input type="hidden" name="set_delivery" value="0" />
                <input type="hidden" name="order_mem_type" value="<?php echo isset($user) ? 'member' : 'guest'; ?>" />
                <input type="hidden" name="mem_id" value="<?php echo isset($user) ? $user['mem_id'] : '0'; ?>" />
                <input type="hidden" name="starter_total_price" value="10900" />
                <input type="hidden" name="subscribe_total_price" value="" />
                <input type="hidden" name="device_type" value="" />
                <input type="hidden" name="pay_type" value="" />
                <input type="hidden" name="cdg_id" value="<?php echo $info['cdg_id']; ?>" />

				<?php 
                    if(empty($user)) {
                        $this->load->view('diagnosis/guest_payment'); 
                    }
                    else {
                        $this->load->view('diagnosis/member_payment'); 
                    } 
                ?>

            </form>
			<!-- // box3 -->
		</div>
		<script>
		// 임시 스크립트
		$('.dia-end .box3 .flip .btn-flip').click(function(){
			$(this).toggleClass('active');
			$(this).parent().next('.prd-opt').stop().slideToggle(300);
			return false;
		});
		</script>
	</div>
	<!-- // inner -->
<?php $this->load->view('diagnosis/paymentRequest'); ?>
<style>
#subscribe_3 {
	display: flex;
    justify-content: space-between;
}
#subscribe_3 > div {width:42.5%}
#subscribe_3 > div .default {
display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}
#subscribe_3 > div .default h4 {
    font-size: 30px;
    font-weight: 600;
}
#subscribe_3 > div .my-addr {
	font-size: 20px;
    line-height: 1.5;
}
#subscribe_3 > div .payment-kind h5 {
    font-size: 30px;
    margin-bottom: 18px;
    font-weight: 600;
}
.item img { height:140px;}
.info_brush { padding:10px 2% !important; }
.info_brush .img {margin-right:74px !important; }
#brush_img_3{
	height:160px;	
}
#toothpaste_img_3 {
	height:100px;
}
.length a {text-underline-position: under; font-weight:normal !important; border-bottom:none !important;}
.btn-box-desc { text-align:center;}
@media (max-width: 1023px) {
	.info_brush { padding:15px 0 !important; }
	.info_brush .img {margin-right:0 !important; }
	#brush_img_3, #toothpaste_img_3{
		height:100px;
	}
	.dia-end .btn-box {
		margin: 30px -10px 15px;
		display: block !important; 
		justify-content: space-between;
	}
	#subscribe_3 {
		display:block;	
	} 
	#subscribe_3 > div { width:auto; }
	#subscribe_3 > div .my-addr {
		font-size: 14px;	
	}
	#subscribe_3 > div .default h4 { font-size:16px; }
	#subscribe_3 div + div {margin-top:30px}
	#subscribe_3 > div .payment-kind h5 { font-size:16px; margin-bottom:12px;}
	#subscribe_3 .payment-kind {
		padding: 0;
		border: 0;
		max-width: 100%;
	}
	#starter_btn_wrap a {width:auto; padding:0 10px;}
	#subscribe_3 > div .payment-kind div + div {margin-top: 0}
}
.info ul li span:last-child {display:inline-block; width:calc(100% - 10px);}
.info ul li span:first-child {display:inline-block; width:10px; vertical-align:top }
.box3 .img span {height:75%;}
.box3 .img span.img_toothpaste{height:50%;}
.box3 .img span img {max-height: 100%;}
.radio-mo + span {background-size:contain;}
#toothpaste_main { height:130px}
#toothpaste_sel1 { height:100px}
#toothpaste_sel2 { height:100px}
#toothpaste_sel3 { height:100px}
#toothpaste_sel4 { height:100px}
</style>

<script>
var items = JSON.parse('<?php echo json_encode($item); ?>');
$(document).ready(function(e) {
    $('.payment-kind .list button.btn2').on('click', function() {
		$('.payment-kind .list button.btn2').removeClass('active');
		$(this).addClass('active');
		$('input[name=pay_type]').val($(this).attr('a'));
	});

    fnSetImg();
    fnSetImg2();
	fnSetImg3();
	fnSetImg4();
	fnAddBrush(3);
	fnAddToothpaste(3);
	$('input[name=ra2]').on('click', function() {
		if($(this).val() == '강한모') {
			$('input[name=brush_name1]').val('기능모');
			$('input[name=brush_id1]').val(1);
			$('input[name=brush_name2]').val('탄력모');
			$('input[name=brush_id2]').val(2);
			$('#start_brush_name1').html('기능모');
			$('#start_brush_name2').html('탄력모');
		}
		else {
			$('input[name=brush_name1]').val('미세모');
			$('input[name=brush_id1]').val(3);
			$('input[name=brush_name2]').val('초극세모');
			$('input[name=brush_id2]').val(4);
			$('#start_brush_name1').html('미세모');
			$('#start_brush_name2').html('초극세모');
		}
		fnSetImg();
	});
	
	$('input[name=ra3]').on('click', function() {
		fnSetImg();
	});
	$('input[name=ra4]').on('click', function() {
		$('#color1').html($(this).val());
		fnSetImg();
	});

	$('input[name=ra5]').on('click', function() {
		fnSetImg();
	});
	$('input[name=ra6]').on('click', function() {
		$('#color2').html($(this).val());
		fnSetImg();
	});

	$('input[name=ra8]').on('click', function() {
		fnSetImg2();
	});
	$('input[name=ra10]').on('click', function() {
		fnSetImg3();
	});
	$('input[name=ra11]').on('click', function() {
		fnSetImg3();
	});
	$('input[name=ra12]').on('click', function() {
		fnSetImg3();
	});
	$('input[name=ra13]').on('click', function() {
		fnSetImg4();
	});
	$('input[name=ra14]').on('click', function() {
		fnSetImg4();
	});
	
	<?php
		if($member['is_starter'] == 'y') echo 'fnRemoveStarter();';
	?>

});

function fnRemoveStarter() {
	$('.starter_wrap').remove();
	$('#subscribe_wrap').show();
	$('#subscribe_is_starter_n').hide();
	$('#subscribe_is_starter_y').show();
	$('#subscribe_3').hide();
}

function fnShowSubscribe() {
	$('#subscribe_wrap').show();
	$('#subscribe_is_starter_n').show();
	$('#subscribe_is_starter_y').hide();
	$('#subscribe_3').hide();
}

function fnShowPayment(order_type) {
	if($('.subscribe_alarm').css('display') == 'block' && order_type != 'starter') {
		return;		
	}
	$('.starter_pay').hide();
	$('input[name=order_type]').val(order_type);
	if(order_type == 'starter') {
		$('#subscribe_wrap').hide();
		$('.starter_pay').show();
	}
	$('#subscribe_3').show();
}

function fnSetImg() {
	var brush1 = $('input[name=brush_name1]').val();
	var color1 = $('input[name=ra4]:checked').val();
	var line1 = '';

	var brush2 = $('input[name=brush_name2]').val();
	var color2 = $('input[name=ra6]:checked').val();
	var line2 = '';

	if($('input[name=ra3]:checked').val() == 'L 6줄') {
		$('.radio-mo1 + span.mo3').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush1 + ' l 컬러.png")');
		$('.radio-mo1 + span.mo2').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush1 + ' m 흑백.png")');
		$('.radio-mo1 + span.mo1').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush1 + ' s 흑백.png")');
		line1 = '6줄';
	}
	else if($('input[name=ra3]:checked').val() == 'M 5줄') {
		$('.radio-mo1 + span.mo3').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush1 + ' l 흑백.png")');
		$('.radio-mo1 + span.mo2').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush1 + ' m 컬러.png")');
		$('.radio-mo1 + span.mo1').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush1 + ' s 흑백.png")');
		line1 = '5줄';
	}
	else if($('input[name=ra3]:checked').val() == 'S 4줄') {
		$('.radio-mo1 + span.mo3').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush1 + ' l 흑백.png")');
		$('.radio-mo1 + span.mo2').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush1 + ' m 흑백.png")');
		$('.radio-mo1 + span.mo1').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush1 + ' s 컬러.png")');
		line1 = '4줄';
	}

	if($('input[name=ra5]:checked').val() == 'L 6줄') {
		$('.radio-mo2 + span.mo3').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush2 + ' l 컬러.png")');
		$('.radio-mo2 + span.mo2').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush2 + ' m 흑백.png")');
		$('.radio-mo2 + span.mo1').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush2 + ' s 흑백.png")');
		line2 = '6줄';
	}
	else if($('input[name=ra5]:checked').val() == 'M 5줄') {
		$('.radio-mo2 + span.mo3').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush2 + ' l 흑백.png")');
		$('.radio-mo2 + span.mo2').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush2 + ' m 컬러.png")');
		$('.radio-mo2 + span.mo1').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush2 + ' s 흑백.png")');
		line2 = '5줄';
	}
	else if($('input[name=ra5]:checked').val() == 'S 4줄') {
		$('.radio-mo2 + span.mo3').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush2 + ' l 흑백.png")');
		$('.radio-mo2 + span.mo2').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush2 + ' m 흑백.png")');
		$('.radio-mo2 + span.mo1').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush2 + ' s 컬러.png")');
		line2 = '4줄';
	}
	$('#start_brush_img1').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/5. 칫솔 상세 (완)/'; ?>' + line1 + ' ' + brush1 + ' ' + color1 + '.png');
	$('#start_brush_img2').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/5. 칫솔 상세 (완)/'; ?>' + line2 + ' ' + brush2 + ' ' + color2 + '.png');
}

function fnSetImg2() {
	if($('input[name=ra8]:checked').val() == '1') {
		$('#toothpaste_main').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/1. 활짝치약 충치예방 100g.png'; ?>');
		$('#toothpaste_sel1').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/1. 활짝치약 충치예방 100g.png'; ?>');
		$('#toothpaste_sel2').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/6. 살짝치약 잇몸질환 30g.png'; ?>');
		$('#toothpaste_sel3').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/7. 반짝치약 미백 20g.png'; ?>');
		$('#toothpaste_sel4').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/8. 달짝치약 시린이 30g.png'; ?>');
		$('#toothpaste_sel1').css('height', '130px');
		$('#toothpaste_sel2').css('height', '100px');
		$('#toothpaste_sel3').css('height', '100px');
		$('#toothpaste_sel4').css('height', '100px');
	}
	else if($('input[name=ra8]:checked').val() == '2') {
		$('#toothpaste_main').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/2. 살짝치약 잇몸질환 100g.png'; ?>');
		$('#toothpaste_sel1').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/5. 활짝치약 충치예방 30g.png'; ?>');
		$('#toothpaste_sel2').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/2. 살짝치약 잇몸질환 100g.png'; ?>');
		$('#toothpaste_sel3').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/7. 반짝치약 미백 20g.png'; ?>');
		$('#toothpaste_sel4').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/8. 달짝치약 시린이 30g.png'; ?>');
		$('#toothpaste_sel1').css('height', '100px');
		$('#toothpaste_sel2').css('height', '130px');
		$('#toothpaste_sel3').css('height', '100px');
		$('#toothpaste_sel4').css('height', '100px');
	}
	else if($('input[name=ra8]:checked').val() == '3') {
		$('#toothpaste_main').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/4. 달짝치약 시린이 100g.png'; ?>');
		$('#toothpaste_sel1').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/5. 활짝치약 충치예방 30g.png'; ?>');
		$('#toothpaste_sel2').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/6. 살짝치약 잇몸질환 30g.png'; ?>');
		$('#toothpaste_sel3').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/4. 달짝치약 시린이 100g.png'; ?>');
		$('#toothpaste_sel4').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/7. 반짝치약 미백 20g.png'; ?>');
		$('#toothpaste_sel1').css('height', '100px');
		$('#toothpaste_sel2').css('height', '100px');
		$('#toothpaste_sel3').css('height', '130px');
		$('#toothpaste_sel4').css('height', '100px');
	}
	else if($('input[name=ra8]:checked').val() == '4') {
		$('#toothpaste_main').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/3. 반짝치약 미백 90g.png'; ?>');
		$('#toothpaste_sel1').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/5. 활짝치약 충치예방 30g.png'; ?>');
		$('#toothpaste_sel2').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/6. 살짝치약 잇몸질환 30g.png'; ?>');
		$('#toothpaste_sel3').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/8. 달짝치약 시린이 30g.png'; ?>');
		$('#toothpaste_sel4').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/3. 반짝치약 미백 90g.png'; ?>');
		$('#toothpaste_sel1').css('height', '100px');
		$('#toothpaste_sel2').css('height', '100px');
		$('#toothpaste_sel3').css('height', '100px');
		$('#toothpaste_sel4').css('height', '140px');
	}
}

function fnSetImg3() {
	var brush = $('input[name=ra10]:checked').val();
	var color = $('input[name=ra12]:checked').val();
	var line = '';

	if($('input[name=ra11]:checked').val() == 'L 6줄') {
		$('.radio-mo3 + span.mo3').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush + ' l 컬러.png")');
		$('.radio-mo3 + span.mo2').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush + ' m 흑백.png")');
		$('.radio-mo3 + span.mo1').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush + ' s 흑백.png")');
		line = '6줄';
	}
	else if($('input[name=ra11]:checked').val() == 'M 5줄') {
		$('.radio-mo3 + span.mo3').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush + ' l 흑백.png")');
		$('.radio-mo3 + span.mo2').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush + ' m 컬러.png")');
		$('.radio-mo3 + span.mo1').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush + ' s 흑백.png")');
		line = '5줄';
	}
	else if($('input[name=ra11]:checked').val() == 'S 4줄') {
		$('.radio-mo3 + span.mo3').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush + ' l 흑백.png")');
		$('.radio-mo3 + span.mo2').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush + ' m 흑백.png")');
		$('.radio-mo3 + span.mo1').css('background-image', 'url("<?php echo CDN_URL . 'prod/diagnosis/6. 칫솔 줄수 상세 (완)/'; ?>' + brush + ' s 컬러.png")');
		line = '4줄';
	}

	$('#subscribe_brush_img').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/5. 칫솔 상세 (완)/'; ?>' + line + ' ' + brush + ' ' + color + '.png');
}

function fnSetImg4() {
	if($('input[name=ra13]:checked').val() == '1') {
		if($('input[name=ra14]:checked').val() == '1') {
			$('#subscribe_toothpaste_img').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/1. 활짝치약 충치예방 100g.png'; ?>');
		}
		else {
			$('#subscribe_toothpaste_img').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/5. 활짝치약 충치예방 30g.png'; ?>');
		}
	}
	else if($('input[name=ra13]:checked').val() == '2') {
		if($('input[name=ra14]:checked').val() == '1') {
			$('#subscribe_toothpaste_img').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/2. 살짝치약 잇몸질환 100g.png'; ?>');
		}
		else {
			$('#subscribe_toothpaste_img').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/6. 살짝치약 잇몸질환 30g.png'; ?>');
		}
	}
	else if($('input[name=ra13]:checked').val() == '3') {
		if($('input[name=ra14]:checked').val() == '1') {
			$('#subscribe_toothpaste_img').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/4. 달짝치약 시린이 100g.png'; ?>');
		}
		else {
			$('#subscribe_toothpaste_img').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/8. 달짝치약 시린이 30g.png'; ?>');
		}
	}
	else if($('input[name=ra13]:checked').val() == '4') {
		if($('input[name=ra14]:checked').val() == '1') {
			$('#subscribe_toothpaste_img').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/3. 반짝치약 미백 90g.png'; ?>');
		}
		else {
			$('#subscribe_toothpaste_img').attr('src', '<?php echo CDN_URL . 'prod/diagnosis/4. 체험하기 - 추천치약 (완)/7. 반짝치약 미백 20g.png'; ?>');
		}
	}
}

function fnDeleteItem(obj) {
	$(obj).parent().parent().remove();	
	fnCalcTotal();
}

function fnPlus(obj) {
	var parent = $(obj)	.parent().parent();
	var qty = parseInt($(parent).children('.length').children('input[name="qty[]"]').val());
	var sel_price = parseInt($(parent).children('.length').children('input[name="cit_price[]"]').val());
	var sel_subscribe_price = parseInt($(parent).children('.length').children('input[name="cit_subscribe_price[]"]').val());
	qty += 1;
	$(parent).children('.length').children('input[name="qty[]"]').val(qty);
	$(parent).children('.txt').children('del').html(commify(sel_price * qty));
	$(parent).children('.txt').children('b').html(commify(sel_subscribe_price * qty) + '원');
	fnCalcTotal();
}

function fnMinus(obj) {
	var parent = $(obj)	.parent().parent();
	var qty = parseInt($(parent).children('.length').children('input[name="qty[]"]').val());
	var sel_price = parseInt($(parent).children('.length').children('input[name="cit_price[]"]').val());
	var sel_subscribe_price = parseInt($(parent).children('.length').children('input[name="cit_subscribe_price[]"]').val());
	qty -= 1;
	if(qty <= 0) return;
	$(parent).children('.length').children('input[name="qty[]"]').val(qty);
	$(parent).children('.txt').children('del').html(commify(sel_price * qty));
	$(parent).children('.txt').children('b').html(commify(sel_subscribe_price * qty) + '원');
	fnCalcTotal();
}

function fnAddBrush(cnt) {
	var shape = $('input[name=ra10]:checked').val();
	var size = $('input[name=ra11]:checked').val();
	var color = $('input[name=ra12]:checked').val();
	var line = '';
	var id = '';
	var price = 0;
	var subscribe_price = 0;
	var cit_name = '';

	if(size == 'S') line = '4줄';
	else if(size == 'M') line = '5줄';
	else if(size == 'L') line = '6줄';

	for(var i = 0; i < items.length; i++) {
		if(	items[i].diagnosis_name == shape) {
			id = items[i].cit_id;
			price = items[i].cit_price;
			subscribe_price = items[i].cit_subscribe_price;	
			cit_name = items[i].cit_name;
		}
	}
	
	var bExists = false;
	$('input[name="cit_id[]"]').each(function(index, element) {
        if($(this).val() == id && $('input[name="option1[]"]').eq(index).val() == color && $('input[name="option2[]"]').eq(index).val() == size) {
			var qty = parseInt($('input[name="qty[]"]').eq(index).val());
			var sel_price = parseInt($('input[name="cit_price[]"]').eq(index).val());
			var sel_subscribe_price = parseInt($('input[name="cit_subscribe_price[]"]').eq(index).val());
			var parent = $(this).parent().parent();
			qty += 1;
			$('input[name="qty[]"]').eq(index).val(qty);
			bExists = true;	
			$(parent).children('.txt').children('del').html(commify(sel_price * qty));
			$(parent).children('.txt').children('b').html(commify(sel_subscribe_price * qty) + '원');
		}
    });
	
	if(!bExists) {
		var str = '<div class="option" style="margin-bottom:10px">'
				+ '		<div class="txt">'
				+ '			<strong>' + line + ' / ' + shape + ' / ' + color + '</strong>'
				+ '			<del>' + commify(price * cnt) + '</del>'
				+ '			<b>' + commify(subscribe_price * cnt) + '원</b>'
				+ '		</div>'
				+ '		<div class="length">'
				+ '			<input type="hidden" name="cit_id[]" value="' + id + '" />'
				+ '			<input type="hidden" name="cit_name[]" value="' + cit_name + '" />'
				+ '			<input type="hidden" name="option1[]" value="' + size + '" />'
				+ '			<input type="hidden" name="option2[]" value="' + color + '" />'
				+ '			<input type="hidden" name="cit_price[]" value="' + price + '" />'
				+ '			<input type="hidden" name="cit_subscribe_price[]" value="' + subscribe_price + '" />'
				+ '			<button class="btn-minus" onclick="javascript:fnMinus(this);"></button>'
				+ '			<input type="text" name="qty[]" class="inp" value="' + cnt + '" readonly>'
				+ '			<button class="btn-plus" onclick="javascript:fnPlus(this);"></button>'
				+ '			<a href="#" class="btn-del" onclick="javascript:fnDeleteItem(this); return false;">삭제</a>'
				+ '		</div>'
				+ '	</div>';
		$('.brush_add_wrap').append(str);
	}
	fnCalcTotal();
}

function fnAddToothpaste(cnt) {
	var kind = $('input[name=ra13]:checked').val();
	var size = $('input[name=ra14]:checked').val() == '1' ? '100g' : '30g';
	var kind_name = '';
	var id_name = '';
	var price = 0;
	var subscribe_price = 0;
	var cit_name = '';

	if(kind == '1') {
		kind_name = '활짝(충치예방)';
		id_name = '활짝' + size;
	}
	else if(kind == '2') {
		kind_name = '살짝(잇몸)';
		id_name = '살짝' + size;
	}
	else if(kind == '3') {
		kind_name = '달짝(시린이)';
		id_name = '달짝' + size;
	}
	else if(kind == '4') {
		size = $('input[name=ra14]:checked').val() == '1' ? '90g' : '20g';
		kind_name = '반짝(미백)';
		id_name = '반짝' + size;
	}
	
	for(var i = 0; i < items.length; i++) {
		if(	items[i].diagnosis_name == id_name) {
			id = items[i].cit_id;
			price = items[i].cit_price;
			subscribe_price = items[i].cit_subscribe_price;	
			cit_name = items[i].cit_name;
		}
	}
	
	var bExists = false;
	$('input[name="cit_id[]"]').each(function(index, element) {
        if($(this).val() == id) {
			var qty = parseInt($('input[name="qty[]"]').eq(index).val());
			var sel_price = parseInt($('input[name="cit_price[]"]').eq(index).val());
			var sel_subscribe_price = parseInt($('input[name="cit_subscribe_price[]"]').eq(index).val());
			var parent = $(this).parent().parent();
			qty += 1;
			$('input[name="qty[]"]').eq(index).val(qty);
			bExists = true;	
			$(parent).children('.txt').children('del').html(commify(sel_price * qty));
			$(parent).children('.txt').children('b').html(commify(sel_subscribe_price * qty) + '원');
		}
    });
	
	if(!bExists) {
		var str = '<div class="option" style="margin-bottom:10px">'
				+ '		<div class="txt">'
				+ '			<strong>' + kind_name + ' ' + size + '</strong>'
				+ '			<del>' + commify(price * cnt) + '</del>'
				+ '			<b>' + commify(subscribe_price * cnt) + '원</b>'
				+ '		</div>'
				+ '		<div class="length">'
				+ '			<input type="hidden" name="cit_id[]" value="' + id + '" />'
				+ '			<input type="hidden" name="cit_name[]" value="' + cit_name + '" />'
				+ '			<input type="hidden" name="option1[]" value="" />'
				+ '			<input type="hidden" name="option2[]" value="" />'
				+ '			<input type="hidden" name="cit_price[]" value="' + price + '" />'
				+ '			<input type="hidden" name="cit_subscribe_price[]" value="' + subscribe_price + '" />'
				+ '			<button class="btn-minus" onclick="javascript:fnMinus(this);"></button>'
				+ '			<input type="text" name="qty[]" class="inp" value="' + cnt + '" readonly>'
				+ '			<button class="btn-plus" onclick="javascript:fnPlus(this);"></button>'
				+ '			<a href="#" class="btn-del" onclick="javascript:fnDeleteItem(this); return false;">삭제</a>'
				+ '		</div>'
				+ '	</div>';
		$('.toothpaste_add_wrap').append(str);
	}
	fnCalcTotal();
}

function fnAddGargle() {
	var id_name = '가글';
	var cit_name = '';
	var cnt = 1;
	var price = 0;
	var subscribe_price = 0;

	for(var i = 0; i < items.length; i++) {
		if(	items[i].diagnosis_name == id_name) {
			id = items[i].cit_id;
			price = items[i].cit_price;
			subscribe_price = items[i].cit_subscribe_price;	
			cit_name = items.cit_name;
		}
	}
	
	var bExists = false;
	$('input[name="cit_id[]"]').each(function(index, element) {
        if($(this).val() == id) {
			var qty = parseInt($('input[name="qty[]"]').eq(index).val());
			var sel_price = parseInt($('input[name="cit_price[]"]').eq(index).val());
			var sel_subscribe_price = parseInt($('input[name="cit_subscribe_price[]"]').eq(index).val());
			var parent = $(this).parent().parent();
			qty += 1;
			$('input[name="qty[]"]').eq(index).val(qty);
			bExists = true;	
			$(parent).children('.txt').children('del').html(commify(sel_price * qty));
			$(parent).children('.txt').children('b').html(commify(sel_subscribe_price * qty) + '원');
		}
    });
	
	if(!bExists) {
		var str = '<div class="option">'
				+ '		<div class="txt">'
				+ '			<strong>' + id_name + ' 300ml</strong>'
				+ '			<del>' + commify(price * cnt) + '</del>'
				+ '			<b>' + commify(subscribe_price * cnt) + '원</b>'
				+ '		</div>'
				+ '		<div class="length">'
				+ '			<input type="hidden" name="cit_id[]" value="' + id + '" />'
				+ '			<input type="hidden" name="cit_name[]" value="' + cit_name + '" />'
				+ '			<input type="hidden" name="option1[]" value="" />'
				+ '			<input type="hidden" name="option2[]" value="" />'
				+ '			<input type="hidden" name="cit_price[]" value="' + price + '" />'
				+ '			<input type="hidden" name="cit_subscribe_price[]" value="' + subscribe_price + '" />'
				+ '			<button class="btn-minus" onclick="javascript:fnMinus(this);"></button>'
				+ '			<input type="text" name="qty[]" class="inp" value="' + cnt + '" readonly>'
				+ '			<button class="btn-plus" onclick="javascript:fnPlus(this);"></button>'
				+ '			<a href="#" class="btn-del" onclick="javascript:fnDeleteItem(this); return false;">삭제</a>'
				+ '		</div>'
				+ '	</div>';
		$('.gargle_add_wrap').append(str);
	}	
	
	fnCalcTotal();
}

function fnCalcTotal()
{
	var total_price = 0;
	var total_subscribe_price = 0;
	var total_qty = 0;
	$('input[name="cit_id[]"]').each(function(index, element) {
		var qty = parseInt($('input[name="qty[]"]').eq(index).val());
		var sel_price = parseInt($('input[name="cit_price[]"]').eq(index).val());
		var sel_subscribe_price = parseInt($('input[name="cit_subscribe_price[]"]').eq(index).val());
		var parent = $(this).parent().parent();
		
		total_qty += qty;
		total_price += sel_price * qty;
		total_subscribe_price += sel_subscribe_price * qty;
    });
	
	$('#subscribe_total_qty').html(total_qty);
	$('#subscribe_total_price').html(commify(total_price));
	$('#subscribe_total_price2').html(commify(total_subscribe_price));
	$('#subscribe_total_per').html(Math.ceil(((total_price - total_subscribe_price)/total_price) * 100) + '%');
	$('input[name=subscribe_total_price]').val(total_subscribe_price);
	
	if(total_subscribe_price < 15000) {
		$('.subscribe_alarm').show();	
		$('.btn-subscribe').css('background-color', '#909090');
	}
	else {
		$('.subscribe_alarm').hide();
		$('.btn-subscribe').css('background-color', '#003ca6');
	}

	$('.starter_pay').hide();
	$('#subscribe_3').hide();
}
</script>