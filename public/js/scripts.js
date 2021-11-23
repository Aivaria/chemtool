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
    });
    $('.tagtoggler').click(function (){
        var $tag = $(this).data('id');
        var $tagClass = 'tag_'+$tag;
        var $tagClassActive = $tagClass+'_active';

        if ($(this).hasClass('hidden')){
            $(this).removeClass('hidden');
            $('.'+$tagClass).addClass($tagClassActive);
        }else{
            $(this).addClass('hidden');

            $('.'+$tagClass).removeClass($tagClassActive)
        }
    });
});