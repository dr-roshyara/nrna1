/**
 * Click-Outside Directive
 *
 * Detects clicks outside a given element and executes a callback.
 * Useful for closing dropdowns, tooltips, and modals.
 *
 * Usage:
 *   v-click-outside="handleClickOutside"
 *   v-click-outside:exclude=".exclude-selector"="handleClickOutside"
 *
 * @module directives/clickOutside
 */

/**
 * Bind phase - sets up event listeners
 * @param {HTMLElement} el - The element to watch
 * @param {Object} binding - The binding object
 */
const bind = (el, binding) => {
  // Store the callback in element data for later access
  el.clickOutsideEvent = function (event) {
    // Check if click is outside the element
    if (!(el === event.target || el.contains(event.target))) {
      // Handle exclusions (e.g., don't close if clicking on certain elements)
      const excludeSelector = binding.arg ? binding.arg : null;
      const excludedElements = excludeSelector
        ? document.querySelectorAll(excludeSelector)
        : [];

      let isExcluded = false;
      for (const excludedEl of excludedElements) {
        if (excludedEl === event.target || excludedEl.contains(event.target)) {
          isExcluded = true;
          break;
        }
      }

      // Only trigger callback if click wasn't on an excluded element
      if (!isExcluded) {
        binding.value(event);
      }
    }
  };

  // Add event listener with capture phase for better performance
  document.addEventListener('click', el.clickOutsideEvent, true);
};

/**
 * Unbind phase - removes event listeners
 * @param {HTMLElement} el - The element to cleanup
 */
const unbind = (el) => {
  if (el.clickOutsideEvent) {
    document.removeEventListener('click', el.clickOutsideEvent, true);
    delete el.clickOutsideEvent;
  }
};

/**
 * Update phase - updates the callback if directive binding changes
 * @param {HTMLElement} el - The element
 * @param {Object} binding - The binding object
 */
const update = (el, binding) => {
  if (binding.value !== binding.oldValue) {
    unbind(el);
    bind(el, binding);
  }
};

/**
 * Vue 3 Directive Plugin
 * Exposes hook methods that Vue calls during lifecycle
 */
export default {
  // Lifecycle hook called when element is mounted
  mounted: bind,

  // Lifecycle hook called when binding updates
  updated: update,

  // Lifecycle hook called when element is unmounted
  unmounted: unbind,

  /**
   * Alternative lifecycle hook names for Vue 2 compatibility
   * (if this directive needs to work with both Vue 2 & 3)
   */
  bind,
  update,
  unbind,
};
