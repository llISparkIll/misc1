jQuery(document).ready(function() {
    jQuery("a.menu-item-text").click(function () {
   elementClick = jQuery(this).attr("href")
   destination = jQuery(elementClick).offset().top-130;
   jQuery("html:not(:animated),body:not(:animated)").animate({scrollTop: destination}, 1500);
   return false;
   });
});