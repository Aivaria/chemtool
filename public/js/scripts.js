$(function()
{

    $('.chemical .header').click(function () {
        var $element = $(this).parent();
        if ($element.hasClass('closed')) {
            $element.removeClass('closed').addClass('open');
        } else {
            $element.removeClass('open').addClass('closed');
        }
    });
    $('a[data-id]', '.chemical').click(function () {
        var $content = $('#chemical_' + $(this).data('id'));

        if ($content.hasClass('closed')) {
            $content.removeClass('closed').addClass('open');
        }
    })
});