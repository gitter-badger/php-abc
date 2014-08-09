/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global window */
/*global $ */
/*global SET_TextColumnTypeHandler */
/*global SET_OverviewTable */

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Prototype for column handlers for columns with a text input form control.
 * @constructor
 */
function SET_CheckboxControlColumnTypeHandler() {
  "use strict";

  // Use parent constructor.
  SET_TextColumnTypeHandler.call(this);
}

// ---------------------------------------------------------------------------------------------------------------------
SET_CheckboxControlColumnTypeHandler.prototype = Object.create(SET_TextColumnTypeHandler.prototype);
SET_CheckboxControlColumnTypeHandler.constructor = SET_CheckboxControlColumnTypeHandler;

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Returns the text content of the input box in a table_cell.
 *
 * @param table_cell
 *
 * @returns string
 */
SET_CheckboxControlColumnTypeHandler.prototype.extractForFilter = function (table_cell) {
  "use strict";

  if ($(table_cell).find('input:checkbox').prop('checked')) {
    return '1';
  }

  return '0';
};

// ---------------------------------------------------------------------------------------------------------------------
SET_CheckboxControlColumnTypeHandler.prototype.getSortKey = function (table_cell) {
  "use strict";

  if ($(table_cell).find('input:checkbox').prop('checked')) {
    return '1';
  }

  return '0';
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Register column type handlers.
 */
SET_OverviewTable.registerColumnTypeHandler('control-checkbox', SET_CheckboxControlColumnTypeHandler);

// ---------------------------------------------------------------------------------------------------------------------
