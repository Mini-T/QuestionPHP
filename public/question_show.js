var $container = $('.js-vote-arrows');

$container.find('a').on('click', function(e) {
    e.preventDefault();
    var $link = $(e.currentTarget);
    $.ajax({
        url: '/answers/' +$link.data("id")+'/vote/'+$link.data("direction"),
        method: 'POST'
    }).then(function(response) {
        $container.find('.js-vote-total-'+$link.data("id")).text(response.votes);
    });
});