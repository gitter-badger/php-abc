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
function SET_TextControlColumnTypeHandler() {
  "use strict";

  // Use parent constructor.
  SET_TextColumnTypeHandler.call(this);
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
  return SET_OverviewTable.toLowerCaseNoAccents($(table_cell).find('input').val());
};

// ---------------------------------------------------------------------------------------------------------------------
SET_TextControlColumnTypeHandler.prototype.getSortKey = function (table_cell) {
  "use strict";
  return SET_OverviewTable.toLowerCaseNoAccents($(table_cell).find('input').val());
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Register column type handlers.
 */
SET_OverviewTable.registerColumnTypeHandler('control-text', SET_TextControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control-button', SET_TextControlColumnTypeHandler);
SET_OverviewTable.registerColumnTypeHandler('control-submit', SET_TextControlColumnTypeHandler);

// ---------------------------------------------------------------------------------------------------------------------
