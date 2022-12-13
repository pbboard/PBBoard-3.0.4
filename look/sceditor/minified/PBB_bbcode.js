function bbcode_wysiwyg(bbcode_tag){
var instance = PBBEditor;
instance.wysiwygEditorInsertText('['+bbcode_tag+']' + getSelection() , '[/'+bbcode_tag+']', true, true, true);
}
function bbcode_tags(bbcode_tag){
var instance = PBBEditor;
instance.insert('['+bbcode_tag+']' + getSelection() , '[/'+bbcode_tag+']', true, true, true);
}

	// add mention to users
	$.sceditor.command.set('mention', {
		_dropDown: function (editor, caller) {
			var $content;

			$content = $(
				'<div>' +
				'<div>' +
				'<label for="mention">' + editor._('mention-user:') + '</label> ' +
				'<span>' + editor._('name-user-mention') + '</span> ' +
				'<input type="text" id="mention" value="" />' +
				'</div>' +
				'<div>' +
				'<input type="hidden" id="des" />' +
				'</div>' +
				'<div><input type="button" class="button" value="' + editor._('Insert') + '" /></div>' +
				'</div>'
			);

			$content.find('.button').on('click', function (e) {
				var val = $content.find('#mention').val(),
					description = $content.find('#des').val();

				if (val) {
					// needed for IE to reset the last range
					$(editor).trigger('focus');

					if (!editor.getRangeHelper().selectedHtml() || description) {
						if (!description)
							description = val;

						editor.insert('[mention]' + description + '[/mention]');
					} else
						editor.execCommand('createlink', 'mention:' + val);
				}

				editor.closeDropDown(true);
				e.preventDefault();
			});

			editor.createDropDown(caller, 'insertmention', $content.get(0));
		},
		exec: function (caller) {
			$.sceditor.command.get('mention')._dropDown(this, caller);
		},
		txtExec: function (caller) {
			$.sceditor.command.get('mention')._dropDown(this, caller);
		},
	tooltip: "Mention"
	});


