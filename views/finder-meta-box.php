<?php 

global $post; 
global $wlPlaces;
		
$options = $wlPlaces->getOptions();
$custom_style=null;
if(class_exists('WelocallyPlacesCustomize' ) && isset($options[ 'map_custom_style' ])  && $options[ 'map_custom_style' ]!=''){
	$custom_style = stripslashes($options[ 'map_custom_style' ]);
}

$marker_image_path = WP_PLUGIN_URL.'/welocally-places/resources/images/marker_all_base.png' ;
if(class_exists('WelocallyPlacesCustomize' ) && isset($options[ 'map_default_marker' ])  && $options[ 'map_default_marker' ]!=''){
	$marker_image_path = $options[ 'map_default_marker' ];
}
 
$endpoint = 'https://api.welocally.com';
if(isset($options[ 'api_endpoint' ]) && $options[ 'api_endpoint' ] !=''){
	$endpoint = $options[ 'api_endpoint' ];
} 
 
?>


<script type="text/javascript">
jQuery(document).ready(function(jQuery) {	

});
</script>
<style type="text/css">
	
	
	
</style>
<body>
	<div>
		<div id="wl-place-finder" class="input-section action" style="display:inline-block" >
			<script type="text/javascript">
			
				var placeSelected = new WELOCALLY_PlaceWidget({}).init();
			    var cfg = { 
						id:'finder_1',
						<?php if(isset($options['default_search_addr'])):?> defaultLocation:<?php echo('\''.$options['default_search_addr'].'\''.','); endif;?>		
						showLetters: true,
						zoom:4, 
						imagePath:'<?php echo($marker_image_path); ?>',
		    			endpoint:'<?php echo($endpoint); ?>',
		    			<?php if(isset($custom_style)):?> styles:<?php echo($custom_style.','); endif;?>
				    	showSelection: true,
				    	observers:[placeSelected],				
			    };
			    
			    var placesFinder = 
					  new WELOCALLY_PlaceFinderWidget(cfg)
				  		.init();
		  		
		  		//now register the display for the place
			    placeSelected.setWrapper(cfg, jQuery(placesFinder.getSelectedSection()));	
			
	
		     </script>		
		</div>		
	</div>
</body>