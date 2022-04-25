<?php
	$unit_price = $info['is_sale'] == 'y' ? $info['cit_sale_price'] : $info['cit_price']; 
//	$unit_price = $info['cit_subscribe_price']; 
//	if($info['is_subscribe'] === 'y') $unit_price = $info['cit_subscribe_price'];
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
						</div>
					</div>
				</div>
			</div>
			<!-- // head -->
			
			<div class="body">
				<!-- 상세 내용 -->
				<?php echo $info['cit_content']; ?>
			</div>
			<!-- // body -->
			
		</div>
		<!-- // product-detail -->
		
		

		<!-- // aside-order -->
		
	</div>
	<!-- // inner -->

<?php $this->load->view('common/reviewViewPopup'); ?>
    
<script>
var detailSwiper = null;
var detailSwiperMo = null;
$(document).ready(function(e) {
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

</script>	
