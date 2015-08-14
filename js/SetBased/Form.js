/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global define */
/*global require */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Form',

  ['jquery'],

  function ($) {
    "use strict";
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Object constructor.
     *
     * @param {jQuery} $form The jQuery object of the SetBased/Form.
     * @constructor
     */
    function Form($form) {
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

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Array with all registered SetBased/Form.
     *
     * @type {{Form}}
     */
    Form.ourForms = [];

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Registers forms that match the jQuery selector as a SetBased/Form.
     */
    Form.registerForm = function (selector) {
      $(selector).each(function () {
        var $this = $(this);

        if ($this.is('form')) {
          // Selector is a form.
          Form.ourForms[Form.ourForms.length] = new Form($this);
        } else {
          // Selector is not a form. Find forms below the selector.
          $this.find('form').each(function () {
            Form.ourForms[Form.ourForms.length] = new Form($(this));
          });
        }
      });
    };

    //------------------------------------------------------------------------------------------------------------------
    return Form;
  }
);

//----------------------------------------------------------------------------------------------------------------------
