<?php
//	$unit_price = $info['cit_subscribe_price']; 
	$unit_price = $info['is_sale'] === 'y' ? $info['cit_sale_price'] : $info['cit_price']; 
//	if($info['is_subscribe'] === 'y') $unit_price = $info['cit_sale_price'];
	$sale_percent = 0;
	$sale_percent = round(($info['cit_price'] - $unit_price) / $info['cit_price'] * 100) . '%'; 
?>
	<div class="sub-head product pc">
		<div class="inner">
			<h2 class="h2">상품</h2>
			<div class="tabs">
				<div>
                <?php
					$sum = 0;
					foreach($category as $row) {
						$sum += $row['cca_cnt'];
					}
				?>
					<a href="/product/product_list" <?php echo empty($info['cca_id']) ? 'class="active"' : ''; ?>>전체 (<?php echo $sum; ?>)</a>
                <?php
					foreach($category as $row) {
						echo '<a href="/product/product_list?seq=' . $row['cca_id'] . '" ' . ($info['cca_id'] === $row['cca_id'] ? 'class="active"' : '') . '>' . $row['cca_value'] . ' (' . $row['cca_cnt'] . ')</a>';
                    }
				?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="inner">
		
		
		<div class="product-detail">
			<div class="head">
				<div class="box">
					<div class="info-box">
						<div class="thum mobile">
                       		<div class="slider">
                            	<div class="swiper-container" id="detail_swipe_mo">
                                	<div class="swiper-wrapper">
                                    	<?php 
											for($i = 1; $i <= 10; $i++) {
												if(!empty($info['cit_file_' . $i])) {
													echo '<div class="swiper-slide"><img src="' . CDN_URL . $info['cit_file_' . $i] . '" style="position:unset; transform:unset;"></div>';
												}
											}
										?>
                                    </div>
	                                <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        </div>
						<div class="info">
							<dl>
								<dt><?php echo $info['cit_name']; ?></dt>
								<dd><?php echo $info['cit_summary']; ?></dd>
							</dl>
							<div class="price-box">
                            	<?php
									if($sale_percent > 0) {
								?>
								<div class="per"><?php echo $sale_percent; ?></div>
								<del><em><?php echo number_format($info['cit_price']); ?></em>원</del>
                                <?php
									}
								?>
								<div class="price"><strong><?php echo number_format($unit_price); ?></strong>원</div>
							</div>
						</div>
					</div>
                        <div class="btn-box">
