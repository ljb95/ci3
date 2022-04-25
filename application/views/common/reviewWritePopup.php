	<!-- 리뷰 작성(제품선택) -->
	<div class="modal fade" role="dialog" aria-labelledby="introHeader" aria-hidden="true" tabindex="-1" data-backdrop="static"  id="modalReview">
		<div class="modal-dialog" style="max-width:830px; margin-top:20px;">
			<div class="modal-content" id="product_select_wrap">
				<div class="modal-body" id="review_wrap1">
					<div class="modal-msg1">
						<div class="h3 mb15">리뷰 상품</div>
						<div class="modal-txt1 f18 mb20"><?php echo empty($orders) ? '리뷰작성가능한 구매상품이 없습니다' : '리뷰하실  제품을 선택해주세요'; ?> </div>
					</div>
					<div class="modal-review-choices">
                    	<?php
							if(!empty($orders)) {
						?>
                            <ul>
                            <?php
                                foreach($orders as $row) {
                            ?>
                                <li>
                                    <input type="hidden" name="order_id[]" value="<?php echo $row['order_id']; ?>" />
                                    <input type="hidden" name="cit_name[]" value="<?php echo $row['cit_name']; ?>" />
                                    <input type="hidden" name="cit_file_1[]" value="<?php echo $row['cit_file_1']; ?>" />
                                    <input type="hidden" name="cit_file_2[]" value="<?php echo $row['cit_file_2']; ?>" />
                                    <input type="hidden" name="cit_file_3[]" value="<?php echo $row['cit_file_3']; ?>" />
                                    <input type="hidden" name="cit_file_4[]" value="<?php echo $row['cit_file_4']; ?>" />
                                    <input type="hidden" name="cit_file_5[]" value="<?php echo $row['cit_file_5']; ?>" />
                                    <input type="hidden" name="cit_file_6[]" value="<?php echo $row['cit_file_6']; ?>" />
                                    <input type="hidden" name="cit_file_7[]" value="<?php echo $row['cit_file_7']; ?>" />
                                    <input type="hidden" name="cit_file_8[]" value="<?php echo $row['cit_file_8']; ?>" />
                                    <input type="hidden" name="cit_file_9[]" value="<?php echo $row['cit_file_9']; ?>" />
                                    <input type="hidden" name="cit_file_10[]" value="<?php echo $row['cit_file_10']; ?>" />
                                    <input type="hidden" name="cde_filename[]" value="<?php echo $row['cde_filename']; ?>" />
    
                                    <label>
                                        <input type="radio" class="radio-reviews" name="cod_id[]" value="<?php echo $row['cod_id']; ?>">
                                        <div>
                                            <div class="img"><img src="<?php echo CDN_URL . $row['cit_file_1']; ?>" style="width:100%"></div>
                                            <div class="info" style="padding:0 20px;">
                                                <dl>
                                                    <dt><?php echo $row['cit_name']; ?></dt>
                                                    <dd><?php echo $row['cde_title']; ?></dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </label>
                                </li>
                            <?php	
                                }
                            ?>
                            </ul>
                        <?php } ?>
					</div>
				</div>
				<div class="modal-body" style="display:none" id="review_wrap2">
					<div class="modal-msg1">
						<div class="h3 mb15">리뷰 작성 </div>
						<hr class="hr2">
						<div class="modal-review-write">
                        	<form id="frmSave" onSubmit="return false;">
                            	<input type="hidden" name="order_id" />
                                <input type="hidden" name="cod_id" />
                                <h4 id="show_product_name">클린디 칫솔 4P</h4>
                                <div class="slider">
                                    <div class="swiper-container">
                                        <div class="swiper-wrapper" id="show_product_img">
											<div class="swiper-slide"><p><img src="/res/img/mypage/tmp_review.jpg"></p></div>
                                        
                                        </div>
                                    </div>
                                    <div class="swiper-button-next"></div>
                                    <div class="swiper-button-prev"></div>
                                </div>
                                <hr class="hr2 mt30 mb20">
                                
                                <div class="grade large event mb20" id="review_score">
                                    <i></i>
                                    <i></i>
                                    <i></i>
    
                                    <i></i>
                                    <i></i>
                                </div>
                                <div class="textarea-box">
                                    <textarea class="textarea" name="cre_content" placeholder="내용을 입력하세요"></textarea>
                                </div>
                                <div class="file-box" style="margin-bottom:0">
                                    <button class="btn-upload" onclick="javascript:fnPopupAddImgShow(); "><i class="xi-paperclip"></i> <span>사진등록</span></button>
                                </div>
                                <div class="file-box" style="margin-top:0">
                                    <div class="files" id="review_img_list">
                                        
                                    </div>
                                </div>
							</form>
						</div>
					</div>
				</div>
				<div class="modal-footer text-center">
					<button class="btn btn-type2 w280" data-dismiss="modal">취소</button>
					<button class="btn btn-type1 w280" onclick="javascript:fnPopupNextReview();">확인</button>
				</div>
			</div>

			<div class="modal-content" id="img_upload_wrap">
				<div class="modal-body">
					<div class="modal-msg1">
						<div class="h3 mb30">사진첨부하기 </div>
						<hr class="hr2">
						<div class="modal-file-upload">
							<div class="head">
								<div>마우스를 드래그하여 순서를 변경할 수 있습니다. </div>
								<div>
									<a href="javascript:void(0);" class="btn-choice"><label for="add_review_file" style="cursor:pointer;">파일선택</label></a>
									<a href="#" onclick="javascript:fnPopupDeleteImgAll(); return false;" class="btn-del">전체삭제</a>
                                  	<input type="file" id="add_review_file" style="display:none"  multiple/>
								</div>
							</div>
							<div class="body">
								<div class="file-upload">
									<div class="msg" id="img_list_wrap" style="overflow:auto">
                                    	<div id="add_file_wrap">
                                            <label for="add_review_file" style="cursor:pointer;">
	                                    		<img src="/res/img/mypage/ico_file.png">
                                            </label>
                                            <p>파일을 드래그하거나 
                                            	<a href="javascript:void(0);"  class="under">
                                                	<label for="add_review_file" style="border-bottom:1px solid #666; cursor:pointer;">여기를 클릭</label>
                                                </a>해서 이미지를 추가해주세요 
                                            </p>
                                        </div>
                                    </div>
								</div>
							</div>
							<div class="foot">이미지는 한번에 10개까지 선택할 수 있습니다. </div>
						</div>
					</div>
					
				</div>
				<div class="modal-footer text-center">
					<button class="btn btn-type2 w280" onclick="javascript:fnPopupAddImgCancel(); ">취소</button>
					<button class="btn btn-type1 w280" onclick="javascript:fnPopupAddImgComplete(); ">등록</button>
				</div>
			</div>
            
		</div>
	</div>
	
	<!-- // 리뷰 작성(제품선택) -->
    
