<?php
/**************************************************************************
Plugin Name: Theme-Selector
Plugin URI: http://www.widgilabs.com
Description: Theme selector enables users to preview and activate themes through an interface similar to tumblr. A new button is available on the admin bar and it works as a shortcut to this new theme selector interface.
Version: 1.0
Author: WidgiLabs
Author URI: http://www.widgilabs.com

**************************************************************************/


function add_theme_selector( $wp_admin_bar ) {
	
	if (is_user_logged_in() && (!isset($_REQUEST['template']) || $_REQUEST['template']=='')) 
		$wp_admin_bar->add_menu( array( 
		'title' => __( 'Change Theme', 'direitoamoradia' ) ,
		'id' => 'change_theme', 
		'href' => get_bloginfo('url').'/?customize=true' )
	);	
	
	
}
add_action( 'admin_bar_menu', 'add_theme_selector' );


	
function ts_init()
{
	if (isset($_REQUEST['customize']) && $_REQUEST['customize'] == true)
	{
		show_theme_selector_form(); die;
	}
}

add_action( 'init', 'ts_init' );

function theme_selector_css() {
	?>
	<style type="text/css">
		
		.container{ width:100%; height:100%;}
		
	</style>
	<?php 
	
}

add_action( 'wp_head', 'theme_selector_css' );

