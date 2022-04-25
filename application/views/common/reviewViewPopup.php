	<!-- 리뷰 상세 보기 -->
	<div class="modal fade" role="dialog" aria-labelledby="introHeader" aria-hidden="true" tabindex="-1" data-backdrop="static" id="modalReviewDetail">
		<div class="modal-dialog" style="max-width:830px; margin-top:20px;">
			<div class="modal-content">
				<div class="modal-body">
					<div class="modal-msg1">
						<div class="modal-review-detail modal-review-write" style="position:relative">
                        	<i class="fas fa-times" style="font-size:25px; color:#000; top:-20px; right:-5%; position:absolute; cursor:pointer;" data-dismiss="modal"></i>
							<h4 id="show_detail_product_name">클린디 칫솔 4P</h4>
							<div class="slider">
                            	<div class="swiper-container">
                                	<div class="swiper-wrapper" id="show_detail_product_img">
                                    </div>
                                </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
							<hr class="hr2 mt30 mb20">
							
							<div class="grade large mb20" id="show_detail_score">
								<i class="on"></i>
								<i class="on"></i>
								<i class="on"></i>
								<i></i>
								<i></i>
							</div>
							<div class="detail-view">
								<div id="show_detail_review_img" style="text-align:center"></div>
								<br>
								<div id="show_detail_review_content" style="word-break:break-all; overflow-x:none; "></div>
							</div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
<script>
var viewSwiper = null;
function fnPopupSetDetail(data) {
	data = data.replace(/(\n|\r\n)/g, '<br>').replace(/\'/gi, "\"");
	var data = JSON.parse(data); 
	$('#show_detail_product_name').html('<a href="/product/product_detail?seq=' + data.cit_id + '">' + data.cit_name + '</a>');
	var str = '';
	if(data.cit_file_1 != '') {
		str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + data.cit_file_1 + '" style="max-height:160px;"></p></div>';
	}
	if(data.cit_file_2 != '') {
		str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + data.cit_file_2 + '" style="max-height:160px;"></p></div>';
	}
	if(data.cit_file_3 != '') {
		str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + data.cit_file_3 + '" style="max-height:160px;"></p></div>';
	}
	if(data.cit_file_4 != '') {
		str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + data.cit_file_4 + '" style="max-height:160px;"></p></div>';
	}
	if(data.cit_file_5 != '') {
		str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + data.cit_file_5 + '" style="max-height:160px;"></p></div>';
	}
	if(data.cit_file_6 != '') {
		str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + data.cit_file_6 + '" style="max-height:160px;"></p></div>';
	}
	if(data.cit_file_7 != '') {
		str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + data.cit_file_7 + '" style="max-height:160px;"></p></div>';
	}
	if(data.cit_file_8 != '') {
		str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + data.cit_file_8 + '" style="max-height:160px;"></p></div>';
	}
	if(data.cit_file_9 != '') {
		str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + data.cit_file_9 + '" style="max-height:160px;"></p></div>';
	}
	if(data.cit_file_10 != '') {
		str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + data.cit_file_10 + '" style="max-height:160px;"></p></div>';
	}
	$('#show_detail_product_img').html(str);
	if(viewSwiper != null) {
//		viewSwiper.destroy();	
	}
	viewSwiper = new Swiper(".modal-review-write .swiper-container", {
		navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev",
		},
			observer: true,
			observeParents: true,
	});
	
	var score = '';
	for(var i = 0; i < data.cre_score; i++) {
		score += '<i class="on"></i>';	
	}
	for(var i = data.cre_score; i < 5; i++) {
		score += '<i></i>';	
	}
	$('#show_detail_score').html(score);
	
	str = '';
	if(data.img_file1 != '') {
		str += '<div style="margin-bottom:10px"><img src="<?php echo CDN_URL; ?>' + data.img_file1 + '" style="max-width:100%;"></div>';
	}
	if(data.img_file2 != '') {
		str += '<div style="margin-bottom:10px"><img src="<?php echo CDN_URL; ?>' + data.img_file2 + '" style="max-width:100%;"></div>';
	}
	if(data.img_file3 != '') {
		str += '<div style="margin-bottom:10px"><img src="<?php echo CDN_URL; ?>' + data.img_file3 + '" style="max-width:100%;"></div>';
	}
	if(data.img_file4 != '') {
		str += '<div style="margin-bottom:10px"><img src="<?php echo CDN_URL; ?>' + data.img_file4 + '" style="max-width:100%;"></div>';
	}
	if(data.img_file5 != '') {
		str += '<div style="margin-bottom:10px"><img src="<?php echo CDN_URL; ?>' + data.img_file5 + '" style="max-width:100%;"></div>';
	}
	if(data.img_file6 != '') {
		str += '<div style="margin-bottom:10px"><img src="<?php echo CDN_URL; ?>' + data.img_file6 + '" style="max-width:100%;"></div>';
	}
	if(data.img_file7 != '') {
		str += '<div style="margin-bottom:10px"><img src="<?php echo CDN_URL; ?>' + data.img_file7 + '" style="max-width:100%;"></div>';
	}
	if(data.img_file8 != '') {
		str += '<div style="margin-bottom:10px"><img src="<?php echo CDN_URL; ?>' + data.img_file8 + '" style="max-width:100%;"></div>';
	}
	if(data.img_file9 != '') {
		str += '<div style="margin-bottom:10px"><img src="<?php echo CDN_URL; ?>' + data.img_file9 + '" style="max-width:100%;"></div>';
	}
	if(data.img_file10 != '') {
		str += '<div style="margin-bottom:10px"><img src="<?php echo CDN_URL; ?>' + data.img_file10 + '" style="max-width:100%;"></div>';
	}
	$('#show_detail_review_img').html(str);
	$('#show_detail_review_content').html(data.cre_content);
	
}
</script>