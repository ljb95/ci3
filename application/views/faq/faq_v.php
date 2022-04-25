	<div class="sub-head">
		<div class="inner">
			<h2 class="h2">커뮤니티</h2>
			<div class="tabs">
				<div>
					<a href="/magazine">매거진</a>
					<a href="/event">이벤트</a>
					<a href="javascript:void(0);" class="active">FAQ</a>
					<a href="/notice/list">공지사항</a>
				</div>
			</div>
		</div>
	</div>
	
	<div class="inner">
		
		<div class="faq-head">
			<div class="faq-search">
				<div class="search">
                    <form id="frmSearch" onSubmit="return false;">
                        <input type="text" class="inp-srch" name="searchText"  onkeypress="javascript:if(event.keyCode==13) { goPage(0); }" />
                        <input type="hidden" id="offset" name="offset" />
                        <input type="hidden" name="cbc_id" />
                    </form>
					<button class="btn-srch" onclick="javascript:goPage(0);">검색</button>
				</div>
			</div>
			<div class="faq-tabs">
				<ul>
                <?php
					foreach($category as $row) {
				?>
					<li id="category_<?php echo $row['cbc_id']; ?>" a="<?php echo $row['cbc_id']; ?>" >
                    	<a href="#"><i></i><p><?php echo $row['cbc_name']; ?></p></a>
                    </li>
                <?php
					}
				?>
				</ul>
			</div>
		</div>
		
		<div class="faq">
			<ul id="faq_list_wrap">
			</ul>
		</div>		
		
		
		<div id="pagination">
		</div>
<style>
<?php
	foreach($category as $row) {
		echo '#category_' . $row['cbc_id'] . ' a i {background:url(/common/img_view?img_path=' . $row['off_img_file'] . '&img_name) no-repeat 50% 50%; background-size:38px;} ';
		echo '#category_' . $row['cbc_id'] . '.active a i {background-image : url(/common/img_view?img_path=' . $row['on_img_file'] . '&img_name); background-color: #003ca6; } ';
	}
?>

.add_file {margin-top:10px;}
.add_file a {
	display: inline-block;
    vertical-align: middle;
    padding-bottom: 4px;
    font-size: 20px;
    color: #003ca6;
    border-bottom: 1px solid #003ca6;
}
.empty_text {
	font-size:20px; text-align:center; width:100%; padding:35px 0;
}
@media (max-width: 1023px) {
	.empty_text {font-size:14px}
<?php
	foreach($category as $row) {
		echo '#category_' . $row['cbc_id'] . ' a i {background:url(/common/img_view?img_path=' . $row['off_img_file'] . '&img_name) no-repeat 50% 50%; background-size:22px;} ';
	}
?>
}
</style>
<script>
$(document).ready(function(e) {
	$('.faq-tabs ul li a').on('click', function() {
		$('.faq-tabs ul li').removeClass('active');
		$(this).parent().addClass('active');
		goPage(0);
		return false;
	});
	
	$('#offset').val(0);
	fnSearch();
});

function goPage(offset)
{
	$('#offset').val(offset);
	fnSearch();
}

function fnSearch()
{
	var seq = new Array();
	$('.faq-tabs ul li').each(function(index, element) {
        if($(this).hasClass('active')) {
			seq.push($(this).attr('a'));
		}
    });
	if(seq.length > 0) {
		$('input[name=cbc_id]').val(seq.join(','));
	}
	else {
		$('input[name=cbc_id]').val('');
	}
	$.ajax({
      	type:'POST',
    	url:'/faq/ajaxFaqList',
		data : $('#frmSearch').serialize(),
		dataType:"json",
       	success:function(data){
			var str = '';
			for(var i = 0; i < data.list.length; i++) {
				str += '<li>'
					+ '		<a href="#" class="q">'
					+ '			<strong>Q.</strong>'
					+ ' 		<div>' + data.list[i].faq_title + '</div>'
					+ '		</a>'
					+ '		<div class="a">'
					+ '			<strong>A.</strong>'
					+ '			<div>' + data.list[i].faq_content + '</div>';
				if(data.list[i].files.length > 0) {
					str += '<div style="margin-top:20px">첨부파일</div>';	
				}
				for(var j = 0; j < data.list[i].files.length; j++) {
					str += '<div class="add_file"><a href="<?php echo CDN_URL;?>' + data.list[i].files[j].new_filepath + data.list[i].files[j].new_filename + '">' + data.list[i].files[j].org_filename + '</a>';
				}
				str += '	</div>'
					+ '</li>';
			}
			if(str != '') {
				$('#faq_list_wrap').html(str);
			}
			else {
				$('#faq_list_wrap').html('<li class="empty_text">준비중이에요 궁금한 사항은 \'마이페이지 > 활동관리 > 나의문의\' 로 문의주세요.</li>');	
			}
			$('#pagination').html(data.pagination);

			$('.faq ul li .q').click(function(){
				$(this).toggleClass('active');
				$(this).next().stop().slideToggle(300);
				return false;
			})
       	},
        error:function(data){
         	alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
        }
   });
}

</script>
</div>