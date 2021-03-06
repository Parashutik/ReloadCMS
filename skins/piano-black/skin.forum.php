<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=$system->CONFIG_PATH['language']?>">
<head>
<?=rcms_show_element('meta')?>
<title><?=rcms_show_element('title')?></title>
<link rel="stylesheet" type="text/css" href="<?=CUR_SKIN_PATH?>forum.css" media="screen" />
<script type="text/javascript" src="<?=JSLIB?>jquery.js"></script>
<script type="text/javascript" src="<?=JSLIB?>jquery.easing.js"></script>
<script type="text/javascript" src="<?=JSLIB?>horinaja.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="<?=CSSLIB?>unitip.css" media="screen" />
<?php if ($system->CONFIG_PATH['pagination'] == 'Paginator3000') { ?>
<script type="text/javascript" src="<?=TOOLS?>paginator3000.js"></script>
<link rel="stylesheet" type="text/css" href="<?=TOOLS?>paginator3000.css" media="screen" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="<?=CSSLIB?>horinaja.css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?=TOOLS?>fancy/fancybox.css" media="screen" />
<script type="text/javascript" src="<?=JSLIB?>limit.js"></script>
<script type="text/javascript" src="<?=JSLIB?>unitip.js"></script>
<link rel="shortcut icon" href="favicon.ico" />
<!--[if lt IE 7]>
<script defer type="text/javascript" src="<?=CUR_SKIN_PATH?>js/iepngfix.js"></script>
<![endif]-->
</head>
<body>
	<div id="wrapper"><!-- Start wrapper -->
		<div id="contents"><!-- Start contents -->
			<div class="header-menu-wrapper clearfix">
				<div id="pngfix-right"></div>
				<div class="menu_wrapper">
					<div class="menu">
						<ul>
							<li><?=rcms_show_element('navigation', '<a href="{link}" target="{target}">{icon}&nbsp;{title}</a> ')?></li>
						</ul>
					</div>
				</div>
				<div id="pngfix-left"></div>
			</div>
			<div id="header"><!-- Start header -->
				<div id="logo">
					<a href="<?=htmlspecialchars('http://'.$_SERVER['HTTP_HOST'])?>"><?=rcms_show_element('title')?></a>
					<h1><?php if (!empty($system->CONFIG_PATH['slogan'])) { ?><?=$system->CONFIG_PATH['slogan']?><?php } ?></h1>
				</div>
				<a href="<?=htmlspecialchars('http://'.$_SERVER['HTTP_HOST'])?>/?module=rss.feeds.list" id="rss-feed">RSS FEEDS</a>
			</div><!-- End header -->
			<div id="middle-contents" class="clearfix"><!-- Start middle-contents-->
			<div id="left-col"><!-- Start left-col -->
				<div class="post">
					<div class="post-content">
						<?=rcms_show_element('main_point', $module.'@window')?>
					</div>
				</div>
			</div><!-- End left-col -->
			<div id="right-col"><!-- Start right-col -->
			<div id="side-bottom"></div>
				<div id="copy-box">
					<div id="copyrights">
						<p>Copyright&nbsp;&copy;&nbsp;2007-2010&nbsp;<br /><a href="<?=htmlspecialchars('http://'.$_SERVER['HTTP_HOST'])?>"><?=htmlspecialchars(''.$_SERVER['HTTP_HOST'])?></a>
						<br />Design&nbsp;by:&nbsp;<a href="http://www.mono-lab.net/" target="_blank" rel="external nofollow">mono-lab</a><br /><?=rcms_show_element('copyright')?><br /><br />
						<a href="http://validator.w3.org/check?uri=referer" target="_blank" rel="nofolow"><img src="<?=CUR_SKIN_PATH?>images/button-xhtml.gif" alt="Valid XHTML 1.0!" /></a>
						<a href="http://jigsaw.w3.org/css-validator/check/referer" target="_blank" rel="external nofollow"><img src="<?=CUR_SKIN_PATH?>images/button-css.gif" alt="Valid CSS!" /></a>
						<a href="http://php.net"><img src="<?=CUR_SKIN_PATH?>images/button-php.gif" alt="PHP powered" /></a>&nbsp;<img src="<?=CUR_SKIN_PATH?>images/button-rss.gif" alt="RSS Aggregation" />
						<br /><?php
						// Page gentime end
						$mtime = explode(' ', microtime());
						$totaltime = $mtime[0] + $mtime[1] - $starttime;
						print(__('Generation time:').round($totaltime,2));
						?><br /><br /><a href="./skins/depress.txt" rel="prettyPopin" title="������!"><img src="./skins/x.png" alt ="" height="48px" width="48px" /></a></p>
					</div>
				</div>
			</div><!-- End right-col -->
			</div><!-- End middle-contents -->
			<div id="footer"></div>
		</div><!-- End contents -->
	</div><!-- End wwrapper -->
	<div id="return_top">
		<a href="javascript:scroll(0,0);">&nbsp;</a>
	</div>
<script type="text/javascript" src="<?=TOOLS?>fancy/jquery.fancybox.js"></script>
<script type="text/javascript" src="<?=TOOLS?>pretty/js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="<?=TOOLS?>pretty/js/jquery.prettyPopin.js"></script>
<script type="text/javascript" src="<?=JSLIB?>jquery.rating.js"></script>
<script type="text/javascript">
		$(document).ready(function() {
		$("a.single_image").fancybox({
			'centerOnScroll' : true,
			'hideOnContentClick': true,
			'overlayShow' : true,
			'titleShow'   : false,
			'titlePosition'  : 'over',
			'transitionIn'	: 'elastic',
			'transitionOut'	: 'elastic',
		});
		$('#demo').Horinaja({
			capture:'demo',
			delai:0.3,
			duree:4,
			pagination:false});
		});
	</script>
</body>
</html>