
;(function ($) {
	"use strict";
	if(_.isUndefined(window.vc))
		return;
	
	var Shortcodes = vc.shortcodes;

    window.DTVCMovieCast = window.VcTabsView.extend({
        render:function () {
            window.DTVCMovieCast.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCMovieCast.__super__.ready.call(this, e);
            return this;
        },
        createAddTabButton:function () {
            var new_tab_button_id = (+new Date() + '-' + Math.floor(Math.random() * 11));
            this.$tabs.append('<div id="new-tab-' + new_tab_button_id + '" class="new_element_button"></div>');
            this.$add_button = $('<li class="add_tab_block"><a href="#new-tab-' + new_tab_button_id + '" class="add_tab" title="' + window.viem_vcL10n.add_item_title + '"></a></li>').appendTo(this.$tabs.find(".tabs_controls"));
        },
        addTab:function (e) {
            e.preventDefault();
            this.new_tab_adding = true;
            var tab_title = window.viem_vcL10n.item_title,
                tabs_count = this.$tabs.find('[data-element_type=dtsingle_movie_cast_item]').length,
                tab_id = (+new Date() + '-' + tabs_count + '-' + Math.floor(Math.random() * 11));
            vc.shortcodes.create({shortcode:'dtsingle_movie_cast_item', params:{title:tab_title + ' ' + (tabs_count + 1), tab_id:tab_id}, parent_id:this.model.id});
            return false;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                model_clone,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dtsingle_movie_cast_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dtsingle_movie_cast_item]').length + '-' + Math.floor(Math.random() * 11)});
            model_clone = Shortcodes.create({shortcode:model.get('shortcode'), id:vc_guid(), parent_id:parent_id, order:new_order, cloned:(model.get('shortcode') === 'dtsingle_movie_cast_item' ? false : true), cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.get('id'), true);
            }, this);
            return model_clone;
        }
    });
    window.DTVCMovieCastItem = window.VcTabView.extend({
        render:function () {
            window.DTVCMovieCastItem.__super__.render.call(this);
            return this;
        },
        ready:function (e) {
            window.DTVCMovieCastItem.__super__.ready.call(this, e);
            return this;
        },
        cloneModel:function (model, parent_id, save_order) {
            var shortcodes_to_resort = [],
                new_order = _.isBoolean(save_order) && save_order === true ? model.get('order') : parseFloat(model.get('order')) + vc.clone_index,
                new_params = _.extend({}, model.get('params'));
            if (model.get('shortcode') === 'dtsingle_movie_cast_item') _.extend(new_params, {tab_id:+new Date() + '-' + this.$tabs.find('[data-element_type=dtsingle_movie_cast_item]').length + '-' + Math.floor(Math.random() * 11)});
            var model_clone = Shortcodes.create({shortcode:model.get('shortcode'), parent_id:parent_id, order:new_order, cloned:true, cloned_from:model.toJSON(), params:new_params});
            _.each(Shortcodes.where({parent_id:model.id}), function (shortcode) {
                this.cloneModel(shortcode, model_clone.id, true);
            }, this);
            return model_clone;
        }
    });

})(window.jQuery);