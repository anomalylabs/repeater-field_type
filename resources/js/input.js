$(function () {
    var toggleCollapseItem = function ($item) {
        var $toggle = $item.find('[data-toggle="collapse"]');
        var $text = $toggle.find('span');

        $item
            .toggleClass('collapsed')
            .find('[data-toggle="collapse"] i')
            .toggleClass('fa-compress')
            .toggleClass('fa-expand');

        if ($toggle.find('i').hasClass('fa-compress')) {
            $text.text($toggle.data('collapse'));
        } else {
            $text.text($toggle.data('expand'));
        }
    };

    var $repeaters = $('[data-provides="anomaly.field_type.repeater"]:not([data-initialized])');

    $repeaters.each(function () {
        var $this = $(this); // This is necessary!!!

        $this.attr('data-initialized', '');

        var $wrapper = $this;
        var instance = $this.data('instance');
        var $items = $this.find('.repeater-item');
        var $add = $wrapper.find('.add-row[data-instance="' + instance + '"]');
        var cookie = 'repeater:' + $this.closest('.repeater-container').data('field_name');

        var collapsed = Cookies.getJSON(cookie);

        $items.each(function () {
            var $item = $(this);

            /**
             * Hide initial items.
             */
            if (typeof collapsed === 'undefined') {
                collapsed = {};
            }

            if (Boolean(collapsed[$items.index($item)]) === true) {
                toggleCollapseItem($item);
            }
        });

        $wrapper.on('click', '[data-toggle="collapse"]', function () {
            var $toggle = $(this);
            var $item = $toggle.closest('.repeater-item');

            toggleCollapseItem($item);

            $toggle
                .closest('.dropdown')
                .find('.dropdown-toggle')
                .trigger('click');

            if (typeof collapsed === 'undefined') {
                collapsed = {};
            }

            collapsed[$items.index($item)] = $item.hasClass('collapsed');

            Cookies.set(
                cookie,
                JSON.stringify(collapsed),
                { path: window.location.pathname }
            );

            return false;
        });

        $wrapper.on('click', '[data-toggle="collapseAll"]', function (e) {
            e.preventDefault();

            var $items = $wrapper.find('.repeater-item');

            if (!$items.hasClass('collapsed')) {
                $items.each(function () {
                    toggleCollapseItem($(this));
                });
            } else {
                $items.each(function () {
                    var $this = $(this);

                    if ($this.hasClass('collapsed')) {
                        toggleCollapseItem($(this));
                    };
                });
            }
        });

        $wrapper.indexCollapsed = function () {
            $wrapper
                .find('.repeater-list')
                .find('.repeater-item')
                .each(function (index) {
                    var $item = $(this);

                    if (typeof collapsed === 'undefined') {
                        collapsed = {};
                    }

                    collapsed[index] = $item.hasClass('collapsed');

                    Cookies.set(
                        cookie,
                        JSON.stringify(collapsed),
                        { path: window.location.pathname }
                    );
                });
        };

        $wrapper.sort = function () {
            var adjustment;

            $wrapper.find('> .repeater-list').sortable({
                handle: '.repeater-handle',
                placeholder: '<div class="placeholder"></div>',
                containerSelector: '.repeater-list',
                itemSelector: '.repeater-item',
                nested: false,

                onDragStart: function ($item, container, _super, event) {
                    $item.css({
                        height: $item.outerHeight(),
                        width: $item.outerWidth()
                    });

                    $item.addClass('dragged');

                    adjustment = {
                        left: container.rootGroup.pointer.left - $item.offset().left,
                        top: container.rootGroup.pointer.top - $item.offset().top
                    };

                    _super($item, container);
                },

                onDrag: function ($item, position) {
                    $item.css({
                        left: position.left - adjustment.left,
                        top: position.top - adjustment.top
                    });
                },

                afterMove: function ($placeholder) {
                    $placeholder
                        .closest('.repeater-list')
                        .find('.dragged')
                        .detach()
                        .insertBefore($placeholder);

                    $wrapper.indexCollapsed();
                },

                serialize: function ($parent, $children, parentIsContainer) {
                    var result = $.extend({}, $parent.data());

                    if (parentIsContainer) {
                        return [$children];
                    } else if ($children[0]) {
                        // This needs to return [0] for some reason..
                        result.children = $children[0];
                    }

                    delete result.subContainers;
                    delete result.sortable;

                    return result;
                },
            });
        };

        $wrapper.sort();

        $add.click(function (e) {
            e.preventDefault();

            var $this = $(this);
            var count = $wrapper.find('.repeater-item').length + 1;

            $wrapper
                .find('> .repeater-list')
                .append(
                    $('<div class="repeater-item"><div class="repeater-loading">' +
                        $this.data('loading') + '...</div></div>').load(
                        $this.attr('href') + '&instance=' + count, function () {
                            $wrapper.sort();
                            $wrapper.indexCollapsed();
                        }
                    )
                );
        });
    });
});
