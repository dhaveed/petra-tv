function pricing_table_feature_remove(element){
	var $this = jQuery(element);
	$this.closest('tr').remove();
	return false;
}
function pricing_table_feature_add(element){
	var $this = jQuery(element);
	var option_list = $this.closest('.pricing-table-feature-list'),
		option_table = option_list.find('table tbody');
	var tmpl = jQuery(dtvcL10n.pricing_table_feature_tmpl);
	option_table.append(tmpl);
	return false;
}
;(function ($) {
	"use strict";
	if(_.isUndefined(window.vc))
		return;
	
	vc.edit_form_callbacks.push(function() {
		var model = this.$el;
		var pricing_table_feature = model.find('.pricing-table-feature-list');
		if(pricing_table_feature.length){
			var features = [];
			pricing_table_feature.find('table tbody tr').each(function(){
				var $this = $(this);
				var feature = {};
				feature['content'] = $this.find('#content').val();
				features.push(feature);
			});
			if(_.isEmpty(features)){
				this.params.features='';
			}else{
				var features_json = JSON.stringify(features);
				this.params.features = base64_encode(features_json);
			}
		}
	});
	
	var Shortcodes = vc.shortcodes;
	window.DTVCBackendTabsView = window.VcBackendTtaTabsView.extend({
		addSection: function(prepend) {
            var newTabTitle, params, shortcode;
            return newTabTitle = this.defaultSectionTitle, params = {
                shortcode: "dt_tab",
                params: {
                    title: newTabTitle
                },
                parent_id: this.model.get("id"),
                order: _.isBoolean(prepend) && prepend ? vc.add_element_block_view.getFirstPositionIndex() : vc.shortcodes.getNextOrder(),
                prepend: prepend
            }, shortcode = vc.shortcodes.create(params)
        }
	});
	window.DTVCTestimonial = window.VcTabsView.extend({
		render:function () {
            window.DTVCTestimonial.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCTestimonial.__super__.ready.call(this, e);
            return this;
        },
        createAddTabButton:function () {
            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
            this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.dtvcL10n.add_item_title + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
        },
        changedContent: function(view) {
            var params = view.model.get("params");
            if (this.$tabs.hasClass("ui-tabs") || (this.$tabs.tabs({
                    select: function(event, ui) {
                        return !$(ui.tab).hasClass("add_tab")
                    }
                }), this.$tabs.find(".ui-tabs-nav").prependTo(this.$tabs), vc_user_access().shortcodeAll("vc_tab") && this.$tabs.find(".ui-tabs-nav").sortable({
                    update: this.stopSorting,
                    items: "> li:not(.add_tab_block)"
                })), !0 === view.model.get("cloned")) {
                var $tab_controls = (view.model.get("cloned_from"), $(".tabs_controls > .add_tab_block", this.$content)),
                    $new_tab = $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertBefore($tab_controls);
                this.$tabs.tabs("refresh"), this.$tabs.tabs("option", "active", $new_tab.index())
            } else $("<li><a href='#tab-" + params.tab_id + "'>" + params.title + "</a></li>").insertBefore(this.$add_button), this.$tabs.tabs("refresh"), this.$tabs.tabs("option", "active", this.new_tab_adding ? $(".ui-tabs-nav li", this.$content).length - 2 : 0);
            this.new_tab_adding = !1
        },
        addTab:function (e) {
            e.preventDefault();
            this.new_tab_adding = true;
            var tab_title = window.dtvcL10n.item_title,
                tabs_count = this.$tabs.find('[data-element_type=dt_testimonial_item]').length,
                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
            vc.shortcodes.create({shortcode:'dt_testimonial_item', params:{title:tab_title + ' ' + (tabs_count + 1), tab_id:tab_id}, parent_id:this.model.id});
            return false;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dt_testimonial_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dt_testimonial_item]').length + '-' + Math.floor(Math.random() * 11)});
            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'dt_testimonial_item' ? false : true), cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.get('id'), true);
            }, this);
            return model_clone;
        }
	});
	window.DTVCTestimonialItem = window.VcTabView.extend({
		render:function () {
            window.DTVCTestimonialItem.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCTestimonialItem.__super__.ready.call(this, e);
            return this;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dt_testimonial_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dt_testimonial_item]').length + '-' + Math.floor(Math.random() * 11)});
            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.id, true);
            }, this);
            return model_clone;
        }
	});
	
	window.DTVCPricingTable = window.VcTabsView.extend({
		render:function () {
            window.DTVCPricingTable.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCPricingTable.__super__.ready.call(this, e);
            return this;
        },
        createAddTabButton:function () {
            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
            this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.dtvcL10n.add_item_title + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
        },
        addTab:function (e) {
            e.preventDefault();
            this.new_tab_adding = true;
            var tab_title = window.dtvcL10n.item_title,
                tabs_count = this.$tabs.find('[data-element_type=dt_pricing_table_item]').length,
                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
            if(tabs_count  >= 5 ){
            	alert(window.dtvcL10n.pricing_table_max_item_msg);
            	return false;
            }
            vc.shortcodes.create({shortcode:'dt_pricing_table_item', params:{title:tab_title + ' ' + (tabs_count + 1), tab_id:tab_id}, parent_id:this.model.id});
            return false;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dt_pricing_table_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dt_pricing_table_item]').length + '-' + Math.floor(Math.random() * 11)});
            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'dt_pricing_table_item' ? false : true), cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.get('id'), true);
            }, this);
            return model_clone;
        }
	});
	window.DTVCPricingTableItem = window.VcTabView.extend({
		render:function () {
            window.DTVCPricingTableItem.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCPricingTableItem.__super__.ready.call(this, e);
            return this;
        },
        cloneModel:function (model, parent_id, save_order) {
        	if(this.$tabs.find('[data-element_type=dt_pricing_table_item]').length >= 5){
        		alert(window.dtvcL10n.pricing_table_max_item_msg);
            	return false;
        	}
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dt_pricing_table_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dt_pricing_table_item]').length + '-' + Math.floor(Math.random() * 11)});
            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.id, true);
            }, this);
            return model_clone;
        }
	});

    window.DTVCFaq = window.VcTabsView.extend({
        render:function () {
            window.DTVCFaq.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCFaq.__super__.ready.call(this, e);
            return this;
        },
        createAddTabButton:function () {
            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
            this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.dtvcL10n.add_item_title + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
        },
        addTab:function (e) {
            e.preventDefault();
            this.new_tab_adding = true;
            var tab_title = window.dtvcL10n.item_title,
                tabs_count = this.$tabs.find('[data-element_type=dt_faq_item]').length,
                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
            vc.shortcodes.create({shortcode:'dt_faq_item', params:{title:tab_title + ' ' + (tabs_count + 1), tab_id:tab_id}, parent_id:this.model.id});
            return false;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dt_faq_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dt_faq_item]').length + '-' + Math.floor(Math.random() * 11)});
            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'dt_faq_item' ? false : true), cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.get('id'), true);
            }, this);
            return model_clone;
        }
    });
    window.DTVCFaqItem = window.VcTabView.extend({
        render:function () {
            window.DTVCFaqItem.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCFaqItem.__super__.ready.call(this, e);
            return this;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dt_faq_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dt_faq_item]').length + '-' + Math.floor(Math.random() * 11)});
            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.id, true);
            }, this);
            return model_clone;
        }
    });

    window.DTVCBanerSlider = window.VcTabsView.extend({
        render:function () {
            window.DTVCBanerSlider.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCBanerSlider.__super__.ready.call(this, e);
            return this;
        },
        createAddTabButton:function () {
            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
            this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.dtvcL10n.add_item_title + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
        },
        addTab:function (e) {
            e.preventDefault();
            this.new_tab_adding = true;
            var tab_title = window.dtvcL10n.item_title,
                tabs_count = this.$tabs.find('[data-element_type=dtsingle_banner_slider_item]').length,
                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
            vc.shortcodes.create({shortcode:'dtsingle_banner_slider_item', params:{title:tab_title + ' ' + (tabs_count + 1), tab_id:tab_id}, parent_id:this.model.id});
            return false;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dtsingle_banner_slider_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dtsingle_banner_slider_item]').length + '-' + Math.floor(Math.random() * 11)});
            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'dtsingle_banner_slider_item' ? false : true), cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.get('id'), true);
            }, this);
            return model_clone;
        }
    });
    window.DTVCBanerSliderItem = window.VcTabView.extend({
        render:function () {
            window.DTVCBanerSliderItem.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCBanerSliderItem.__super__.ready.call(this, e);
            return this;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dtsingle_banner_slider_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dtsingle_banner_slider_item]').length + '-' + Math.floor(Math.random() * 11)});
            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.id, true);
            }, this);
            return model_clone;
        }
    });
    
    window.DTVCVideoSlider = window.VcTabsView.extend({
        render:function () {
            window.DTVCVideoSlider.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCVideoSlider.__super__.ready.call(this, e);
            return this;
        },
        createAddTabButton:function () {
            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
            this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.dtvcL10n.add_item_title + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
        },
        addTab:function (e) {
            e.preventDefault();
            this.new_tab_adding = true;
            var tab_title = window.dtvcL10n.item_title,
                tabs_count = this.$tabs.find('[data-element_type=dtsingle_video_slider_item]').length,
                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
            vc.shortcodes.create({shortcode:'dtsingle_video_slider_item', params:{title:tab_title + ' ' + (tabs_count + 1), tab_id:tab_id}, parent_id:this.model.id});
            return false;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dtsingle_video_slider_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dtsingle_video_slider_item]').length + '-' + Math.floor(Math.random() * 11)});
            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'dtsingle_video_slider_item' ? false : true), cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.get('id'), true);
            }, this);
            return model_clone;
        }
    });
    window.DTVCVideoSliderItem = window.VcTabView.extend({
        render:function () {
            window.DTVCVideoSliderItem.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCVideoSliderItem.__super__.ready.call(this, e);
            return this;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dtsingle_video_slider_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dtsingle_video_slider_item]').length + '-' + Math.floor(Math.random() * 11)});
            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.id, true);
            }, this);
            return model_clone;
        }
    });

    window.DTVCOurBrand = window.VcTabsView.extend({
        render:function () {
            window.DTVCOurBrand.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCOurBrand.__super__.ready.call(this, e);
            return this;
        },
        createAddTabButton:function () {
            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
            this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.dtvcL10n.add_item_title + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
        },
        addTab:function (e) {
            e.preventDefault();
            this.new_tab_adding = true;
            var tab_title = window.dtvcL10n.item_title,
                tabs_count = this.$tabs.find('[data-element_type=dtsingle_our_brand_item]').length,
                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
            vc.shortcodes.create({shortcode:'dtsingle_our_brand_item', params:{title:tab_title + ' ' + (tabs_count + 1), tab_id:tab_id}, parent_id:this.model.id});
            return false;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dtsingle_our_brand_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dtsingle_our_brand_item]').length + '-' + Math.floor(Math.random() * 11)});
            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'dtsingle_our_brand_item' ? false : true), cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.get('id'), true);
            }, this);
            return model_clone;
        }
    });
    window.DTVCOurBrandItem = window.VcTabView.extend({
        render:function () {
            window.DTVCOurBrandItem.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCOurBrandItem.__super__.ready.call(this, e);
            return this;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dtsingle_our_brand_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dtsingle_our_brand_item]').length + '-' + Math.floor(Math.random() * 11)});
            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.id, true);
            }, this);
            return model_clone;
        }
    });

    window.DTVCOurTeam = window.VcTabsView.extend({
        render:function () {
            window.DTVCOurTeam.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCOurTeam.__super__.ready.call(this, e);
            return this;
        },
        createAddTabButton:function () {
            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
            this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.dtvcL10n.add_item_title + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
        },
        addTab:function (e) {
            e.preventDefault();
            this.new_tab_adding = true;
            var tab_title = window.dtvcL10n.item_title,
                tabs_count = this.$tabs.find('[data-element_type=dtsingle_our_team_item]').length,
                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
            vc.shortcodes.create({shortcode:'dtsingle_our_team_item', params:{title:tab_title + ' ' + (tabs_count + 1), tab_id:tab_id}, parent_id:this.model.id});
            return false;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dtsingle_our_team_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dtsingle_our_team_item]').length + '-' + Math.floor(Math.random() * 11)});
            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'dtsingle_our_team_item' ? false : true), cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.get('id'), true);
            }, this);
            return model_clone;
        }
    });
    window.DTVCOurTeamItem = window.VcTabView.extend({
        render:function () {
            window.DTVCOurTeamItem.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCOurTeamItem.__super__.ready.call(this, e);
            return this;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dtsingle_our_team_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dtsingle_our_team_item]').length + '-' + Math.floor(Math.random() * 11)});
            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.id, true);
            }, this);
            return model_clone;
        }
    });

})(window.jQuery);