<script>
var uploadFiles = [];

$(document).ready(function(e) {
	$("#img_list_wrap").on("dragenter", function(e) { //드래그 요소가 들어왔을떄
	}).on("dragleave", function(e) { //드래그 요소가 나갔을때
	}).on("dragover", function(e) {
		e.stopPropagation();
		e.preventDefault();
	}).on('drop', function(e) { //드래그한 항목을 떨어뜨렸을때
		e.preventDefault();
		var files = e.originalEvent.dataTransfer.files; //드래그&드랍 항목
		fnPopupAddImgPreview(files);
	});

	$('#modalReview').on('show.bs.modal', function(){
		$('input[name="cod_id[]"]').prop('checked', false);
		$('#review_wrap1').show();
		$('#review_wrap2').hide();
		$('#product_select_wrap').show();
		$('#img_upload_wrap').hide();
		$('.modal-review-choices').scrollTop(0);
		
		$('#review_img_list').html('');
		$('textarea[name=cre_content]').val('');
		$('#review_score i').removeClass('on');
	});

    $('#add_review_file').on('change', function() {
		if($(this).val() == '') {
			return;	
		}
		
		fnPopupAddImgPreview($('#add_review_file')[0].files);
		$(this).val('');
	});
	
});

function fnPopupAddImgPreview(files) {
	var cnt = files.length;
	$.each(uploadFiles, function(i, file) {
		if(file.upload) cnt++;
	});
	if(cnt > 10) {
		showAlert('error', '파일은 최대 10개까지만 추가 가능합니다.');
		return;	
	}
		
	for(var i = 0; i < files.length; i++) {
		var ext = files[i].name.split('.').pop().toLowerCase();
		if($.inArray(ext, ['jpg', 'pdf', 'png', 'jpeg', 'gif']) == -1) {
			showAlert('error', '이미지 파일만 업로드 가능합니다.');
			return;
		}
	}

		
	$('#add_file_wrap').hide();
	$('#img_list_wrap').css('display', 'block');
	for(var i = 0; i < files.length; i++) {
		var file = files[i];
		var size = uploadFiles.push(file) - 1 ; //업로드 목록에 추가
		uploadFiles[size].upload = true;

		var reader = new FileReader();
		reader.onload = (function(f, size) {
			return function(e) {
				var str = '<div class="img_wrap" style="width:100%; position:relative; margin-bottom:10px;">'
						+ '		<img style="max-width:100% !important; width:unset !important;" src="' + e.target.result + '">'
						+ ' 	<a href="#" onclick="javascript:fnPopupDeleteImg(this, ' + size + '); return false;">'
						+ '			<i class="fas fa-times" style="font-size:20px; color:#000; top:10px; right:10px; position:absolute;"></i>'
						+ '		</a>'
						+ '</div>';
				$('#img_list_wrap').append(str);	
			};
		})(file, size);
		reader.readAsDataURL(file);
	}
}

