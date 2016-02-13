/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Table/ColumnTypeHandler/Bool',

  ['jquery',
    'SetBased/Abc/Table/OverviewTable',
    'SetBased/Abc/Table/ColumnTypeHandler/Text'],

  function ($, OverviewTable, Text) {
    "use strict";
    //------------------------------------------------------------------------------------------------------------------
    function Bool() {
      // Use parent constructor.
      Text.call(this);
    }

    //------------------------------------------------------------------------------------------------------------------
    // Extend Bool from Text.
    Bool.prototype = Object.create(Text.prototype);
    // Set constructor for Bool.
    Bool.constructor = Bool;

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the boolean content (i.e. 0 or 1) of a table_cell.
     *
     * @param {HTMLTableElement} table_cell The table cell.
     *
     * @returns {string}
     */
    Bool.prototype.extractForFilter = function (table_cell) {
      return $(table_cell).attr('data-value');
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the numeric content of a table cell.
     *
     * @param {HTMLTableCellElement} table_cell The table cell.
     *
     * @returns {number}
     */
    Bool.prototype.getSortKey = function (table_cell) {
      return $(table_cell).attr('data-value');
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Register column type handler.
     */
    OverviewTable.registerColumnTypeHandler('bool', Bool);

    //------------------------------------------------------------------------------------------------------------------
    return Bool;
  }
);

//----------------------------------------------------------------------------------------------------------------------
