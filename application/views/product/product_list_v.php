	<div class="sub-head product">
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
					<a href="/product/product_list" <?php echo empty($req['seq']) ? 'class="active"' : ''; ?>>전체 (<?php echo $sum; ?>)</a>
                <?php
					foreach($category as $row) {
						echo '<a href="/product/product_list?seq=' . $row['cca_id'] . '" ' . ($req['seq'] === $row['cca_id'] ? 'class="active"' : '') . '>' . $row['cca_value'] . ' (' . $row['cca_cnt'] . ')</a>';
                    }
				?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="inner">
		<div class="product-list">
			<ul>
            <?php 
				foreach($list as $row) {
			?>
				<li>
					<a href="/product/product_detail?seq=<?php echo $row['cit_id']; ?>">
						<div class="thum"><img src="<?php echo CDN_URL . $row['cit_file_1']; ?>"></div>
						<div class="info">
							<dl>
								<dt><?php echo $row['cit_name']; ?></dt>
								<dd><?php echo $row['cit_summary']; ?></dd>
							</dl>
							<div class="price-box">
                            <?php
								if($row['is_order'] === 'y') {
									if($row['is_sale'] === 'y') {
										$percent = round(($row['cit_price'] - $row['cit_sale_price']) / $row['cit_price'] * 100) . '%';
							?>
								<div class="per"><?php echo $percent; ?></div>
								<del><em><?php echo number_format($row['cit_price']); ?></em>원</del>
								<div class="price"><strong><?php echo number_format($row['cit_sale_price']); ?></strong>원</div>
                            <?php
									}
									else {
							?>
								<div class="price"><strong><?php echo number_format($row['cit_price']); ?></strong>원</div>
                            <?php
									}
								}
							?>
							</div>
						</div>
					</a>
				</li>
            
            <?php	
				}
			?>
			</ul>
		</div>
	</div>