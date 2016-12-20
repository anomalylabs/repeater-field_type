$(function () {

    var repeaters = $('[data-provides="anomaly.field_type.repeater"]');

    repeaters.each(function () {

        var wrapper = $(this);
        var items = $(this).find('.repeater-item');
        var cookie = 'repeater:' + $(this).closest('.repeater-container').data('field_name');

        var collapsed = Cookies.getJSON(cookie);

        items.each(function () {

            var item = $(this);
            var toggle = $(this).find('[data-toggle="collapse"]');

            /**
             * Hide initial items.
             */
            if (typeof collapsed == 'undefined') {
                collapsed = {};
            }

            if (collapsed[items.index(item)] == true) {
                item
                    .toggleClass('collapsed')
                    .find('[data-toggle="collapse"] i')
                    .toggleClass('fa-toggle-on')
                    .toggleClass('fa-toggle-off');
            }
        });

        wrapper.on('click', '[data-toggle="collapse"]', function () {

            var toggle = $(this);
            var item = toggle.closest('.repeater-item');

            item
                .toggleClass('collapsed')
                .find('[data-toggle="collapse"] i')
                .toggleClass('fa-toggle-on')
                .toggleClass('fa-toggle-off');

            if (typeof collapsed == 'undefined') {
                collapsed = {};
            }

            collapsed[items.index(item)] = item.hasClass('collapsed');

            Cookies.set(cookie, JSON.stringify(collapsed), {path: window.location.pathname});

            return false;
        });

        wrapper.indexCollapsed = function () {

            wrapper.find('.repeater-list').find('.repeater-item').each(function (index) {

                var item = $(this);

                if (typeof collapsed == 'undefined') {
                    collapsed = {};
                }

                collapsed[index] = item.hasClass('collapsed');

                Cookies.set(cookie, JSON.stringify(collapsed), {path: window.location.pathname});
            });
        };

        wrapper.sort = function () {
            wrapper.find('.repeater-list').sortable({
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

                    $placeholder.closest('.repeater-list').find('.dragged').detach().insertBefore($placeholder);

                    wrapper.indexCollapsed();
                },
                serialize: function ($parent, $children, parentIsContainer) {

                    var result = $.extend({}, $parent.data());

                    if (parentIsContainer)
                        return [$children];
                    else if ($children[0]) {
                        result.children = $children[0]; // This needs to return [0] for some reason..
                    }

                    delete result.subContainers;
                    delete result.sortable;

                    return result
                }
            });
        };

        wrapper.sort();

        wrapper.find('.add-row').click(function (e) {

            e.preventDefault();

            var count = wrapper.find('.repeater-item').length + 1;

            $(wrapper)
                .find('.repeater-list')
                .append($('<div class="repeater-item"><div class="repeater-loading">Loading...</div></div>').load($(this).attr('href') + '&instance=' + count, function () {
                    wrapper.sort();
                    wrapper.indexCollapsed();
                }));
        });
    });
});
