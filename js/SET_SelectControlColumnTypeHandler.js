// ---------------------------------------------------------------------------------------------------------------------
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
function SET_SelectControlColumnTypeHandler() {
  "use strict";

  // Use parent constructor.
  SET_TextColumnTypeHandler.call(this);
}

// ---------------------------------------------------------------------------------------------------------------------
SET_SelectControlColumnTypeHandler.prototype = Object.create(SET_TextColumnTypeHandler.prototype);
SET_SelectControlColumnTypeHandler.constructor = SET_SelectControlColumnTypeHandler;

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Returns the label of the checked radio button.
 *
 * @param table_cell
 *
 * @returns string
 */
SET_SelectControlColumnTypeHandler.prototype.extractForFilter = function (table_cell) {
  "use strict";
  var text;

  text = $(table_cell).find('select option:selected').text();

  return SET_OverviewTable.toLowerCaseNoAccents(text);
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Returns the label of the checked radio button.
 *
 * @param table_cell
 *
 * @returns string
 */
SET_SelectControlColumnTypeHandler.prototype.getSortKey = function (table_cell) {
  "use strict";
  var text;

  text = $(table_cell).find('select option:selected').text();

  return SET_OverviewTable.toLowerCaseNoAccents(text);
};

// ---------------------------------------------------------------------------------------------------------------------
/**
 * Register column type handlers.
 */
SET_OverviewTable.registerColumnTypeHandler('control-select', SET_SelectControlColumnTypeHandler);

