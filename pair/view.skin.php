<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$sql = "SELECT wr_id, wr_subject FROM {$g5['write_prefix']}{$bo_table} WHERE wr_is_comment = 0 ORDER BY wr_id DESC";
$result = sql_query($sql);

$board_list = array();

if ($result) {
    while ($row = sql_fetch_array($result)) {
        $board_list[] = array(
            'href' => './board.php?bo_table=' . $bo_table . '&wr_id=' . $row['wr_id'],
            'subject' => $row['wr_subject']
        );
    }
}

$image_urls_view = array();

foreach ($view['file'] as $index => $file) {
    if (isset($file['view'])) {
        preg_match('/src="([^"]+)"/i', $file['view'], $matches);
        if (isset($matches[1])) {
            $image_urls_view[$index] = $matches[1];
        }
    }
}

if (!$is_member) {
    // 비회원 상태
    echo '<link rel="stylesheet" href="'.$board_skin_url.'/css/style.css">';
} else {
    // 회원 상태
    add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/style.css">', 0);
}
?>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

<style>
    * {
        font-family: "Wanted Sans Variable";
        font-size: 14px;
        outline-color: <?php echo isset($view['wr_5']) ? $view['wr_5'] : ''; ?>;
    }
    
    :root {
        --color-1: <?php echo isset($view['wr_4']) ? $view['wr_4'] : ''; ?>;
        --color-2: <?php echo isset($view['wr_5']) ? $view['wr_5'] : ''; ?>;
        --line-color: <?php echo isset($view['wr_6']) ? $view['wr_6'] : ''; ?>;
        --text-color: <?php echo isset($view['wr_7']) ? $view['wr_7'] : ''; ?>;
        --tape-gray: #DDDDDD;
        --tape-edge-gray: #A9A9A9;
    }
    
    ::selection {background: <?php echo isset($view['wr_5']) ? $view['wr_5'] : '#BBBBBB'; ?>;}
    
    html, body {
        background-color: <?php echo isset($view['wr_8']) ? $view['wr_8'] : 'transparent'; ?>;
        background-image: url('<?php echo isset($image_urls_view[3]) ? $image_urls_view[3] : ''; ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
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

<div id="view">
    <div id="view_container">
        <div id="left_box">
            <div class="v_header">
                <div class="empty line_1"></div>
                <div class="h_name"><?php echo isset($view['wr_3']) ? $view['wr_3'] : ''; ?></div>
                <div class="h_btn">
                    <ul>
                        <li>
                            <a href="<?php echo $list_href ?>"><span data-tooltip="LIST">●</span></a>
                        </li>

                        <li>
                            <?php if ($update_href) { ?>
                            <a href="<?php echo $update_href ?>"><span data-tooltip="EDIT">●</span></a>
                            <?php } else { ?>
                            <span>●</span>
                            <?php } ?>
                        </li>

                        <li>
                            <?php if ($delete_href) { ?>
                            <a href="<?php echo $delete_href ?>" onclick="del(this.href); return false;"><span data-tooltip="DELETE">●</span></a>
                            <?php } else { ?>
                            <span>●</span>
                            <?php } ?>
                        </li>

                        <li>
                            <?php if ($write_href) { ?>
                            <a href="<?php echo $write_href ?>"><span data-tooltip="ADD">●</span></a>
                            <?php } else { ?>
                            <span>●</span>
                            <?php } ?>
                        </li>
                    </ul>
                </div>
                <div class="empty line_2"></div>
            </div>
            <div class="v_content">
                <div class="favorites">
                    <div class="tag">✔ Favorites</div>
                    <div class="list_box">
                        <div class="board_list">
                            <div class="b_sub">List</div>
                            <div class="list">
                                <ul>
                                    <?php foreach ($board_list as $item) { ?>
                                    <li>
                                        <span class="material-symbols-outlined">subdirectory_arrow_right</span>
                                        <a href="<?php echo $item['href']; ?>" class="sub">
                                            <?php echo $item['subject']; ?>
                                        </a>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </div>
                        <div class="etc_tag">Pinned Issue</div>
                        <div class="acc">
                            <div class="tape"></div>
                            <div class="pair_img">
                                <div class="img" style="background-image: url('<?php echo isset($image_urls_view[0]) ? $image_urls_view[0] : ''; ?>');"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="comment">
                    <div class="top_tape"></div>
                    <?php include_once(G5_BBS_PATH.'/view_comment.php'); ?>
                </div>
            </div>
        </div>
        <div id="right_box">
            <div class="a_header">
                <div class="h_etc">
                    <div class="x-btn">✖</div>
                </div>
                <div class="h_tag">
                    <div class="a_empty line_3"></div>
                    <div class="a_sub">ARCHIVE</div>
                    <div class="a_empty line_4"></div>
                </div>
            </div>

            <div class="a_content">
                <div id="loadMore" style="display: none;">더보기</div>
                <?php
                $cmt_amt = count($list);
                for ($i = 0; $i < $cmt_amt; $i++) {
                    $comment_id = $list[$i]['wr_id'];
                    $cmt_depth = strlen($list[$i]['wr_comment_reply']) * 10;
                    $comment = $list[$i]['content'];

                    $selected_wr_1 = $list[$i]['wr_1'];
                    $selected_wr_2 = $list[$i]['wr_2'];
                ?>
                <article id="c_<?php echo $comment_id ?>" style="display: none;" <?php if ($cmt_depth) { ?>class="is-reply" style="border-left-width:<?php echo $cmt_depth ?>px;"<?php } ?>>
                    <header>
                        <span><?php echo $selected_wr_1 ?><?php echo $selected_wr_2 ?></span>
                        <span class="bo_vc_hdinfo"><time datetime="<?php echo date('Y-m-d\TH:i:s+09:00', strtotime($list[$i]['datetime'])) ?>"><?php echo date('Y.m.d H:i', strtotime($list[$i]['datetime'])) ?></time></span>
                    </header>
                    <p><?php echo $comment ?></p>
                </article>
                <?php } ?>
                <?php if ($i == 0) { ?><p id="bo_vc_empty">등록된 댓글이 없습니다.</p><?php } ?>
            </div>
        </div>
    </div>
</div>


<script>
    window.onload = function() {
        var contentDiv = document.querySelector('.a_content');
        contentDiv.scrollTop = contentDiv.scrollHeight;
    };
</script>

<script>
$(document).ready(function() {
    var $articles = $(".a_content article");
    var totalComments = $articles.length;

    $articles.hide();

    if (totalComments <= 15) {
        $articles.show();
        $("#loadMore").hide();
    } else {
        $articles.slice(totalComments - 15).show();
        $("#loadMore").show();
    }

    $("#loadMore").click(function(e) {
        e.preventDefault();
        var visibleCount = $(".a_content article:visible").length;
        var nextToShow = $articles.slice(Math.max(0, totalComments - visibleCount - 15), totalComments - visibleCount);

        nextToShow.slideDown();

        if ($(".a_content article:hidden").length === 0) {
            $("#loadMore").fadeOut();
        }
    });
});
</script>