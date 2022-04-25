			<div id="subscribe_2" style="display:none">
				<div class="calendar-cart">
					<div class="tit">
                    	<strong>원하는 배송날짜 선택</strong><br>
                    </div>
					<div class="tit2">오후 3시(15시)이전까지 구독 신청 시 당일발송됩니다.<br>오후 3시(15시)이후는 익일부터 선택 가능합니다.</div>
					<div class="box">
						<div id="CalendarCart" class="calendar"></div>
						<div class="week">
							<div class="btns">
                            	<?php
									$week_set = array("일","월","화","수","목","금","토");
									$week = $week_set[date('w', strtotime($start_date))];
									$next_day = date("Y.m.d",strtotime("+4 week", strtotime($start_date)));
									$next_day2 = date("Y.m.d",strtotime("+12 week", strtotime($start_date)));
									$next_day3 = date("Y.m.d",strtotime("+16 week", strtotime($start_date)));
								?>
								<p>
                                	<button class="btn-period btn-under active" id="select_period_4" onclick="javascript:fnSetPeriod(4);">4주 마다</button>
                                    <span class="desc2 desc_week" id="desc_4week">다음 결제일 : <label id="desc_4week_day"><?php echo $next_day; ?></label>(<label class="week_val"><?php echo $week; ?></label>요일)</span>
                                </p>
								<p>
                                	<button class="btn-period btn-under" id="select_period_12" onclick="javascript:fnSetPeriod(12);">12주 마다</button>
                                    <span class="desc2 desc_week" id="desc_12week" style="display:none;">다음 결제일 : <label id="desc_12week_day"><?php echo $next_day2; ?></label>(<label class="week_val"><?php echo $week; ?></label>요일)</span>
                                </p>
								<p>
                                	<button class="btn-period btn-under" id="select_period_16" onclick="javascript:fnSetPeriod(16);">16주 마다</button>
                                    <span class="desc2 desc_week" id="desc_16week" style="display:none;">다음 결제일 : <label id="desc_16week_day"><?php echo $next_day3; ?></label>(<label class="week_val"><?php echo $week; ?></label>요일)</span>
                                </p>
                                <input type="hidden" name="delivery_day" value="<?php echo date('w', strtotime($start_date)); ?>"/>
                                <input type="hidden" name="delivery_period" value="4"/>
                                <input type="hidden" name="start_date" value="<?php echo $start_date; ?>" />
							</div>
							<div class="end">결제일자 : <span id="show_delivery_day"><?php echo date('Y.m.d', strtotime($start_date)); ?></span>일(<label class="week_val"><?php echo $week; ?></label>요일) / 
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
		minDate : '<?php echo $start_date; ?>',
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
