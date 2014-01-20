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
/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global window */
/*global $ */
/*global set_to_lower_case_no_accents */
/*global SET_TextColumnTypeHandler */
/*global SET_OverviewTable */

// ---------------------------------------------------------------------------------------------------------------------
function SET_TextControlColumnTypeHandler() {
  "use strict";
}

// ---------------------------------------------------------------------------------------------------------------------
SET_TextControlColumnTypeHandler.prototype = Object.create(SET_TextColumnTypeHandler.prototype);
SET_TextControlColumnTypeHandler.constructor = SET_TextControlColumnTypeHandler;

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Returns the text content of the input box in a table_cell.
 *
 * @param table_cell
 *
 * @returns string
 */
SET_TextControlColumnTypeHandler.prototype.extractForFilter = function (table_cell) {
  "use strict";
  return set_to_lower_case_no_accents($(table_cell).find('input').val());
};

// ---------------------------------------------------------------------------------------------------------------------
SET_TextControlColumnTypeHandler.prototype.getSortKey = function (table_cell) {
  "use strict";
  return set_to_lower_case_no_accents($(table_cell).find('input').val());
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Register column type handler.
 */
SET_OverviewTable.registerColumnTypeHandler('control_text', SET_TextControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control_button', SET_TextControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control_submit', SET_TextControlColumnTypeHandler);

// ---------------------------------------------------------------------------------------------------------------------
/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global window */
/*global $ */
/*global set_to_lower_case_no_accents */
/*global SET_TextColumnTypeHandler */
/*global SET_OverviewTable */

// ---------------------------------------------------------------------------------------------------------------------
function SET_HtmlControlColumnTypeHandler() {
  "use strict";
}

// ---------------------------------------------------------------------------------------------------------------------
SET_HtmlControlColumnTypeHandler.prototype = Object.create(SET_TextColumnTypeHandler.prototype);
SET_HtmlControlColumnTypeHandler.constructor = SET_HtmlControlColumnTypeHandler;

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Returns the text content of the input box in a table_cell.
 *
 * @param table_cell
 *
 * @returns string
 */
SET_HtmlControlColumnTypeHandler.prototype.extractForFilter = function (table_cell) {
  "use strict";
  return set_to_lower_case_no_accents($(table_cell).children().text());
};

// ---------------------------------------------------------------------------------------------------------------------
SET_HtmlControlColumnTypeHandler.prototype.getSortKey = function (table_cell) {
  "use strict";
  return set_to_lower_case_no_accents($(table_cell).children().text());
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Register column type handler.
 */
SET_OverviewTable.registerColumnTypeHandler('control_div',  SET_HtmlControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control_link', SET_HtmlControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control_span', SET_HtmlControlColumnTypeHandler);

// ---------------------------------------------------------------------------------------------------------------------
/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global window */
/*global $ */
/*global set_to_lower_case_no_accents */
/*global SET_TextColumnTypeHandler */
/*global SET_OverviewTable */

// ---------------------------------------------------------------------------------------------------------------------
function SET_TextAreaControlColumnTypeHandler() {
  "use strict";
}

// ---------------------------------------------------------------------------------------------------------------------
SET_TextAreaControlColumnTypeHandler.prototype = Object.create(SET_TextColumnTypeHandler.prototype);
SET_TextAreaControlColumnTypeHandler.constructor = SET_TextAreaControlColumnTypeHandler;

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Returns the text content of the input box in a table_cell.
 *
 * @param table_cell
 *
 * @returns string
 */
SET_TextAreaControlColumnTypeHandler.prototype.extractForFilter = function (table_cell) {
  "use strict";
  return set_to_lower_case_no_accents($(table_cell).find('textarea').val());
};

// ---------------------------------------------------------------------------------------------------------------------
SET_TextAreaControlColumnTypeHandler.prototype.getSortKey = function (table_cell) {
  "use strict";
  return set_to_lower_case_no_accents($(table_cell).find('textarea').val());
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Register column type handler.
 */
SET_OverviewTable.registerColumnTypeHandler('control_text_area', SET_TextAreaControlColumnTypeHandler);

// ---------------------------------------------------------------------------------------------------------------------
