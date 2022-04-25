	<div class="sub-head review pc">
		<div class="inner">
			<h2 class="h2">리뷰</h2>
			
		</div>
	</div>
	
	<div class="inner">
		<div class="review">
			<div class="flex">
				<div>
                </div>
				<div>
					<label><input type="checkbox" class="checkbox" id="only_photo"><em></em><span>포토리뷰만 보기</span></label>
                    <input type="hidden" id="offset" value="0" />
				</div>
			</div>
			<div class="review-list">
				<ul id="review_list_wrap">
				</ul>
					
				<div class="text-center more" id="more_button">
					<button class="btn btn-type0 btn-m w190" onclick="javascript:fnSearch(); ">더보기</button>
				</div>
			</div>

		</div>
			<!-- // review -->
	</div>
<?php $this->load->view('common/reviewViewPopup'); ?>
    
<script>
$(document).ready(function(e) {
	$('#offset').val('0');
    fnSearch();
	
	$('#only_photo').on('click', function() {
		$('#offset').val('0');
		fnSearch();
	});
});

function fnSearch()
{
	$.ajax({
      	type:'POST',
    	url:'/review/ajaxReviewAll',
		data : {offset : $('#offset').val(), only_photo: $('#only_photo').is(':checked') ? 'y' : 'n'},
		dataType:"json",
       	success:function(data){
			var str = '';
			if(data.list.length < data.perpage) {
				$('#more_button').hide();	
			}
			else {
				$('#more_button').show();	
			}
			for(var i = 0; i < data.list.length; i++) {
				str += '<li>'
					+  ' 	<div class="item">'
					+  '		<div class="box1">'
					+  '			<div class="txt1">'
					+  '				<span class="nick">' + data.list[i].mem_email + '</span>'
					+  '				<span class="date">' + data.list[i].ins_dtm + '</span>'
					+  '				<div class="mobile">' + data.list[i].ins_dtm + ', ' + data.list[i].cit_name + '</div>'
					+  '			</div>'
					+  '			<div class="grade">';
				for(var j = 0; j < data.list[i].cre_score; j++) {
					str += '			<i class="on"></i>';
				}
				for(var j = data.list[i].cre_score; j < 5; j++) {
					str += '				<i></i>';
				}
				str += '			</div>'
					+  '		</div>'
					+  '		<div class="box2">';
				if(data.list[i].img_file1 != '') {
					str += '		<div class="img img_fit" style="border:none"><img src="<?php echo CDN_URL; ?>' + data.list[i].img_file1 + '" style="object-fit: cover; width: 100%; height: 100%;"></div>';
				}
				else {
					str += '		<div class="img img_fit" style="border:none"><img src="<?php echo CDN_URL; ?>' + data.list[i].cit_file_1 + '" style="object-fit: cover; width: 100%; height: 100%;"></div>';
				}
				str += '		</div>'
					+  '		<div class="box3">'
					+  '			<div class="month">' + data.list[i].cit_name + '</div>'
					+  '			<div class="txt" style="word-break:break-all;">' + data.list[i].cre_title + '</div>'
					+  '			<div class="more">'
					+  '				<a href="#" class="btn-under"  data-toggle="modal" data-target="#modalReviewDetail" onclick="javascript:fnPopupSetDetail(\'' + JSON.stringify(data.list[i]).replace(/\"/gi, "\\\'") +'\'); return false;">더보기</a>'
					+  '			</div>'
					+  '		</div>'
					+  '	</div>'
					+  '</li>';

			}
			if(data.offset == data.perpage) {
				$('#review_list_wrap').html(str);
			}
			else {
				$('#review_list_wrap').append(str);
			}
			$('#offset').val(data.offset);
       	},
        error:function(data){
         	alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
        }
   });
}
</script>