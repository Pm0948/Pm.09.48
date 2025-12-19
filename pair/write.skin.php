<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$background_image_url = isset($file[3]['file']) ? G5_DATA_URL.'/file/'.$bo_table.'/'.$file[3]['file'] : '';

function display_image_or_text($file, $index, $alt_text) {
    if (!empty($file[$index]['file'])) {
        echo '<img src="' . $file[$index]['path'] . '/' . $file[$index]['file'] . '" alt="' . $alt_text . '">';
    } else {
        echo '<p>No image</p>';
    }
}

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/coloris.css">', 1);
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<style>
    * {
        font-family: "Wanted Sans Variable";
        font-size: 14px;
        outline-color: <?php echo !empty($write['wr_5']) ? $write['wr_5'] : '#BBBBBB'; ?>;
    }
    
    :root {
        --color-1: <?php echo !empty($write['wr_4']) ? $write['wr_4'] : '#EEEEEE'; ?>;
        --color-2: <?php echo !empty($write['wr_5']) ? $write['wr_5'] : '#BBBBBB'; ?>;
        --line-color: <?php echo !empty($write['wr_6']) ? $write['wr_6'] : '#000000'; ?>;
        --text-color: <?php echo !empty($write['wr_7']) ? $write['wr_7'] : '#000000'; ?>;
        --tape-gray: #DDDDDD;
        --tape-edge-gray: #A9A9A9;
    }
    
    ::selection {background: <?php echo !empty($write['wr_5']) ? $write['wr_5'] : '#BBBBBB'; ?>;}
    
    html, 
    body {
        background-color: <?php echo !empty($write['wr_8']) ? $write['wr_8'] : 'transparent'; ?>;
        background-image: url('<?php echo $background_image_url; ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    #header,
    #footer,
    #goto_top,
    hr.padding {display: none;}
    
    .fix-layout {
        max-width: 100%;
        height: 100vmin;
        margin: 0;
        padding: 0;
    }
</style>

<div id="write">
    <div id="write_container">
        <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
            <input type="hidden" name="w" value="<?php echo $w ?>">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
            <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
            <input type="hidden" name="sca" value="<?php echo $sca ?>">
            <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
            <input type="hidden" name="stx" value="<?php echo $stx ?>">
            <input type="hidden" name="spt" value="<?php echo $spt ?>">
            <input type="hidden" name="sst" value="<?php echo $sst ?>">
            <input type="hidden" name="sod" value="<?php echo $sod ?>">
            <input type="hidden" name="page" value="<?php echo $page ?>">
	
            <?php
            $option = '';
            $option_hidden = '';
               if ($is_secret) {
                   $option = '';

                   if ($is_secret) {
                       if ($is_admin || $is_secret==1) {
                           $option .= "\n".'<input type="checkbox" id="secret" name="secret" value="secret" '.$secret_checked.'>'."\n".'<label for="secret">비밀글</label>';
                       } else {
                           $option_hidden .= '<input type="hidden" name="secret" value="secret">';
                       }
                   }
               }

               echo $option_hidden;
            ?>
            
            <div class="w_box">
                <div class="w_acc">
                    <div class="tape"></div>
                    <div class="w_text">
                    <table>
                        <tbody>
                            <?php if ($option) { ?>
                            <tr>
                                <th>옵션</th>
                                <td>
                                    <?php echo $option ?>
                                </td>
                            </tr>
                            <?php } ?>
                            
                            <?php if ($is_category) { ?>
                            <tr>
                                <th><label for="ca_name">분류<strong class="sound_only">필수</strong></label></th>
                                <td>
                                    <select name="ca_name" id="ca_name" required class="required" >
                                        <option value="">선택하세요</option>
                                        <?php echo $category_option ?>
                                    </select>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                    
                    <table>
                        <tbody>
                            <tr>
                                <th rowspan="2"><label for="wr_subject">페어명<strong class="sound_only">필수</strong></label></th>
                                <td>
                                    <input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="frm_input required" maxlength="255" placeholder="한글">
                                </td>
                            </tr>
                            
                            <tr>
                                <td><input type="text" name="wr_3" id="wr_3" value="<?php echo $wr_3 ?>" placeholder="영문"></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <table>
                        <tbody>
                            <tr>
                                <th rowspan="2">배경</th>
                                <td>
                                    <div class="bg_thumb i_t">
                                        <?php display_image_or_text($file, 3, 'Background Image'); ?>
                                    </div>
                                    
                                    <input type="file" name="bf_file[3]" title="파일첨부1" class="frm_file frm_input">
                                    <?php if ($is_file_content) { ?>
                                    <input type="text" name="bf_content[3]" value="<?php echo ($w == 'u') ? $file[3]['bf_content'] : ''; ?>" title="파일 설명을 입력해주세요." class="frm_file frm_input">
                                    <?php } ?>
                                    <?php if($w == 'u' && $file[3]['file']) { ?>
                                    <input type="checkbox" id="bf_file_del3" name="bf_file_del[3]" value="1"> <label for="bf_file_del3">파일삭제</label>
                                    <?php } ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    <input type="text" value="<?php echo $wr_8 ?>" name="wr_8" id="wr_8" data-coloris>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <table>
                        <tbody>
                            <tr>
                                <th><label for="wr_4">박스색</label></th>
                                <td><input type="text" value="<?php echo !empty($write['wr_4']) ? $write['wr_4'] : '#EEEEEE'; ?>" name="wr_4" id="wr_4" data-coloris></td>
                            </tr>
                            
                            <tr>
                                <th><label for="wr_5">메인색</label></th>
                                <td><input type="text" value="<?php echo !empty($write['wr_5']) ? $write['wr_5'] : '#BBBBBB'; ?>" name="wr_5" id="wr_5" data-coloris></td>
                            </tr>
                            
                            <tr>
                                <th><label for="wr_6">라인색</label></th>
                                <td><input type="text" value="<?php echo !empty($write['wr_6']) ? $write['wr_6'] : '#000000'; ?>" name="wr_6" id="wr_6" data-coloris></td>
                            </tr>
                            
                            <tr>
                                <th><label for="wr_7">폰트색</label></th>
                                <td><input type="text" value="<?php echo !empty($write['wr_7']) ? $write['wr_7'] : '#000000'; ?>" name="wr_7" id="wr_7" data-coloris></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
                
                <div class="w_acc_img">
                    <div class="tape"></div>
                    <div class="w_img">
                        <table>
                            <tbody>
                                <tr>
                                    <th>페어 이미지</th>
                                    <td>
                                        <div class="pair_thumb i_t">
                                            <?php display_image_or_text($file, 0, 'Pair Image'); ?>
                                        </div>
                                        <input type="file" name="bf_file[0]" title="파일첨부1" class="frm_file frm_input">
                                        <?php if ($is_file_content) { ?>
                                        <input type="text" name="bf_content[0]" value="<?php echo ($w == 'u') ? $file[0]['bf_content'] : ''; ?>" title="파일 설명을 입력해주세요." class="frm_file frm_input">
                                        <?php } ?>
                                        <?php if($w == 'u' && $file[0]['file']) { ?>
                                        <input type="checkbox" id="bf_file_del0" name="bf_file_del[0]" value="1"> <label for="bf_file_del0"> 파일삭제</label>
                                        <?php } ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    
                        <table>
                            <tbody>
                                <tr>
                                    <th><label for="wr_1">캐릭터 #1</label></th>
                                    <td>
                                        <input type="text" name="wr_1" id="wr_1" value="<?php echo $wr_1 ?>">
                                    </td>
                                </tr>
                            
                                <tr>
                                    <th><label for="wr_2">캐릭터 #2</label></th>
                                    <td>
                                        <input type="text" name="wr_2" id="wr_2" value="<?php echo $wr_2 ?>">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    
                        <table>
                            <tbody>
                                <tr>
                                    <th>두상 #1</th>
                                    <th>두상 #2</th>
                                </tr>
                            
                                <tr>
                                    <td>
                                        <div class="ch_thumb i_t">
                                            <?php display_image_or_text($file, 1, 'Character Head Image 1'); ?>
                                        </div>
                                        <input type="file" name="bf_file[1]" title="파일첨부1" class="frm_file frm_input">
                                        <?php if ($is_file_content) { ?>
                                        <input type="text" name="bf_content[1]" value="<?php echo ($w == 'u') ? $file[1]['bf_content'] : ''; ?>" title="파일 설명을 입력해주세요." class="frm_file frm_input">
                                        <?php } ?>
                                        <?php if($w == 'u' && $file[1]['file']) { ?>
                                        <input type="checkbox" id="bf_file_del1" name="bf_file_del[1]" value="1"> <label for="bf_file_del1"> 파일삭제</label>
                                        <?php } ?>
                                    </td>
                                
                                    <td>
                                        <div class="ch_thumb i_t">
                                            <?php display_image_or_text($file, 2, 'Character Head Image 2'); ?>
                                        </div>
                                        <input type="file" name="bf_file[2]" title="파일첨부1" class="frm_file frm_input">
                                        <?php if ($is_file_content) { ?>
                                        <input type="text" name="bf_content[2]" value="<?php echo ($w == 'u') ? $file[2]['bf_content'] : ''; ?>" title="파일 설명을 입력해주세요." class="frm_file frm_input">
                                        <?php } ?>
                                        <?php if($w == 'u' && $file[2]['file']) { ?>
                                        <input type="checkbox" id="bf_file_del2" name="bf_file_del[2]" value="1"> <label for="bf_file_del2"> 파일삭제</label>
                                        <?php } ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="btn_confirm">
                <button type="submit" id="btn_submit" accesskey="s" class="custom_btn">등록하기</button>
                <button onclick="history.back();" class="custom_btn">뒤로가기</button>
            </div>
  
        </form>
    </div>
</div>

<script src="<?=$board_skin_url?>/js/coloris.js"></script>

<script>
	function html_auto_br(obj)
	{
		if (obj.checked) {
			result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
			if (result)
				obj.value = "html2";
			else
				obj.value = "html1";
		}
		else
			obj.value = "";
	}

	function fwrite_submit(f)
	{
		<?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

		var subject = "";
		var content = "";
		$.ajax({
			url: g5_bbs_url+"/ajax.filter.php",
			type: "POST",
			data: {
				"subject": f.wr_subject.value,
				"content": f.wr_content.value
			},
			dataType: "json",
			async: false,
			cache: false,
			success: function(data, textStatus) {
				subject = data.subject;
				content = data.content;
			}
		});

		if (subject) {
			alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
			f.wr_subject.focus();
			return false;
		}

		if (content) {
			alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
			if (typeof(ed_wr_content) != "undefined")
				ed_wr_content.returnFalse();
			else
				f.wr_content.focus();
			return false;
		}

		if (document.getElementById("char_count")) {
			if (char_min > 0 || char_max > 0) {
				var cnt = parseInt(check_byte("wr_content", "char_count"));
				if (char_min > 0 && char_min > cnt) {
					alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
					return false;
				}
				else if (char_max > 0 && char_max < cnt) {
					alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
					return false;
				}
			}
		}

		<?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

		document.getElementById("btn_submit").disabled = "disabled";

		return true;
	}
</script>

<script>
    Coloris({
        theme: 'polaroid',
        themeMode: 'light',
        margin: 2,
        alpha: true,
        format: 'hex',
        formatToggle: false,
        clearButton: true,
        clearLabel: '비우기',
        inline: false,
        defaultColor: '#000000',
    });   
</script>