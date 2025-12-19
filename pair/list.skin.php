<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$category_list = get_category_list($bo_table, $sca);

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/style.css">', 0);
?>

<!-- 게시판 목록 시작 { -->
<div id="list_container">
    <form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="spt" value="<?php echo $spt ?>">
        <input type="hidden" name="sca" value="<?php echo $sca ?>">
        <input type="hidden" name="sst" value="<?php echo $sst ?>">
        <input type="hidden" name="sod" value="<?php echo $sod ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <input type="hidden" name="sw" value="">

        <?php
            $categories = array();
            foreach ($list as $item) {
                $categories[$item['ca_name']][] = $item;
            }

               ksort($categories);
               foreach ($categories as $category_name => $category_list) {
        ?>

        <div id="category_section">
            <div id="ca_sub"><?php echo $category_name ? $category_name : 'LIST'; ?></div>
            <div id="list_card">
                <?php foreach ($category_list as $item) { 
                $file = get_file($bo_table, $item['wr_id']);
                if (isset($file[0]) && preg_match("/\.({$config['cf_image_extension']})$/i", $file[0]['file'])) {
                    $file_src = ''.$file[0]['path'].'/'.$file[0]['file'].'';
                } else {
                    $file_src = '';
                }
            
                $wr_5 = isset($item['wr_5']) ? $item['wr_5'] : '';
                $wr_6 = isset($item['wr_6']) ? $item['wr_6'] : '';
                ?>
                <a href="<?php echo $item['href'] ?>">
                    <div class="card" style="background-image: url('<?php echo $file_src; ?>'); border-color: <?php echo $wr_6; ?>;">
                        <?php if ($file_src == '') { ?>
                        <div class="no_image">No image</div>
                        <?php } ?>
                        <div class="card-overlay" style="background-color: <?php echo $wr_5; ?>; border-top: 1px solid <?php echo $wr_6; ?>;">
                            <div class="card-title"><?php echo $item['subject']; ?></div>
                        </div>
                    </div>
                </a>
                <?php } ?>
            </div>
        </div>
        <?php } ?>

        <?php if (count($list) == 0) { echo '<div class="empty_table">게시물이 없습니다.</div>'; } ?>
        
        
        <?php echo $write_pages;  ?>

        <?php if ($write_href) { ?>
        <div class="bo_fx">
            <?php if ($write_href) { ?>
            <a href="<?php echo $write_href ?>" class="custom_btn">등록하기</a>
            <?php } ?>
        </div>
        <?php } ?>
    </form>
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>


<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택복사") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "선택이동") {
        select_copy("move");
        return;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;

        f.removeAttribute("target");
        f.action = "./board_list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == "copy")
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = "./move.php";
    f.submit();
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->
