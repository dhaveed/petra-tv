/* =========================================================
 * bootstrap-tabdrop.js 
 * http://www.eyecon.ro/bootstrap-tabdrop
 * =========================================================
 * Copyright 2012 Stefan Petre
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */
 
!function( $ ) {

	var WinReszier = (function(){
		var registered = [];
		var inited = false;
		var timer;
		var resize = function(ev) {
			clearTimeout(timer);
			timer = setTimeout(notify, 100);
		};
		var notify = function() {
			for(var i=0, cnt=registered.length; i<cnt; i++) {
				registered[i].apply();
			}
		};
		return {
			register: function(fn) {
				registered.push(fn);
				if (inited === false) {
					$(window).bind('resize', resize);
					inited = true;
				}
			},
			unregister: function(fn) {
				for(var i=0, cnt=registered.length; i<cnt; i++) {
					if (registered[i] == fn) {
						delete registered[i];
						break;
					}
				}
			}
		}
	}());

	var TabDrop = function(element, options) {
		this.element = $(element);
		this.dropdown = $('<li class="dropdown hide pull-right tabdrop"><a class="dropdown-toggle" data-toggle="dropdown" href="#">'+options.text+' <b class="caret"></b></a><ul class="dropdown-menu"></ul></li>')
							.prependTo(this.element);
		if (this.element.parent().is('.tabs-below')) {
			this.dropdown.addClass('dropup');
		}
		WinReszier.register($.proxy(this.layout, this));
		this.layout();
	};

	TabDrop.prototype = {
		constructor: TabDrop,

		layout: function() {
			var collection = [];
			this.dropdown.removeClass('hide');
			this.element.append(this.dropdown.find('li'));
			console.log(this.element)
			var self = this,
				sumwith = 0,
				title = this.element.find('.nav-tab-title'),
				items =this.element.find('>li').not('.tabdrop').not(title);
			items.each(function(){
					sumwith += $(this).outerWidth();
				});
			if(sumwith > $(self.element).outerWidth() - (title.outerWidth() +  self.element.find('.tabdrop').outerWidth())){
				for ( var i = items.length - 1; i >= 0; i-- ) {
					var _item_i = items[i];
					
					if(sumwith - $(_item_i).outerWidth() < $(self.element).outerWidth() - (title.outerWidth() +  self.element.find('.tabdrop').outerWidth())){
						collection.push(_item_i);
						break;
					}else{
						collection.push(_item_i);
						sumwith = sumwith -  $(_item_i).outerWidth();
					}
				}
			}
			if (collection.length > 0) {
				collection = $(collection.reverse());
				this.dropdown
					.find('ul')
					.empty()
					.append(collection);
				if (this.dropdown.find('.active').length == 1) {
					this.dropdown.addClass('active');
				} else {
					this.dropdown.removeClass('active');
				}
			} else {
				this.dropdown.addClass('hide');
			}
		}
	}

	$.fn.dhtabdrop = function ( option ) {
		return this.each(function () {
			var $this = $(this),
				data = $this.data('dhtabdrop'),
				options = typeof option === 'object' && option;
			if (!data)  {
				$this.data('dhtabdrop', (data = new TabDrop(this, $.extend({}, $.fn.dhtabdrop.defaults,options))));
			}
			if (typeof option == 'string') {
				data[option]();
			}
		})
	};

	$.fn.dhtabdrop.defaults = {
		text: '<i class="icon-align-justify"></i>'
	};

	$.fn.dhtabdrop.Constructor = TabDrop;

}( window.jQuery );