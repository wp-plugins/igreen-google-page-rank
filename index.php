<?php

/*
Plugin Name: Igreen Google Page Rank Site Rank
Plugin URI: http://susheelonline.com
Description: Get your updated Google Page  Rank in widgets or integrate in theme using plugin API/ shortcode
Version: 1.0
Author: Susheel Kumar ,Ritu Kushwaha
Author URI: http://susheelonline.com
License: GPL2
*/

/* short code generating */


add_shortcode("GOOGLEPAGERANK","GooglePageRank");


 
 
 function  getGooglePageRankbySiteName($url)
 {
$PageRank = new PageRank();
echo  $PageRank->get_google_pagerank($url) ;
 }
 
 
  function   GooglePageRankbySiteName($url)
 {
 
  echo getGooglePageRankbySiteName($url);
 }
 
 
 
 
 function getGooglePageRank()
 {
	 
$PageRank = new PageRank();
echo  $PageRank->get_google_pagerank($_SERVER['HTTP_HOST']) ;
 }
 
 
 function GooglePageRank()
 {
	echo getGooglePageRank(); 
 }
 /* /4 API IS HERE */
  
 
class igreen_page_rank_widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'igreen_page_rank_widget', // Base ID
			'Igreen_Page_Rank_Widget', // Name
			array( 'description' => __( 'Igreen Google Page Rank  Widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		echo "Google Page Rank is " ;
		echo getGooglePageRank();
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Google Page Rank', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php 
	}

} // class Foo_Widget

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "igreen_page_rank_widget" );' ) );
		 
	
 

class PageRank {
 public function get_google_pagerank($url) {
 $query="http://toolbarqueries.google.com/tbr?client=navclient-auto&ch=".$this->CheckHash($this->HashURL($url)). "&features=Rank&q=info:".$url."&num=100&filter=0";
 $data=file_get_contents($query);
 $pos = strpos($data, "Rank_");
 if($pos === false){} else{
 $pagerank = substr($data, $pos + 9);
 return $pagerank;
 }
 }
 public function StrToNum($Str, $Check, $Magic)
 {
 $Int32Unit = 4294967296; // 2^32
 $length = strlen($Str);
 for ($i = 0; $i < $length; $i++) {
 $Check *= $Magic;
 if ($Check >= $Int32Unit) {
 $Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
 $Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
 }
 $Check += ord($Str{$i});
 }
 return $Check;
 }
 public function HashURL($String)
 {
 $Check1 = $this->StrToNum($String, 0x1505, 0x21);
 $Check2 = $this->StrToNum($String, 0, 0x1003F);
 $Check1 >>= 2;
 $Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
 $Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
 $Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);
 $T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
 $T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
 return ($T1 | $T2);
 }
 
 
 
 
 public function CheckHash($Hashnum)
 {
 $CheckByte = 0;
 $Flag = 0;
 $HashStr = sprintf('%u', $Hashnum) ;
 $length = strlen($HashStr);
 for ($i = $length - 1; $i >= 0; $i --) {
 $Re = $HashStr{$i};
 if (1 === ($Flag % 2)) {
 $Re += $Re;
 $Re = (int)($Re / 10) + ($Re % 10);
 }
 $CheckByte += $Re;
 $Flag ++;
 }
 $CheckByte %= 10;
 if (0 !== $CheckByte) {
 $CheckByte = 10 - $CheckByte;
 if (1 === ($Flag % 2) ) {
 if (1 === ($CheckByte % 2)) {
 $CheckByte += 9;
 }
 $CheckByte >>= 1;
 }
 }
 return '7'.$CheckByte.$HashStr;
 }
}





?>