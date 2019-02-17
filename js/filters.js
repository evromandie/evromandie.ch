$(document).ready(function () {
    $('[data-filter]').each(function () {
        var $container = $(this);
        var filter = $container.data('filter');
        var options = [];

        $('[data-' + filter + ']').each(function () {
            var value = $(this).data(filter);

            if (value && options.indexOf(value) === -1) {
                options.push(value);
            }
        });

        options.sort();

        var $dropdown = $('<div class="dropdown-menu" />');

        function applyFilter(filter, option) {
            $('[data-' + filter + ']').each(function () {
                var $line = $(this);
                var value = $line.data(filter);

                $line.toggle((option === null) ? true : (value === option));
            });

            $('[data-filter] [data-filter-option]').removeClass('active', false);

            $dropdown.find('[data-filter-option]').each(function() {
                var $option = $(this);

                if ($option.data('filter-option') === option) {
                    $option.addClass('active');
                }
            });
        }

        $dropdown.append($('<div class="dropdown-item" />').text('Tout afficher').click(function () {
            applyFilter(filter, null);
        }));

        options.forEach(function (option) {
            $dropdown.append($('<div class="dropdown-item" />').text(option).click(function () {
                applyFilter(filter, option);
            }).attr('data-filter-option', option));
        });

        var $group = $('<div class="btn-group" />');

        $group.append($('<button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" />'));
        $group.append($dropdown);

        $container.append($group);
    })
});