$.sceditor.command.set("codebrush", {
	exec: function(caller) {
		var	editor   = this,
		$content = $("<div />");
            var i= $(
			'<div>' +
				'<a class="sceditor-header-option" OnClick=bbcode_wysiwyg("' + ('code') + '") ">' +
				  '<b>CODE</b>' +
				'</a><div />'+
				'<a class="sceditor-header-option" OnClick=bbcode_wysiwyg("' + ('php') + '") ">' +
					'<b>PHP</b>' +
				'</a><div />'+
				'<div><a class="sceditor-header-option" OnClick=bbcode_wysiwyg("' + ('html') + '") ">' +
					'<b>Html</b>' +
				'</a><div />'+
				'<div><a class="sceditor-header-option" OnClick=bbcode_wysiwyg("' + ('js') + '") ">' +
					'<b>JavaScript</b>' +
				'</a><div />'+
				'<div><a class="sceditor-header-option" OnClick=bbcode_wysiwyg("' + ('css') + '") ">' +
					'<b>CSS</b>' +
				'</a><div />'+
				'<div><a class="sceditor-header-option" OnClick=bbcode_wysiwyg("' + ('sql') + '") ">' +
					'<b>SQL</b>' +
				'</a><div />'+
				'<div><a class="sceditor-header-option" OnClick=bbcode_wysiwyg("' + ('xml') + '") ">' +
					'<b>XML</b>' +
				'</a><div />'
			)
			.data('codebrushize', i)
			.click(function (e) {
				editor.closeDropDown(true);
				e.preventDefault();
			})
			.appendTo($content);

		editor.createDropDown(caller, "codebrush-picker", $content.get(0));
	},
txtExec: function(caller) {
		var	editor   = this,
		$content = $("<div />");
            var i= $(
			'<div>' +
				'<a class="sceditor-header-option" OnClick=bbcode_tags("' + ('code') + '") ">' +
					'<b>CODE</b>' +
				'</a><div />'+
				'<a class="sceditor-header-option" OnClick=bbcode_tags("' + ('php') + '") ">' +
					'<b>PHP</b>' +
				'</a><div />'+
				'<div><a class="sceditor-header-option" OnClick=bbcode_tags("' + ('html') + '") ">' +
					'<b>Html</b>' +
				'</a><div />'+
				'<div><a class="sceditor-header-option" OnClick=bbcode_tags("' + ('js') + '") ">' +
					'<b>JavaScript</b>' +
				'</a><div />'+
				'<div><a class="sceditor-header-option" OnClick=bbcode_tags("' + ('css') + '") ">' +
					'<b>CSS</b>' +
				'</a><div />'+
				'<div><a class="sceditor-header-option" OnClick=bbcode_tags("' + ('sql') + '") ">' +
					'<b>SQL</b>' +
				'</a><div />'+
				'<div><a class="sceditor-header-option" OnClick=bbcode_tags("' + ('xml') + '") ">' +
					'<b>XML</b>' +
				'</a><div />'
			)
			.data('codebrushize', i)
			.click(function (e) {
				editor.closeDropDown(true);
				e.preventDefault();
			})
			.appendTo($content);

		editor.createDropDown(caller, "codebrush-picker", $content.get(0));
	},
	tooltip: "Code"
});



 var pbbCmd = {
		align: ['left', 'center', 'right', 'justify'],
		fsStr: ['xx-small', 'x-small', 'small', 'medium', 'large', 'x-large', 'xx-large'],
		fSize: [9, 12, 15, 17, 23, 31],
		video: {
			'Dailymotion': {
				'match': /(dailymotion\.com\/video\/|dai\.ly\/)([^\/]+)/,
				'url': '//www.dailymotion.com/embed/video/',
				'html': '<iframe frameborder="0" width="480" height="270" src="{url}" data-pbb-vt="{type}" data-pbb-vsrc="{src}"></iframe>'
			},
			'Facebook': {
				'match': /facebook\.com\/(?:photo.php\?v=|video\/video.php\?v=|video\/embed\?video_id=|v\/?)(\d+)/,
				'url': 'https://www.facebook.com/video/embed?video_id=',
				'html': '<iframe src="{url}" width="625" height="350" frameborder="0" data-pbb-vt="{type}" data-pbb-vsrc="{src}"></iframe>'
			},
			'Tiktok': {
				'match': /tiktok\.com\/embed\/v2\/([^\/]+)/,
				'url': 'https://www.tiktok.com/embed/v2/',
				'html': '<iframe src="{url}" width="500" height="580" frameborder="0" scrolling="no" data-pbb-vt="{type}" data-pbb-vsrc="{src}"></iframe>'
			},
			'Instagram': {
				'match': /instagram\.com\/p\/([^\/]+)/,
				'url': 'http://www.instagram.com/p/',
				'html': '<iframe src="{url}/embed" width="440" height="510" frameborder="0" data-pbb-vt="{type}" data-pbb-vsrc="{src}"></iframe>'
			},
			'Vimeo': {
				'match': /vimeo\.com\/video\/([^\/]+)/,
				'url': '//player.vimeo.com/video/',
				'html': '<iframe src="{url}" width="500" height="281" frameborder="0" data-pbb-vt="{type}" data-pbb-vsrc="{src}"></iframe>'
			},
			'Youtube': {
				'match': /(?:v=|v\/|embed\/|youtu\.be\/)(.{11})/,
				'url': 'https://www.youtube.com/embed/',
				'html': '<iframe width="560" height="315" src="{url}" frameborder="0" data-pbb-vt="{type}" data-pbb-vsrc="{src}"></iframe>'
			},
			'Twitch': {
				'match': /twitch\.tv\/(?:[\w+_-]+)\/v\/(\d+)/,
				'url': '//player.twitch.tv/?video=v',
				'html': '<iframe src="{url}" frameborder="0" scrolling="no" height="378" width="620" data-pbb-vt="{type}" data-pbb-vsrc="{src}"></iframe>'
			}
		}
	};

 	// Add PBBoard video command
	$.sceditor.formats.bbcode.set('video', {
		allowsEmpty: true,
		allowedChildren: ['#', '#newline'],
		tags: {
			iframe: {
				'data-pbb-vt': null
			}
		},
		format: function ($element, content) {
			return '[video=' + $($element).data('pbb-vt') + ']' + $($element).data('pbb-vsrc') + '[/video]';
		},
		html: function (token, attrs, content) {
			var params = pbbCmd.video[Object.keys(pbbCmd.video).find(key => key.toLowerCase() === attrs.defaultattr)];
			var matches, url;
			var n = (attrs.defaultattr == 'dailymotion') ? 2 : 1;
			if (typeof params !== "undefined") {
				matches = content.match(params['match']);
				url = matches ? params['url'] + matches[n] : false;
			}
			if (url) {
				return params['html'].replace('{url}', url).replace('{src}', content).replace('{type}', attrs.defaultattr);
			}
			return $.sceditor.escapeEntities(token.val + content + (token.closing ? token.closing.val : ''));
		}
	});

	$.sceditor.command.set('video', {
		_dropDown: function (editor, caller) {
			var $content, videourl, videotype, videoOpts;

			$.each(pbbCmd.video, function (provider, data) {
				videoOpts += '<option class="toLowerCase" value="' + provider.toLowerCase() + '">' + editor._(provider) + '</option>';
				videourl += '<div id="' + provider.toLowerCase() + '">' + editor._(provider) + '</div>';
			});
			$content = $(
				'<div>' +
				'<div>' +
				'<label for="videotype">' + editor._('Video Type:') + '</label> ' +
				'<select id="videotype">' + videoOpts + '</select>' +
				'</div>' +
				'<div id="exampleurl" style="display:none;">' +
				'<label for="link">' + editor._('example url:') + '</label> ' +
				 '</div>' +
				'<div id="dailymotion" class="urlex" style="display:none;">https://www.dailymotion.com/video/x8ea724</div> ' +
				'<div id="facebook" class="urlex" style="display:none;">https://www.facebook.com/video/embed?video_id=644342003942740</div> ' +
				'<div id="tiktok" class="urlex" style="display:none;">https://www.tiktok.com/embed/v2/7076887934426795266</div> ' +
				'<div id="instagram" class="urlex" style="display:none;">http://www.instagram.com/p/BRZKS5GBZuJ</div> ' +
				'<div id="vimeo" class="urlex" style="display:none;">https://www.player.vimeo.com/video/1646265</div> ' +
				'<div id="youtube" class="urlex" style="display:none;">https://www.youtube.com/embed/A5zpNKMMHV4</div> ' +
				'<div id="twitch" style="display:none;"><div class="urlex">https://player.twitch.tv/?video=1621499513</div>'+ editor._('Twitch Channel Url:') +'<div class="urlex">https://player.twitch.tv/?channel=pubg_battlegrounds</div></div> ' +
				'<div>' +
				'<label for="link">' + editor._('Video URL:') + '</label> ' +
				'<input type="text" class="textbutton" id="videourl" dir="ltr" placeholder="http://" />' +
				'</div>' +
				'<div><input type="button" class="button" value="' + editor._('Insert') + '" /></div>' +
				'</div>'
			);

			$content.find('#videotype').on('click', function (e) {
				videourl = $content.find('#videourl').val();
				videotype = $content.find('#videotype').val();
				 $("#exampleurl").show();

				   if (videotype == 'dailymotion')
				   {
					$content.find("#dailymotion").slideToggle("slow");
					$("#facebook").hide();
					$("#tiktok").hide();
					$("#instagram").hide();
					$("#youtube").hide();
					$("#vimeo").hide();
					$("#twitch").hide();
					}
				   else if  (videotype == 'facebook')
				   {
					$content.find("#facebook").slideToggle("slow");
					$("#dailymotion").hide();
					$("#tiktok").hide();
					$("#instagram").hide();
					$("#youtube").hide();
					$("#vimeo").hide();
					$("#twitch").hide();
					}
				   else if  (videotype == 'tiktok')
				    {
					$content.find("#tiktok").slideToggle("slow");
					$("#dailymotion").hide();
					$("#facebook").hide();
					$("#instagram").hide();
					$("#youtube").hide();
					$("#vimeo").hide();
					$("#twitch").hide();
					}
					else if  (videotype == 'instagram')
				    {
					$content.find("#instagram").slideToggle("slow");
					$("#dailymotion").hide();
					$("#facebook").hide();
					$("#tiktok").hide();
					$("#youtube").hide();
					$("#vimeo").hide();
					$("#twitch").hide();
					}

					else if  (videotype == 'vimeo')
				    {
					$content.find("#vimeo").slideToggle("slow");
					$("#dailymotion").hide();
					$("#facebook").hide();
					$("#tiktok").hide();
					$("#instagram").hide();
					$("#youtube").hide();
					$("#twitch").hide();
					}
					else if  (videotype == 'youtube')
				    {
					$content.find("#youtube").slideToggle("slow");
					$("#dailymotion").hide();
					$("#facebook").hide();
					$("#tiktok").hide();
					$("#instagram").hide();
					$("#vimeo").hide();
					$("#twitch").hide();
					}
					else if  (videotype == 'twitch')
				    {
					$content.find("#twitch").slideToggle("slow");
					$("#dailymotion").hide();
					$("#facebook").hide();
					$("#tiktok").hide();
					$("#instagram").hide();
					$("#vimeo").hide();
					$("#youtube").hide();
					}

				});

			$content.find('.button').on('click', function (e) {
				videourl = $content.find('#videourl').val();
				videotype = $content.find('#videotype').val();

				if (videourl !== '' && videourl !== 'http://')
					editor.insert('[video=' + videotype + ']' + videourl + '[/video]');

				editor.closeDropDown(true);
				e.preventDefault();
			});

			editor.createDropDown(caller, 'insertvideo', $content.get(0));
		},
		exec: function (caller) {
			$.sceditor.command.get('video')._dropDown(this, caller);
		},
		txtExec: function (caller) {
			$.sceditor.command.get('video')._dropDown(this, caller);
		},
		tooltip: 'Insert a video'
	});

	// Update quote to support id and write_time
	$.sceditor.formats.bbcode.set('quote', {
		format: function (element, content) {
			var author = '',
				$elm = $(element),
				$cite = $elm.children('cite').first();

			if ($cite.length === 1 || $elm.data('author')) {
				author = $cite.text() || $elm.data('author');

				$elm.data('author', author);
				$cite.remove();

				content = this.elementToBbcode(element);
				author = '=' + author.replace(/(^\s+|\s+$)/g, '');

				$elm.prepend($cite);
			}

			if ($elm.data('id'))
				author += " id='" + $elm.data('id') + "'";

			if ($elm.data('write_time'))
				author += " write_time='" + $elm.data('write_time') + "'";

			return '[quote' + author + ']' + content + '[/quote]';
		},
		html: function (token, attrs, content) {
			var data = '';

			if (attrs.id)
				data += ' data-id="' + $.sceditor.escapeEntities(attrs.id) + '"';

			if (attrs.write_time)
				data += ' data-write_time="' + $.sceditor.escapeEntities(attrs.write_time) + '"';

			if (typeof attrs.defaultattr !== "undefined")
				content = '<cite>' + $.sceditor.escapeEntities(attrs.defaultattr).replace(/ /g, '&nbsp;') + '</cite>' + content;

			return '<blockquote' + data + '>' + content + '</blockquote>';
		},
		quoteType: function (val, name) {
			var quoteChar = val.indexOf('"') !== -1 ? "'" : '"';

			return quoteChar + val + quoteChar;
		},
		breakStart: true,
		breakEnd: true
	});

	if (partialmode) {
		$.sceditor.formats.bbcode.remove('code').remove('php').remove('quote').remove('video').remove('img').remove('mention');
		$.sceditor.command
			.set('quote', {
				exec: function () {
					this.insert('[quote]', '[/quote]');
				}
			});
	}





