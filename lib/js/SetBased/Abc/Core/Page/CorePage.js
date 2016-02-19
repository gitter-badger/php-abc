/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global define */
/*global set_based_abc_inline_js*/

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Core/Page/CorePage',
  ['jquery',
    'SetBased/Abc/Page/Page',
    'SetBased/Abc/Core/InputTable',
    'SetBased/Abc/Table/OverviewTablePackage',
    'SetBased/Abc/Form/FormPackage'],

  function ($, Page, InputTable, OverviewTable, Form) {
    'use strict';
    //------------------------------------------------------------------------------------------------------------------
    Page.enableDatePicker();

    OverviewTable.registerTable('.overview_table');
    Form.registerForm('form');

    InputTable.registerTable('form');
    $('form').submit(InputTable.setCsrfValue);

    $('.icon_action').click(Page.showConfirmMessage);

    /*jslint evil: true */
    if (typeof set_based_abc_inline_js !== 'undefined') {
      eval(set_based_abc_inline_js);
    }

    //------------------------------------------------------------------------------------------------------------------
  }
);

//----------------------------------------------------------------------------------------------------------------------
