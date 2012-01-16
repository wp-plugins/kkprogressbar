(function() {
	tinymce.create('tinymce.plugins.kkpb', {

		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
			ed.addCommand('mceKKPB', function() {
				ed.windowManager.open({
					file : url + '/tinyMCE-shortcodes.php',
					width : 800,
					height : 400,
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});
			// Register example button
			ed.addButton('kkpb', {
				title : 'KKProgressbar Shortcodes',
				cmd : 'mceKKPB',
				image : url + '/kkpb-ico.jpg'
			});

			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('kkpb', n.nodeName == 'IMG');
			});
		},

		createControl : function(n, cm) {
			return null;
		},

		getInfo : function() {
			return {
					longname  : 'KKProgressbar Shortcodes',
					author 	  : 'Krzysztof Furtak',
					authorurl : 'http://krzysztof-furtak.pl',
					infourl   : 'http://krzysztof-furtak.pl',
					version   : "1.0"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('kkpb', tinymce.plugins.kkpb);
})();


