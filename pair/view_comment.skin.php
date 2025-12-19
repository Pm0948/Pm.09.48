<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$comment_action_url = https_url(G5_BBS_DIR)."/write_comment_update.php";

$character_name_1 = $write['wr_1'];
$character_name_2 = $write['wr_2'];

$image_urls_comment = array();

foreach ($view['file'] as $index => $file) {
    if (isset($file['view'])) {
        preg_match('/src="([^"]+)"/i', $file['view'], $matches);
        if (isset($matches[1])) {
            $image_urls_comment[$index] = $matches[1];
        }
    }
}
?>

<script>
// 글자수 제한
var char_min = parseInt(<?php echo $comment_min ?>); // 최소
var char_max = parseInt(<?php echo $comment_max ?>); // 최대
</script>

<!-- 댓글 시작 { -->
<div id="bo_vc">
    <?php
    $latest_wr_1 = null;
    $latest_wr_2 = null;

    // 최신 wr_1, wr_2 댓글을 찾기 위해 역순으로 탐색
    for ($i = count($list) - 1; $i >= 0; $i--) {
        if (!$latest_wr_1 && $list[$i]['wr_1']) {
            $latest_wr_1 = $list[$i];
        }
        if (!$latest_wr_2 && $list[$i]['wr_2']) {
            $latest_wr_2 = $list[$i];
        }
        if ($latest_wr_1 && $latest_wr_2) {
            break;
        }
    }
    ?>

    <div class="ch_a">
        <div class="ch_thumb">
            <div class="thumb" style="background-image: url('<?php echo isset($image_urls_comment[1]) ? $image_urls_comment[1] : ''; ?>');"></div>
            <div class="name"><?php echo $character_name_1; ?></div>
        </div>
          
        <div class="ch_content">
            <?php if ($latest_wr_1): ?>
            <?php
            $comment_id = $latest_wr_1['wr_id'];
            $comment = $latest_wr_1['content'];
            ?>
           
            <div id="c_<?php echo $comment_id; ?>" class="c_comment">
                <div class="co"><?php echo $comment; ?></div>
                <?php if ($latest_wr_1['is_del']) {
                $query_string = clean_query_string($_SERVER['QUERY_STRING']);
                ?>
                <ul>
                    <?php if ($latest_wr_1['is_del']) { ?><li><a href="<?php echo $latest_wr_1['del_link']; ?>" onclick="return comment_delete();" class="d_btn">[삭제]</a></li><?php } ?>
                </ul>
                <?php } ?>
            </div>
                
            <?php else: ?>
            <div class="c_comment">
                <div class="co">등록된 댓글이 없습니다.</div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="ch_b">
        <div class="ch_thumb">
            <div class="thumb" style="background-image: url('<?php echo isset($image_urls_comment[2]) ? $image_urls_comment[2] : ''; ?>');"></div>
            <div class="name"><?php echo $character_name_2; ?></div>
        </div>
        
        <div class="ch_content">
            <?php if ($latest_wr_2): ?>
            <?php
            $comment_id = $latest_wr_2['wr_id'];
            $comment = $latest_wr_2['content'];
            ?>
           
            <div id="c_<?php echo $comment_id; ?>" class="c_comment">
                <div class="co"><?php echo $comment; ?></div>
                <?php if ($latest_wr_2['is_del']) {
                $query_string = clean_query_string($_SERVER['QUERY_STRING']);
                ?>
                <ul>
                    <?php if ($latest_wr_2['is_del']) { ?><li><a href="<?php echo $latest_wr_2['del_link']; ?>" onclick="return comment_delete();" class="d_btn">[삭제]</a></li><?php } ?>
                </ul>
                <?php } ?>
            </div>
                
            <?php else: ?>
            <div class="c_comment">
                <div class="co">등록된 댓글이 없습니다.</div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- } 댓글 끝 -->

