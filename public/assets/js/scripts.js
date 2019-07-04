$(document).ready(function(){
    $('.alert[data-auto-dismiss]').each(function(index, element){
        const $element = $(element),
            timeout = $element.data('auto-dismiss') || 5000;
        setTimeout(function(){
            $element.alert('close');
        }, timeout);
    });

    $('body').tooltip({
        trigger: 'hover',
        selector: '.tooltipsC',
        placement: 'top',
        html: true,
        container: 'body'
    });
});