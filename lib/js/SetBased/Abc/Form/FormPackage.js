/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Form/FormPackage',

  ['SetBased/Abc/Form/Form',
    'SetBased/Abc/Form/ColumnTypeHandler/CheckboxControl',
    'SetBased/Abc/Form/ColumnTypeHandler/HtmlControl',
    'SetBased/Abc/Form/ColumnTypeHandler/RadiosControl',
    'SetBased/Abc/Form/ColumnTypeHandler/SelectControl',
    'SetBased/Abc/Form/ColumnTypeHandler/TextAreaControl',
    'SetBased/Abc/Form/ColumnTypeHandler/TextControl'],

  function (Form) {
    "use strict";

    return Form;
  }
);

//----------------------------------------------------------------------------------------------------------------------
