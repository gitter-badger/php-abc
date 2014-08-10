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
function SET_RadiosControlColumnTypeHandler() {
  "use strict";

  // Use parent constructor.
  SET_TextColumnTypeHandler.call(this);
}

// ---------------------------------------------------------------------------------------------------------------------
SET_RadiosControlColumnTypeHandler.prototype = Object.create(SET_TextColumnTypeHandler.prototype);
SET_RadiosControlColumnTypeHandler.constructor = SET_RadiosControlColumnTypeHandler;

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Returns the label of the checked radio button.
 *
 * @param table_cell
 *
 * @returns string
 */
SET_RadiosControlColumnTypeHandler.prototype.extractForFilter = function (table_cell) {
  "use strict";

  return SET_OverviewTable.toLowerCaseNoAccents($(table_cell).find('input:radio:checked'));
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Returns the label of the checked radio button.
 *
 * @param table_cell
 *
 * @returns string
 */
SET_RadiosControlColumnTypeHandler.prototype.getSortKey = function (table_cell) {
  "use strict";

  return SET_OverviewTable.toLowerCaseNoAccents($(table_cell).find('input:radio:checked'));
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Register column type handlers.
 */
SET_OverviewTable.registerColumnTypeHandler('control-radios', SET_RadiosControlColumnTypeHandler);

// ---------------------------------------------------------------------------------------------------------------------
