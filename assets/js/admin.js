(function ($) {
    // Social Share Sortable
    $('.twp-be-social-re-oprder').sortable({
        axis: 'y',
        containment: "parent",
        update:function(event,ui){
                    var profile_array = [];
                    $('.twp-be-social-share-wrap input[type="checkbox"]').each(function(){
                    profile_array.push($(this).attr('data-key')) ;
                    });
                    var social_networks_orders = profile_array.join(',');
                    $('#twp_social_share_options').val(social_networks_orders);
                }
    });
    $('.twp-be-tab').click(function(){
        var id = $(this).attr('id');
        $('.twp-be-tab').removeClass('twp-tab-active');
        $(this).addClass('twp-tab-active');
        $('.twp-be-content').removeClass('twp-content-active');
        $('#'+id+'-content').addClass('twp-content-active');
        
    });
    $('.twp-toggle-control').click(function(){
        $(this).closest('.twp-be-social-share-options').find('.twp-social-control').slideToggle();
    });
}(jQuery));