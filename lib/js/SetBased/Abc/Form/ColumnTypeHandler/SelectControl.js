/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Form/ColumnTypeHandler/SelectControl',

  ['jquery',
    'SetBased/Abc/Table/OverviewTable',
    'SetBased/Abc/Table/ColumnTypeHandler/Text'],

  function ($, OverviewTable, Text) {
    "use strict";
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Prototype for column handlers for columns with a text input form control.
     * @constructor
     */
    function SelectControl() {
      // Use parent constructor.
      Text.call(this);
    }

    //------------------------------------------------------------------------------------------------------------------
    SelectControl.prototype = Object.create(Text.prototype);
    SelectControl.constructor = SelectControl;

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the label of the checked radio button.
     *
     * @param table_cell
     *
     * @returns string
     */
    SelectControl.prototype.extractForFilter = function (table_cell) {
      var text;

      text = $(table_cell).find('select option:selected').text();

      return OverviewTable.toLowerCaseNoAccents(text);
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the label of the checked radio button.
     *
     * @param table_cell
     *
     * @returns string
     */
    SelectControl.prototype.getSortKey = function (table_cell) {
      var text;

      text = $(table_cell).find('select option:selected').text();

      return OverviewTable.toLowerCaseNoAccents(text);
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Register column type handlers.
     */
    OverviewTable.registerColumnTypeHandler('control-select', SelectControl);

    //------------------------------------------------------------------------------------------------------------------
    return SelectControl;
  }
);

//------------------------------------------------------------------------------------------------------------------


