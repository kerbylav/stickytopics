var lsStickyLoaderClass = new Class({

	Implements : Options,

	options : {
		classes_nav : {
			nav : 'block-nav',
			content : 'block-content',
			active : 'active'
		}
	},

	type : {
		topics : {
			url : aRouter['stickytopics_ajax'] + '/topics/',
			content : 'topics_content'
		},
		stickytopics : {
			url : aRouter['stickytopics_ajax'] + '/stickytopics/'
		}
	},

	initialize : function(options) {
		this.setOptions(options);
	},
	
	orderSticky : function(topic_id,type)
	{
		thisObj = this;

		var blockContent = $$('.' + 'stickytopics_content')[0].set('html', '');

		params = {
			security_ls_key : LIVESTREET_SECURITY_KEY
		};

		params['topic_id'] = topic_id;
		params['type'] = type;

		this.showStatus(blockContent);

		new Request.JSON({
			url : aRouter['stickytopics_ajax'] + '/ordersticky/',
			noCache : true,
			async: false,
			data : params,
			onSuccess : function(result) {
				thisObj.onLoad(result, blockContent);
			},
			onFailure : function() {
				msgErrorBox.alert('Error', 'Please try again later');
			}
		}).send();

		this.toggle('stickytopics');
	},

	deleteSticky : function(topic_id,blog_id)
	{
		thisObj = this;

		var blockContent = $$('.' + 'stickytopics_content')[0].set('html', '');

		params = {
			security_ls_key : LIVESTREET_SECURITY_KEY
		};

		params['topic_id'] = topic_id;
		params['blog_id'] = blog_id;

		this.showStatus(blockContent);

		new Request.JSON({
			url : aRouter['stickytopics_ajax'] + '/deletesticky/',
			noCache : true,
			async: false,
			data : params,
			onSuccess : function(result) {
				thisObj.onLoad(result, blockContent);
			},
			onFailure : function() {
				msgErrorBox.alert('Error', 'Please try again later');
			}
		}).send();

		this.toggle('stickytopics');
		this.toggle('topics');
	},

	toggleSticky : function(topic_id,type)
	{
		thisObj = this;

		var blockContent = $$('.' + 'stickytopics_content')[0].set('html', '');

		params = {
			security_ls_key : LIVESTREET_SECURITY_KEY
		};

		params['topic_id'] = topic_id;
		params['type'] = type;

		this.showStatus(blockContent);

		new Request.JSON({
			url : aRouter['stickytopics_ajax'] + '/togglesticky/',
			noCache : true,
			async: false,
			data : params,
			onSuccess : function(result) {
				thisObj.onLoad(result, blockContent);
			},
			onFailure : function() {
				msgErrorBox.alert('Error', 'Please try again later');
			}
		}).send();

		this.toggle('stickytopics');
	},

	addSticky : function(blog_id, topic_id) {
		thisObj = this;

		var blockContent = $$('.' + 'topics_content')[0].set('html', '');

		params = {
			security_ls_key : LIVESTREET_SECURITY_KEY
		};

		params['blog_id'] = blog_id;
		params['topic_id'] = topic_id;

		this.showStatus(blockContent);

		new Request.JSON({
			url : aRouter['stickytopics_ajax'] + '/addsticky/',
			noCache : true,
			async: false,
			data : params,
			onSuccess : function(result) {
				thisObj.onLoad(result, blockContent);
				msgNoticeBox.alert('Успешно', 'Запись добавлена');
			},
			onFailure : function() {
				msgErrorBox.alert('Error', 'Please try again later');
			}
		}).send();

		this.toggle('stickytopics');
		this.toggle('topics');
	},

	changeBlog : function() {
		this.toggle('stickytopics');
		var blockContent = $$('.' + 'topics_content')[0].set('html', '');
	},

	toggle : function(type, params) {
		if (!this.type[type]) {
			return false;
		}
		thisObj = this;

		if (!params) {
			params = {
				security_ls_key : LIVESTREET_SECURITY_KEY
			};
		} else {
			params['security_ls_key'] = LIVESTREET_SECURITY_KEY;
		}

		var blockContent = $$('.' + type + '_content')[0].set('html', '');
		if (type == 'topics') {
			var filter = $('topic_filter').value;
			var blog_id = $('blog_id').value;
			params['blog_id'] = blog_id;
			params['value'] = filter;
		}
		if (type == 'stickytopics') {
			var blog_id = $('blog_id').value;
			params['blog_id'] = blog_id;
		}
		this.showStatus(blockContent);

		new Request.JSON({
			url : this.type[type].url,
			noCache : true,
			async: false,
			data : params,
			onSuccess : function(result) {
				thisObj.onLoad(result, blockContent);
			},
			onFailure : function() {
				msgErrorBox.alert('Error', 'Please try again later');
			}
		}).send();
	},

	onLoad : function(result, blockContent) {
		blockContent.set('html', '');
		if (!result) {
			msgErrorBox.alert('Error', 'Please try again later');
		}
		if (result.bStateError) {
			// msgErrorBox.alert(result.sMsgTitle,result.sMsg);
		} else {
			blockContent.set('html', result.sText);
		}
	},

	showStatus : function(obj) {
		var newDiv = new Element('div');
		newDiv.setStyle('text-align', 'center');
		newDiv.set('html', '<img src="' + DIR_STATIC_SKIN
				+ '/images/loader.gif" >');

		newDiv.inject(obj);
	}
});