function fnPopupDeleteImg(obj, idx)
{
	$(obj).parent().remove();
	uploadFiles[idx].upload = false;
	if($('.img_wrap').length <= 0) {
		$('#img_list_wrap').css('display', 'flex');
		$('#add_file_wrap').show();	
	}
}

function fnPopupDeleteImgAll()
{
	$('.img_wrap').remove();
	$('#img_list_wrap').css('display', 'flex');
	$('#add_file_wrap').show();	
	uploadFiles = [];
}

var bSave = false;
function fnPopupNextReview()
{
	if($('#review_wrap1').css('display') == 'block') {
		var cod_id = '';
		var order_id = '';
		$('input[name="cod_id[]"]').each(function(index, element) {
			if($(this).is(':checked')) {
				cod_id = $(this).val();
				$('#frmSave input[name=order_id]').val($('input[name="order_id[]"]').eq(index).val());
				$('#show_product_name').html($('input[name="cit_name[]"]').eq(index).val());
				var str = '';
				if($('input[name="cit_file_1[]"]').eq(index).val() != '') {
					str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + $('input[name="cit_file_1[]"]').eq(index).val() + '" style="max-height:160px;"></p></div>';
				}
				if($('input[name="cit_file_2[]"]').eq(index).val() != '') {
					str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + $('input[name="cit_file_2[]"]').eq(index).val() + '" style="max-height:160px;"></p></div>';
				}
				if($('input[name="cit_file_3[]"]').eq(index).val() != '') {
					str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + $('input[name="cit_file_3[]"]').eq(index).val() + '" style="max-height:160px;"></p></div>';
				}
				if($('input[name="cit_file_4[]"]').eq(index).val() != '') {
					str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + $('input[name="cit_file_4[]"]').eq(index).val() + '" style="max-height:160px;"></p></div>';
				}
				if($('input[name="cit_file_5[]"]').eq(index).val() != '') {
					str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + $('input[name="cit_file_5[]"]').eq(index).val() + '" style="max-height:160px;"></p></div>';
				}
				if($('input[name="cit_file_6[]"]').eq(index).val() != '') {
					str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + $('input[name="cit_file_6[]"]').eq(index).val() + '" style="max-height:160px;"></p></div>';
				}
				if($('input[name="cit_file_7[]"]').eq(index).val() != '') {
					str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + $('input[name="cit_file_7[]"]').eq(index).val() + '" style="max-height:160px;"></p></div>';
				}
				if($('input[name="cit_file_8[]"]').eq(index).val() != '') {
					str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + $('input[name="cit_file_8[]"]').eq(index).val() + '" style="max-height:160px;"></p></div>';
				}
				if($('input[name="cit_file_9[]"]').eq(index).val() != '') {
					str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + $('input[name="cit_file_9[]"]').eq(index).val() + '" style="max-height:160px;"></p></div>';
				}
				if($('input[name="cit_file_10[]"]').eq(index).val() != '') {
					str += '<div class="swiper-slide"><p><img src="<?php echo CDN_URL; ?>' + $('input[name="cit_file_10[]"]').eq(index).val() + '" style="max-height:160px;"></p></div>';
				}
				$('#show_product_img').html(str);

				var reviewSwiper = new Swiper(".modal-review-write .swiper-container", {
					navigation: {
					  nextEl: ".swiper-button-next",
					  prevEl: ".swiper-button-prev",
					observer: true,
					observeParents: true,
					},
				});
			}
		});
		
		if(cod_id == '') {
			showAlert('error', '리뷰를 작성할 상품을 선택해 주세요.');
			return;	
		}
		
		$('input[name=cod_id]').val(cod_id);
		$('#review_wrap1').hide();
		$('#review_wrap2').show();
	}
	else {
		var score = 0;
		$('#review_score i').each(function(index, element) {
            if($(this).hasClass('on')) {
				score++;
			}
        });
		
		if(score <= 0) {
			showAlert('error', '리뷰 점수를 선택해 주세요.');
			return;	
		}
		
		var formData = new FormData();
		formData.append('cre_score', score);
		formData.append('cre_content', $('textarea[name=cre_content]').val());
		formData.append('order_id', $('#frmSave input[name=order_id]').val());
		formData.append('cod_id', $('#frmSave input[name=cod_id]').val());
		$.each(uploadFiles, function(i, file) {
			if(file.upload) {
				formData.append('files[]', file, file.name);
			}
		});
		
		if(bSave) {
			showAlert('error', '작성하신 리뷰를 등록중입니다. 잠시 기다려 주세요');
			return;	
		}
		bSave = true;
	  	$.ajax({
			method: 'POST',
			url:'/my/make/ajaxInsertReview',
			data: formData,
			processData: false,
			contentType: false,
			success : function(res, textStatus, jqXHR) {
				bSave = false;
				res = JSON.parse(res);
				if(res.status == 'succ') {
					showAlert('success', res.msg, function() {location.reload();});
				}
				else if(res.status == 'login') {
					showAlert('error', res.msg, function() {location.href="/member/login";});
				}
				else {
					showAlert('error', res.msg);
				}
			},
		   	error: function(request,status,error){
				bSave = false;
       			alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		    }
		});		

	}
}

