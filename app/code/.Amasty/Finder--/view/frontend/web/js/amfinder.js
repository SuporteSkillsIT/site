/**
 * @author Amasty Team
 * @copyright Copyright (c) 2010-2015 Amasty (http://www.amasty.com)
 * @package Amasty_Finder
 */
define([
    "jquery",
    "underscore",
    "jquery/ui"
], function ($, _) {
    $.widget('mage.amfinder', {
        options: {
            containerId: 'amfinder_Container',
            ajaxUrl: '',
            loadingText: '',
            isNeedLast: false,
            autoSubmit: false
        },
        selects: [],
        _isFirstLoad: false,

        _create: function () {
            this.selects = $('#' + this.options.containerId).find('select');
            this.selects.on('change', this, this._onChange);
        },

        _onChange: function (event) {
            var select = this, parentId = select.value, dropdownId = 0, self = event.data, selectToReload = null;
            /* should load next element's options only if current is not the last one */
            for (var i = 0; i < self.selects.length; i++) {
                if (self.selects[i].id == select.id && i != self.selects.length - 1) {
                    selectToReload = self.selects[i + 1];
                    break;
                }
            }

            self._clearAllBelow(select);
            if (selectToReload && parentId != 0) {
                self._loadDropDownValues(selectToReload, parentId);
            }
        },
        _loadDropDownValues: function (selectToReload, parentId) {
            var $selectToReload = $(selectToReload), dropdownId = $selectToReload.attr('data-dropdown-id');
            if (!dropdownId) {
                return;
            }
            $.getJSON(
                this.options.ajaxUrl,
                {dropdown_id: dropdownId, parent_id: parentId, use_saved_values: this._isFirstLoad},
                function (response) {
                    $selectToReload.empty();
                    var itemsFound = false;
                    var selectedValue = 0;
                    $.each(response, function (key, item) {
                        itemsFound = true;
                        $selectToReload.append("<option value='" + item.value + "'>" + item.label + "</option>");
                        if (item.selected) {
                            selectedValue = item.value;
                        }
                    });
                    if (itemsFound) {
                        $selectToReload.removeAttr("disabled");
                    }
                    if (selectedValue != 0) {
                        $selectToReload.val(selectedValue);
                        $selectToReload.change();
                    }
                }
            );
        },

        _clearAllBelow: function (select) {
            var startClearing = false;
            for (var i = 0; i < this.selects.length; i++) {
                if (startClearing) {
                    $(this.selects[i]).empty();
                    $(this.selects[i]).attr("disabled", "disabled");
                }
                if (this.selects[i].id == select.id) {
                    startClearing = true;
                    if (i == 0) {
                        select.isFirst = true;
                    }
                    if (i == this.selects.length - 1) {
                        select.isLast = true;
                    }
                }
            }
            var isLast = select.isLast && !this.options.isNeedLast;

            var hide = ((isLast && select.value > 0)
            || ((this.options.isNeedLast) && ((select.value > 0)
            || (!select.isFirst)))) ? false : true;

            var buttonsSelector = '#' + this.options.containerId + ' .amfinder-buttons';

            if (!hide && this.options.autoSubmit && select.isLast && !this._isFirstLoad) {
                $(buttonsSelector +' button.action').click();
            } else {
                if (hide) {
                    $(buttonsSelector).hide();
                } else {
                    $(buttonsSelector).show();
                }
            }
        }
    });

    return $.mage.amfinder;
});
