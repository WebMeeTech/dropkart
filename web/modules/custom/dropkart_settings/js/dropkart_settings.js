(function ($, Drupal) {
  Drupal.behaviors.dropkartSettings = {
    attach: function (context, settings) {
      var siteColor = drupalSettings.dropkart_settings.site_color;

      $('.button--primary').css({
        'background-color': siteColor,
        'border-color': siteColor,
      });

      $('.comment-user-name').css({
        'color': siteColor,
      });

      $('.cart-block--summary__count').css({
        'background': siteColor,
      });

      $('.block--search form input[type=submit]').css({
        'background': siteColor,
        'border-color': siteColor,
      });
    }
  };
})(jQuery, Drupal);