function fnPopupAddImgShow()
{
	$('.img_wrap').remove();
	var bExists = false;
	$.each(uploadFiles, function(i, file) {
		if(file.upload) {
			bExists = true;
			var reader = new FileReader();
			reader.onload = (function(f, i) {
				return function(e) {
					var str = '<div class="img_wrap" style="width:100%; position:relative; margin-bottom:10px;">'
							+ '		<img style="max-width:100% !important; width:unset !important;" src="' + e.target.result + '">'
							+ ' 	<a href="#" onclick="javascript:fnPopupDeleteImg(this, ' + i + '); return false;">'
							+ '			<i class="fas fa-times" style="font-size:20px; color:#000; top:10px; right:10px; position:absolute;"></i>'
							+ '		</a>'
							+ '</div>';
					$('#img_list_wrap').append(str);
				};
			})(file, i);
			reader.readAsDataURL(file);
		}
	});

	if(!bExists) {
		$('#img_list_wrap').css('display', 'flex');
		$('#add_file_wrap').show();	
	}
	else {
		$('#add_file_wrap').hide();
		$('#img_list_wrap').css('display', 'block');
	}
	$('#img_upload_wrap').show();
	$('#product_select_wrap').hide();	
}

function fnPopupAddImgCancel() {
	$('#img_upload_wrap').hide();
	$('#product_select_wrap').show();	
}

function fnPopupAddImgComplete()
{
	var str = '';
	$.each(uploadFiles, function(i, file) {
		if(file.upload) {
			str += '<div style="margin-right:20px; margin-top:10px;">'
				+ file.name
				+ '	<button class="btn-del" onclick="javascript:fnPopupDeleteImg(this, ' + i + '); return false;"><i class="xi-close-thin"></i></button>'
				+ '</div>';
		}
	});
	$('#review_img_list').html(str);
	$('#img_upload_wrap').hide();
	$('#product_select_wrap').show();	
}
</script>
