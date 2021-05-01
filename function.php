// 関連記事
function jetpackme_more_related_posts( $options ) {
    $options['size'] = 6;
    return $options;
}
add_filter( 'jetpack_relatedposts_filter_options', 'jetpackme_more_related_posts' );

// Public Post Previewの有効期限
add_filter( `ppp_nonce_life`,`my_nonce_life`);
function my_nonce_life(){
    return 60*60*24*5;   //５日間(秒×分×時間×日数)
}

// 記事IDを指定して抜粋文を取得
function ltl_get_the_excerpt($post_id){
global $post;
$post_bu = $post;
$post = get_post($post_id);
setup_postdata($post_id);
$output = get_post_meta($post_id,'_aioseop_description',true);//AllinOneSEOから
$post = $post_bu;
return $output;
}

//ショートコード
function nlink_scode($atts) {
extract(shortcode_atts(array(
'url'=>"",
'title'=>"",
'excerpt'=>""
),$atts));

$id = url_to_postid($url);//URLから投稿IDを取得

$no_image = 'noimageに指定したい画像があればここにパス';//アイキャッチ画像がない場合の画像を指定

//タイトルを取得
if(empty($title)){
$title = esc_html(get_the_title($id));
}
//抜粋文を取得
if(empty($excerpt)){
$excerpt = esc_html(ltl_get_the_excerpt($id));
}

//アイキャッチ画像を取得
if(has_post_thumbnail($id)) {
$img = wp_get_attachment_image_src(get_post_thumbnail_id($id),'medium');
$img_tag = "<img src='" . $img[0] . "' alt='{$title}'/>";
}else{
$img_tag ='<img src="'.$no_image.'" alt="" width="'.$img_width.'" height="'.$img_height.'" />';
}

$nlink .='
<div class="blog-card">
<a href="'. $url .'">
<div class="blog-card-thumbnail">'. $img_tag .'</div>
<div class="blog-card-content">
<div class="blog-card-title">'. $title .' </div>
<div class="blog-card-excerpt">'. $excerpt .'</div>
</div>
<div class="clear"></div>
</a>
</div>';

return $nlink;
}

add_shortcode("nlink", "nlink_scode");
