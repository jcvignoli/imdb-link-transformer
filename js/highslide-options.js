/* Settings for highslide */

hs.allowWidthReduction = true
hs.graphicsDir = '../wp-content/plugins/imdb-link-transformer/js/highslide/graphics/';
hs.showCredits = false;
hs.outlineType = 'custom';
hs.easing = 'linearTween';
hs.align = 'center';
hs.useBox = true;
hs.registerOverlay(
	{ html: '<div class=\"closebutton\" onclick=\"return hs.close(this)\" title=\"Close\"></div>',
	position: 'top right',
	useOnHtml: true, fade: 2 }
);

