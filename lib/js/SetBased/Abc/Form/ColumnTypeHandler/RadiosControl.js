/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Form/ColumnTypeHandler/RadiosControl',

  ['jquery',
    'SetBased/Abc/OverviewTable',
    'SetBased/Abc/OverviewTable/ColumnTypeHandler/Text'],

  function ($, OverviewTable, Text) {
    "use strict";
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Prototype for column handlers for columns with a text input form control.
     * @constructor
     */
    function RadiosControl() {
      // Use parent constructor.
      Text.call(this);
    }

    //------------------------------------------------------------------------------------------------------------------
    RadiosControl.prototype = Object.create(Text.prototype);
    RadiosControl.constructor = RadiosControl;

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the label of the checked radio button.
     *
     * @param table_cell
     *
     * @returns string
     */
    RadiosControl.prototype.extractForFilter = function (table_cell) {
      var id;

      id = $(table_cell).find('input[type="radio"]:checked').prop('id');

      return OverviewTable.toLowerCaseNoAccents(($('label[for=' + id + ']').text()));
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Returns the label of the checked radio button.
     *
     * @param table_cell
     *
     * @returns string
     */
    RadiosControl.prototype.getSortKey = function (table_cell) {
      var id;

      id = $(table_cell).find('input[type="radio"]:checked').prop('id');

      return OverviewTable.toLowerCaseNoAccents(($('label[for=' + id + ']').text()));
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Register column type handlers.
     */
    OverviewTable.registerColumnTypeHandler('control-radios', RadiosControl);

    //------------------------------------------------------------------------------------------------------------------
    return RadiosControl;
  }
);

//----------------------------------------------------------------------------------------------------------------------