<!--                            <button class="btn btn-type2">장바구니</button> -->
                            <button class="btn btn-type1" style="border-radius:13px" id="BtnBuy">구매하기</button>
                        </div>
				</div>
			</div>
			<!-- // head -->
			
			<div class="body">
				<!-- 상세 내용 -->
				<?php echo $info['cit_content']; ?>
			</div>
			<!-- // body -->
			
			<div class="review">
				<h3 class="h3"><span class="blue"><?php echo $info['cit_name']; ?></span> 리뷰</h3>
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
                    	<li>
                        	<div style="font-size:16px; text-align:center">
	                        	등록된 리뷰가 없습니다. 첫 번째 리뷰를 작성해 주세요.
                            </div>
                        </li>
                    </ul>
                        
                    <div class="text-center more" id="more_button">
                        <button class="btn btn-type0 btn-m w190" onclick="javascript:fnSearch(); ">더보기</button>
                    </div>
                </div>

			</div>
		</div>
		<!-- // product-detail -->
		
		
		<div class="aside-order">
        <form id="order_data" onSubmit="return false;">
			<!-- 1. 옵션선택 -->
			<div class="order-step1">
				<div class="head">
					<button class="btn-back"><span>뒤로</span></button>
				</div>
				<div class="body">
					<div class="info-box">
						<div class="thum" style="padding:0">
                       		<div class="slider">
                            	<div class="swiper-container" id="detail_swipe">
                                	<div class="swiper-wrapper">
                                    	<?php 
											for($i = 1; $i <= 10; $i++) {
												if(!empty($info['cit_file_' . $i])) {
													echo '<div class="swiper-slide"><img src="' . CDN_URL . $info['cit_file_' . $i] . '" style="position:relative; transform:translateY(0%);"></div>';
												}
											}
										?>
                                    </div>
	                                <div class="swiper-pagination"></div>
                                </div>
                            </div>
                        </div>
						<div class="info">
							<dl>
								<dt><?php echo $info['cit_name']; ?></dt>
								<dd><?php echo $info['cit_summary']; ?></dd>
                                <input type="hidden" name="cart_type" value="subscribe" />
                                <input type="hidden" name="is_subscribe" value="<?php echo $info['is_subscribe']; ?>" />
                                <input type="hidden" name="mem_id" value="<?php echo isset($user) ? $user['mem_id'] : ''; ?>" />
                                <input type="hidden" name="cit_id" value="<?php echo $info['cit_id']; ?>" />
                                <input type="hidden" name="cit_name" value="<?php echo $info['cit_name']; ?>" />
                                <input type="hidden" name="product_code" value="" />
                                <input type="hidden" name="barcode_no" value="" />
                                <input type="hidden" name="cde_id" value="" />
                                <input type="hidden" name="cde_title" value="" />
                                <input type="hidden" name="cit_price" value="<?php echo $info['cit_price']; ?>" />
                                <input type="hidden" name="cit_sale_price" value="<?php echo $info['cit_sale_price']; ?>" />
                                <input type="hidden" name="cit_subscribe_price" value="<?php echo $info['cit_subscribe_price']; ?>" />
                                <input type="hidden" name="total_price" value="<?php echo $unit_price; ?>" />
                                <input type="hidden" name="cit_file_1" value="<?php echo $info['cit_file_1']; ?>" />
                                <input type="hidden" name="is_sale" value="<?php echo $info['is_sale']; ?>" />
