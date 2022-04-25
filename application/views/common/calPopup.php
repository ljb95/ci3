	<div class="modal" id="modalSchedule">
		<div class="modal-dialog" style="max-width:830px; margin-top:20px;">
			<div class="modal-content">
				<div class="modal-body">
					<div class="modal-msg1">
						<h3 class="h3">날짜변경</h3>
						<div class="calendar calender-multi"></div>
					</div>
					
				</div>
				<div class="modal-footer text-center">
					<button class="btn btn-type1 w280" data-dismiss="modal" onclick="javascript:fnPopupSetDay();">확인</button>
				</div>
			</div>
		</div>
	</div>
<script>
function fnPopupShowDay(day) {
    $.datepicker.regional["ko"] = {
		closeText: "닫기",
		prevText: "이전달",
		nextText: "다음달",
		currentText: "오늘",
		yearRange: 'c-70:c+00',
		monthNames: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"],
		monthNamesShort: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"],
		dayNames: ["일","월","화","수","목","금","토"],
		dayNamesShort: ["일","월","화","수","목","금","토"],
		dayNamesMin: ["일","월","화","수","목","금","토"],
		weekHeader: "Wk",
		dateFormat: "yy-mm-dd",
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: "",
		minDate: '<?php echo date('Y-m-d', strtotime('+1 days')); ?>',
	};
	$.datepicker.setDefaults($.datepicker.regional["ko"]);
	$( ".calender-multi" ).datepicker({
		numberOfMonths: 2
	}).datepicker('setDate', day);	
	
	$('#modalSchedule').modal('show');
}

function fnPopupSetDay() {
	var date = new Date($( ".calender-multi" ).datepicker('getDate'));
	var yy = date.getFullYear();
	var mm = date.getMonth() + 1;
	var dd = date.getDate();
	$('#new_date').val(yy + '-' + (mm < 10 ? '0' : '') + mm + '-' + (dd < 10 ? '0' : '') + dd);
	$('#now_date').html(mm + '월 ' + dd + '일');
	
	var period = $('#new_period').val() == '' ? $('#org_period').val() : $('#new_period').val();
	date.setDate(dd + (7 * period));
	yy = date.getFullYear();
	mm = date.getMonth() + 1;
	dd = date.getDate();
	$('#next_date').html(mm + '월 ' + dd + '일');
}
</script>