function show_theme_selector_form(){
	?>
	
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
	
		<style type="text/css">	
			
				html, body{
					width:100%;
					height:100%;
					margin:0;
					padding:0;
					overflow:hidden;
				}
				
			div {display:block;}		
			
			a, a:visited{color:#C1C9D1; text-decoration:none;}
			a:hover{text-decoration:underline;}
			
			.container{ 
				width:100%; 
				height:100%;}	
			
			.theme_selector{
				width:300px;
				position:absolute;
				float:left;
				top:0;
				left:0;
				bottom:0;
				overflow:hidden;
				background-color:#464646;
			}
			
			#preview_theme{
				position:absolute;
				left:300px;
				right:0;
				top:0;
				bottom:0;
				background-color:withe;
				z-index:1000;
			}
			
			/*Buttons*/
					
			.button {
				color: #606060;
				border: solid 1px #e2e2e2;
				background: #fff;
				background: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#ededed));
				background: -moz-linear-gradient(top,  #fff,  #ededed);
				filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#ededed');
			}
			.button:hover {
				background: #ededed;
				background: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#dcdcdc));
				background: -moz-linear-gradient(top,  #fff,  #dcdcdc);
				filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#dcdcdc');
			}
			.button:active {
				color: #999;
				background: -webkit-gradient(linear, left top, left bottom, from(#ededed), to(#fff));
				background: -moz-linear-gradient(top,  #ededed,  #fff);
				filter:  progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#ffffff');
			}		
					
			.buttons{
					height: 30px;
					padding: 7px 0px 24px 6px;
					border-bottom: 1px solid #4E545B;		
			}	
			
			.button-close{
					height: 30px;
					padding: 7px 24px 6px !important;
					border-bottom: 1px solid #4E545B;		
			}		
				
				
			.button {
			display: inline-block;
			outline: none;
			cursor: pointer;
			text-align: center;
			text-decoration: none;
			font: 12px sans-serif, Arial, Helvetica;
			padding: 5px 10px;
		/*	text-shadow: 0 1px 1px rgba(0,0,0,.3);*/
			-webkit-border-radius: .3em; 
			-moz-border-radius: .3em;
			border-radius: .3em;
			-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.2);
			-moz-box-shadow: 0 1px 2px rgba(0,0,0,.2);
			box-shadow: 0 1px 2px rgba(0,0,0,.2);
			}
		
			.button:hover {
			text-decoration: none;
			}
		
			.button:active {
			position: relative;
			top: 1px;
			}	
				
				
			/*End Buttons*/
			
			.themes_list{
				bottom: 0px;
				position: absolute;
				bottom: 0;
				top: 35px;
				width: 286px;
				margin-top: 23px;
				}		
				
			.themes_list_content{
				position: absolute;
				top: 0px;
				bottom: 0px;
				width: 250px;
				padding-right: 15px;
				padding-left: 23px;
				overflow-y: auto;
				overflow-x: hidden;
			}	
			
			
			.theme_title{
				text-align: left; color: white;
				font-size: 14px;
				line-height: 18px;
				font-weight: bold;
				top: -8px;
				width: 70%;
				position: relative;
			}
			
			.by{
				font-size: 12px;
				font-weight: bold;
				color: #C1C9D1;
			}
				
			
			.theme_selected{
				position:absolute;
				width:250px;
				height:166px;
				z-index:0;
				background-image:url('/wp-content/plugins/theme-selector/images/thumbnail_selected.png'); 		
			}		
			
			.thumb_img{
				-moz-border-radius: 4px;
				border-radius: 4px;
			}
					
		</style>
		
		<script type='text/javascript' src='<?php echo get_bloginfo('url');?>/wp-includes/js/jquery/jquery.js?ver=1.6.1'></script>
		<script type="text/javascript">
	

			jQuery(document).ready( function($) {


				//when we want to preview other themes and click on image for selection
				jQuery('.theme_thumbnail').click(function(){
				var bloginfo = jQuery('#bloginfo');
				var fcontainer = jQuery('.fcontainer');

				var template= this.id;
				
				var new_src = bloginfo.val()+'/?preview=1&template='+ template + '&stylesheet=' +template;
		
				fcontainer.attr("src",new_src);		
				
				//fcontainer.load();		

			   var selected  = jQuery('.'+template);
			   var previous = jQuery('#previous');

				if(previous.val() != template)					
				{
						var val = previous.val();
						
						
						var teste  = jQuery('.'+val);
						
						teste.removeClass('theme_selected');
					
				}	 
			  selected.addClass('theme_selected');
			  previous.val(template);
			 
				
					
					});

				});

			
		</script>
		
		
		
		<?php 
		// checking if we need to activate theme 
		
		if($_REQUEST['activate'])
		{
			$newtheme = $_REQUEST['activate'];
			
			switch_theme($newtheme, $newtheme);
			
		}
		
		// theme to activate
		?>
		
		
		<div class="container">
		
		
		<div class="theme_selector">
		
			<?php 
			$active_theme = get_theme(get_current_theme());
			$active_theme = $active_theme['Template'];
			?>
		
			<div class=" button-close">
				<div style="float: right;">
				
				<form method="link" action="<?php echo get_bloginfo('url');?>">
					<input class="button" id="themes_cancel_button" type="submit" value="<?php _e('Close','themeselector');?>">
				</form>
				
					
				</div>
			</div>
			<div class="themes_list">
			
			<div class="themes_list_content">
			
			<input type="hidden" name="previous" id="previous" value="<?php echo $active_theme?>"/>
			<input type="hidden" name="bloginfo" id="bloginfo" value="<?php echo get_bloginfo('url');?>"/>
					<?php 
						$avaliable_themes  = get_themes();
						
						foreach ($avaliable_themes as $tname=>$theme)
						{
							//print_r($theme);
							$screenshot =  $theme['Screenshot'];
							$template = $theme['Template'];
							$stylesheet = $theme['Stylesheet'];
							
							$preview_theme = get_bloginfo('url')."/wp-content/themes/".$template.'/'.$screenshot;
							
								$theme_selected = '';
							
								if($active_theme==$template)
									$theme_selected = 'theme_selected';
									
								$siteurl =  get_bloginfo('url');	
								$siteurl = $siteurl.'/?customize=true&activate='.$template;
							?>
								<div class="theme_thumbnail" id="<?php echo $template?>" >
								<div class="<?php echo $template; echo ' '.$theme_selected; ?>"></div>
									<img class="thumb_img" width="250" height="166" src="<?php echo $preview_theme;?>"/>	
								</div>
								
								<div class="buttons" style="float: right; border-bottom:none;">
									<input class="button" id="use_theme_button" type="button" value="<?php _e('Activate','themeselector');?>" ONCLICK="window.location.href='<?php echo $siteurl;?>'">
								</div>
							
							<input type="hidden" name="template" id="template" value="<?php echo $template; ?>"/>
							

							<p class="theme_title"><?php echo $theme['Title']?> <br/><span class="by"><?php _e('by ');?><a href="<?php $theme['Author'];?>"><?php echo $theme['Author Name'];?></a></span></p>
							<?php 					
						}
					?>
				
				</div><!-- end theme list content -->
			
			</div> <!-- end themes list -->
		</div> <!-- end theme selector -->
			
		<div id="preview_theme">		
			<iframe class="fcontainer" width="100%" height="100%" frameborder="0" scrolling="auto" src="<?php echo get_bloginfo('url');?>/?preview=1&template=<?php echo $active_theme;?>&stylesheet=<?php echo $active_theme;?>"></iframe>
		</div>
		
		</div> <!-- end div container  -->
	<?php 
}



?>