<?php if ($is_comment_write) {
	if($w == '')
		$w = 'c';
?>
<!-- 댓글 쓰기 시작 { -->
<aside id="bo_vc_w">
	<form name="fviewcomment" action="<?php echo $comment_action_url; ?>" onsubmit="return fviewcomment_submit(this);" method="post" autocomplete="off">
        <input type="hidden" name="w" value="<?php echo $w ?>" id="w">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
        <input type="hidden" name="comment_id" value="<?php echo $c_id ?>" id="comment_id">
        <input type="hidden" name="sca" value="<?php echo $sca ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="spt" value="<?php echo $spt ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <input type="hidden" name="wr_1" value="">
        <input type="hidden" name="wr_2" value="">

        <div class="w_box">
            <?php if ($comment_min || $comment_max) { ?><strong id="char_cnt"><span id="char_count"></span>글자</strong><?php } ?>
                       
            <textarea placeholder="댓글을 입력하세요." id="wr_content" name="wr_content" maxlength="10000" required class="required" title="내용"
            <?php if ($comment_min || $comment_max) { ?>onkeyup="check_byte('wr_content', 'char_count');"<?php } ?>><?php echo $c_wr_content;  ?></textarea>
            <?php if ($comment_min || $comment_max) { ?><script> check_byte('wr_content', 'char_count'); </script><?php } ?>
                           
            <script>
                $(document).on( "keyup change", "textarea#wr_content[maxlength]", function(){
                    var str = $(this).val()
                    var mx = parseInt($(this).attr("maxlength"))
                    if (str.length > mx) {
                        $(this).val(str.substr(0, mx));
                        return false;
                    }
                });
            </script>
        </div>
        
        <div class="w_btn">                
            <select name="wr_select" id="wr_select">           
                <option value="wr_1"><?php echo $character_name_1; ?></option>         
                <option value="wr_2"><?php echo $character_name_2; ?></option>            
            </select>

            <script>
                $(document).ready(function() {
                    $('#wr_select').change(function() {
                        var selected = $(this).val();
                        var selectedText = $('#wr_select option:selected').text();
        
                        if (selected === 'wr_1') {
                            $('input[name="wr_1"]').val(selectedText);
                            $('input[name="wr_2"]').val('');
                        } else if (selected === 'wr_2') {
                            $('input[name="wr_2"]').val(selectedText);
                            $('input[name="wr_1"]').val('');
                        }
                    });

                    // 페이지가 로드될 때 선택된 옵션에 따라 초기화
                    $('#wr_select').trigger('change');
                });
            </script>
            <div class="btn_confirm">
                <button type="submit" id="btn_submit" class="custom_btn">댓글등록</button>
            </div>
        </div>
	</form>
</aside>
<script>
// 초기 상태 설정
var save_before = '';
var save_html = document.getElementById('bo_vc_w').innerHTML;

// 댓글 제출 시 실행되는 함수
function fviewcomment_submit(f) {
    var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자

    // 금지단어 필터링을 위해 AJAX 요청
    var content = "";
    $.ajax({
        url: g5_bbs_url + "/ajax.filter.php",
        type: "POST",
        data: {
            "subject": "",
            "content": f.wr_content.value
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
            content = data.content;
        }
    });

    if (content) {
        alert("내용에 금지단어('" + content + "')가 포함되어있습니다");
        f.wr_content.focus();
        return false;
    }

    // 양쪽 공백 없애기
    f.wr_content.value = f.wr_content.value.replace(pattern, "");

    if (char_min > 0 || char_max > 0) {
        check_byte('wr_content', 'char_count');
        var cnt = parseInt(document.getElementById('char_count').innerHTML);
        if (char_min > 0 && char_min > cnt) {
            alert("댓글은 " + char_min + "글자 이상 쓰셔야 합니다.");
            return false;
        } else if (char_max > 0 && char_max < cnt) {
            alert("댓글은 " + char_max + "글자 이하로 쓰셔야 합니다.");
            return false;
        }
    } else if (!f.wr_content.value) {
        alert("댓글을 입력하여 주십시오.");
        return false;
    }

    if (typeof f.wr_name != 'undefined') {
        f.wr_name.value = f.wr_name.value.replace(pattern, "");
        if (f.wr_name.value == '') {
            alert('이름이 입력되지 않았습니다.');
            f.wr_name.focus();
            return false;
        }
    }

    if (typeof f.wr_password != 'undefined') {
        f.wr_password.value = f.wr_password.value.replace(pattern, "");
        if (f.wr_password.value == '') {
            alert('비밀번호가 입력되지 않았습니다.');
            f.wr_password.focus();
            return false;
        }
    }

    <?php if($is_guest) echo chk_captcha_js();  ?>

    set_comment_token(f);

    document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

// 댓글 삭제 시 확인을 위한 함수
function comment_delete() {
    return confirm("이 댓글을 삭제하시겠습니까?");
}

// 댓글 입력 폼을 표시하기 위한 함수
comment_box('', 'c'); // 기본으로 댓글 입력 폼이 보이도록 설정
</script>
    
<?php } else { ?>
<div class="no_permission">
    <p>접근 권한이 없습니다.</p>  
</div>
<?php } ?>
<!-- } 댓글 쓰기 끝 -->