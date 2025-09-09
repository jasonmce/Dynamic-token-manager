/**
 * @file
 * Provides base functionality for dynamic tokens in Drupal.
 * 
 * This file implements a base behavior that other modules can extend to create
 * dynamic, time-based tokens that update in the browser.
 */

(function (Drupal, drupalSettings) {
  'use strict';

  /**
   * Global namespace for dynamic token functionality.
   * @namespace Drupal.dynamicTokens
   */
  Drupal.dynamicTokens = Drupal.dynamicTokens || {
    /**
     * Tracks intervals for dynamic token updates using WeakMap for garbage collection.
     * @type {WeakMap}
     */
    intervals: new WeakMap(),

    /**
     * Parses the speed attribute from an element with a fallback to defaultMs.
     *
     * @param {HTMLElement} el - The DOM element containing the data-speed attribute.
     * @param {number} [defaultMs=1000] - Default interval in milliseconds.
     * @return {number} The parsed speed in milliseconds.
     */
    parseSpeed: function(el, defaultMs) {
      var speedMs = parseInt(el.getAttribute('data-speed'), 10);
      return (isNaN(speedMs) || speedMs < 1)
        ? defaultMs || 1000
        : speedMs * 1000;
    },

    /**
     * Ensures a function only runs once per element using a data attribute flag.
     *
     * @param {string} flag - The name to use for the data attribute flag.
     * @param {HTMLElement} el - The DOM element to check/set the flag on.
     * @return {boolean} True if this is the first call, false otherwise.
     */
    once: function(flag, el) {
      return el.dataset[flag]
        ? false
        : el.dataset[flag] = '1' && true;
    }
  };

  /**
   * Drupal behavior for dynamic token functionality.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.dynamicTokenBase = {
    /**
     * Attach behavior for dynamic tokens.
     *
     * @param {HTMLElement} context - The DOM element that was just added.
     * @param {object} settings - Drupal settings for this behavior.
     */
    attach: function (context, settings) {
      // This is a base behavior. Extend this object to implement specific
      // dynamic token functionality in child behaviors.
    },

    /**
     * Detach behavior for dynamic tokens.
     *
     * @param {HTMLElement} context - The DOM element being removed.
     * @param {object} settings - Drupal settings for this behavior.
     * @param {string} trigger - The trigger causing this detach (e.g., 'unload').
     */
    detach: function (context, settings, trigger) {
      // Child behaviors should override this to clean up any intervals or event
      // listeners when elements are removed from the page.
      // Use Drupal.dynamicTokens.interfaces to track and clear intervals.
    }
  };

})(Drupal, drupalSettings);
