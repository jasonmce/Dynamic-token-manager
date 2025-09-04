(function (Drupal, drupalSettings) {
  Drupal.dynamicTokens = Drupal.dynamicTokens || {
    intervals: new WeakMap(),
    parseSpeed: function(el, defaultMs) {
      var s = parseInt(el.getAttribute('data-speed'));
      if (isNaN(s) || s < 1) return defaultMs || 1000;
      return s * 1000;
    },
    once: function(flag, el) {
      if (el.dataset[flag]) return false;
      el.dataset[flag] = '1';
      return true;
    }
  };

  Drupal.behaviors.dynamicTokenBase = {
    attach: function (context, settings) {
      // Shared helpers placeholder.
    },
    detach: function (context, settings, trigger) {
      // Clear intervals if needed by children.
    }
  };
})(Drupal, drupalSettings);
