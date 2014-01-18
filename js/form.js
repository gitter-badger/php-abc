/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global window */
/*global $ */

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Object constructor.
 *
 * @param {jQuery} $form The jQuery object of the SET form.
 * @constructor
 */
function SET_Form($form) {
  "use strict";
  var that = this;

  this.$myFrom = $form;

  // Install event handlers.
  this.$myFrom.submit(function () {
    // Note: When a form control is disabled the browser will not send the value of the input to web server and
    // the Set Based form classes will assume that the values of these form controls have changed. To prevent this
    // we enable all disabled form controls such that the browser will send the value of disabled form controls.
    that.$myFrom.find(':disabled').prop('disabled', false);
  });
}

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Array with all registered SET forms.
 *
 * @type {{SET_Form}}
 */
SET_Form.ourForms = [];

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Registers forms that match the jQuery selector as a SET form.
 */
SET_Form.registerForm = function (selector) {
  "use strict";
  $(selector).each(function () {
    var $this = $(this);

    if ($this.is('form')) {
      // Selector is a form.
      SET_Form.ourForms[SET_Form.ourForms.length] = new SET_Form($this);
    } else {
      // Selector is not a form. Find forms below the selector.
      $this.find('form').each(function () {
        SET_Form.ourForms[SET_Form.ourForms.length] = new SET_Form($(this));
      });
    }
  });
};

// ---------------------------------------------------------------------------------------------------------------------