<!--                                <input type="hidden" name="delivery_price" value="2500" />
                                <input type="hidden" name="use_point" value="0" />
                                <input type="hidden" name="set_delivery" value="2500" /> -->
							</dl>
							<div class="price-box">
                            	<?php
									if($sale_percent > 0) {
								?>
								<div class="per"><?php echo $sale_percent; ?></div>
								<del><em><?php echo number_format($info['cit_price']); ?></em>원</del>
                                <?php
									}
								?>
								<div class="price"><strong><?php echo number_format($unit_price); ?></strong>원</div>
							</div>
						</div>
					</div>

					<div class="length">
						<button class="btn-minus" onclick="javascript:fnMinus();"></button>
						<input type="text" class="inp" name="qty" value="1" readonly />
						<button class="btn-plus" onclick="javascript:fnPlus();"></button>
					</div>

					<?php
						if(!empty($options)) {
                   ?>
						<hr class="hr">
                        <div class="option-box">
                        <?php
                            foreach($options as $row) {
                                $option = explode(',', $row['option_val']);
                        ?>
                            <div class="inp-box">
                                <select class="select-opt" name="option[]" a="<?php echo $row['option_name']; ?>">
                                    <option value=""><?php echo $row['option_name']; ?> 선택</option>
                                    <?php
                                        foreach($option as $row2) {
                                            echo '<option value="' . $row2 . '">' . $row2 . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        <?php	
                            }
                        ?>
                        </div>

                        <hr class="hr">
    
                        <div class="etc-prd">
                            <h5>선택상품</h5>
                            <ul id="select_option">
                            </ul>
                        </div>
					<?php } ?>

					<hr class="hr">

					<div class="price-detail">
                        <?php
							if($info['is_subscribe'] === 'y') {
								$dis = $info['cit_sale_price'] - $info['cit_subscribe_price'];
						?>
						<dl>
							<dt>구독할인</dt>
							<dd id="step1_subscribe_dis_price"><?php echo $dis > 0 ? '-' . number_format($dis) : '0'; ?>원</dd>
						</dl>
                        <?php
							}
						?>
						<dl class="total">
							<dt>총 결제금액</dt>
							<dd id="step1_total_price">
								<?php echo $info['is_subscribe'] == 'y' ? number_format($info['cit_subscribe_price']) : $unit_price; ?> 원
                            </dd>
						</dl>
					</div>
					
                        <div class="btn-box">
                            <button class="btn btn-type2" id="btnItem" onclick="javascript:fnAddCart('item');">1회구매</button>
							<?php
                                if($info['is_subscribe'] === 'y') {
		                            echo '<button class="btn btn-type1" id="btnSubscribe" onclick="javascript:fnAddCart(\'subscribe\');">구독하기</button>';
                            	}
							?>
                        </div>
    
<!--                        <div class="no-sale">
                            <a href="/product/product_detail?seq=<?php echo $info['cit_id']; ?>&type=item">할인없이 한번만 구매하기</a>
                        </div> -->

				</div>
			</div>
			<!-- // 1. 옵션선택 -->
        </form>
		</div>
		<div class="bg-aside"></div>
		<!-- // aside-order -->
		
	</div>
	<!-- // inner -->

<?php $this->load->view('common/reviewViewPopup'); ?>
    
<script>
var detailSwiper = null;
var detailSwiperMo = null;
$(document).ready(function(e) {
	$('#offset').val('0');
    fnSearch();
	
	$('#only_photo').on('click', function() {
		$('#offset').val('0');
		fnSearch();
	}); 
	
	detailSwiperMo = new Swiper("#detail_swipe_mo", {
		pagination : { 
			el:'#detail_swipe_mo .swiper-pagination',
			clickable : true, 
			type: 'bullets',
		},
		autoplay: { 
			delay: 3000, 
			disableOnInteraction: false, 
		},
		observer: true,
		observeParents: true,
		loop : true,	
	});
	
});

function fnSearch()
{
	$.ajax({
      	type:'POST',
    	url:'/review/ajaxReviewProduct',
		data : {cit_id: '<?php echo $info['cit_id']; ?>', offset : $('#offset').val(), only_photo: $('#only_photo').is(':checked') ? 'y' : 'n'},
		dataType:"json",
       	success:function(data){
			var str = '';
			if(data.list.length < data.perpage) {
				$('#more_button').hide();	
			}
			else {
				$('#more_button').show();	
			}
			if(data.list.length > 0) {
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
			}
       	},
        error:function(data){
         	alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
        }
   });
}
</script>	
<script>
$(function(){
		
		// 사이드 메뉴 열림 
	$('#BtnBuy').click(function(){
		$('body').addClass('aside-on');
		$('.aside-order > div').hide();
		$('.order-step1').show();
		$('.order_step').hide();
		$('select[name="option[]"]').val('');
		$('input[name=qty]').val(1);
		fnCalcTotalPrice();
		$('.aside-order').scrollTop(0);

		if(detailSwiper != null) {
			detailSwiper.destroy();	
		}
		detailSwiper = new Swiper("#detail_swipe", {
			pagination : { 
				el:'#detail_swipe .swiper-pagination',
				clickable : true, 
				type: 'bullets',
			},
			autoplay: { 
				delay: 3000, 
				disableOnInteraction: false, 
			},
			observer: true,
			observeParents: true,
			loop : true,	
		}); 
	});
		
	$('.aside-order .head .btn-back, .bg-aside').click(function(){
		$('body').removeClass('aside-on');
		return false;
	});
		
		// Mobile Scroll Event
	var position = $(window).scrollTop(); 
	$(window).scroll(function() {
		if($(window).outerWidth() < 1024){
			var scroll = $(window).scrollTop();
			if(scroll > position) {
				console.log('scrollDown');

			} else {
				 console.log('scrollUp');

			}
			position = scroll;
		}
	});
	
	$('select[name="option[]"]').on('change', function() {
		fnGetDetail();
	});
	
	<?php
		if(empty($options)) {
			echo 'fnGetDetail();';
		}
	?>
	
});

function fnAddCart(type)
{
	var option = fnOptionCheck();
	if(option != '') {
		showAlert('error', option + '을(를) 선택해 주세요.');
		return;	
	}
	
	$('input[name=cart_type]').val(type);
	gtag('event', 'conversion', {
	        'send_to': 'AW-10803290940/BPShCKat7ZEDELzGtJ8o', 'value':$('input[name=total_price]').val()
    });
	$.ajax({
       	type:'POST',
    	url:'/cart/ajaxAddCart',
		data : $('#order_data').serialize(),
		dataType:"json",
       	success:function(data){
			if(data.status == 'succ') {
				var msg = {msg : '장바구니에 담았습니다. 이동하시겠습니까?', cancel: '쇼핑계속하기', confirm:'장바구니가기'};
				showConfirm(msg
							, function() {
								location.href = '/cart/cart_list?type=' + type;
							});
			}
			else {
				showAlert('error', data.msg);	
			}
       	},
        error:function(data){
         	alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
        }
   });
}

function fnMinus()
{
	var qty = parseInt($('input[name="qty"]').val());
	if(qty <= 1) return;
		
	$('input[name="qty"]').val(--qty);
	fnCalcTotalPrice();
}
	
function fnPlus()
{
	var qty = parseInt($('input[name="qty"]').val());
	$('input[name="qty"]').val(++qty);
	fnCalcTotalPrice();
}

function fnCalcTotalPrice()
{
	var qty = $('input[name="qty"]').val();

	<?php if($info['is_subscribe'] === 'y') { ?>
	var price = $('input[name="cit_subscribe_price"]').val();
	var dis_price = $('input[name=cit_sale_price]').val() - price;
	$('#step1_subscribe_dis_price').html('-' + commify(dis_price * qty) + ' 원');
	
	<?php } else if($info['is_sale'] === 'y') { ?>
	var price = $('input[name="cit_sale_price"]').val();
	<?php } else {?>
	var price = $('input[name="cit_price"]').val();
	<?php } ?>
	var total = price * qty;	
	
	$('input[name=total_price]').val(total);
	$('#step1_total_price').html(commify(total) + ' 원');
}

function fnGetDetail()
{
	var bSelect = true;
	var option = new Array();
	
	$('select[name="option[]"]').each(function(index, element) {
		option.push($(this).val());
        if($(this).val() == '') bSelect = false;
    });

	if(bSelect) {
		$.ajax({
	       	type:'POST',
	    	url:'/product/ajaxOptionDetail',
			data : {seq: '<?php echo $info['cit_id']; ?>', option : option.join(',')},
			dataType:"json",
	       	success:function(data){
				if($('select[name="option[]"]').length > 0) {
					var str = '<li>'
							+ '		<div class="item">'
							+ '			<div class="img"><img src="<?php echo CDN_URL; ?>' + data.cde_filename + '"></div>'
							+ '			<dl>'
	//						+ '				<dt>칫솔 4P</dt>'
							+ '				<dd>' + data.cde_title + '</dd>'
							+ '			</dl>'
							+ '		</div>'
							+ '</li>';
					$('#select_option').html(str);
				}
				$('input[name="product_code"]').val(data.product_code);
				$('input[name="barcode_no"]').val(data.barcode_no);
				$('input[name="cde_id"]').val(data.cde_id);
				$('input[name="cde_title"]').val(data.cde_title);
	       	},
	        error:function(data){
	         	alert("오류가 발생하였습니다. 관리자에게 문의해 주세요.");
	        }
	   });
	}
	else {
		$('#select_option').html('');
	}
}

function fnOptionCheck() {
	var re = '';
	if($('select[name="option[]"]').length > 0) {
		$('select[name="option[]"]').each(function(index, element) {
            if($(this).val() == '' && re == '') {
				re = $(this).attr('a');	
			}
        });
	}
	return re;
}
</script>

