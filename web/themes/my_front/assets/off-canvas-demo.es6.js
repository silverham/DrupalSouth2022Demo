'use strict';

(function ($, Drupal) {
  Drupal.behaviors.MyThemeAddMyThingDialog = {
    attach: function (context, settings) {
      $(context)
        .find('a.use-ajax.my-extra-class').once('my-extra-class-processed')
        .each(function (element_index, element) {
           $.each(Drupal.ajax.instances, function(index, instance) {
            if ((!instance.element) || !$(element).is(instance.element)) {
              // Continue.
              return true;
            }
            let classSuffixes = ['--mything'];
            let dialogOptions = {
              'classes': {
                'ui-dialog':                $.map(classSuffixes, (e) => { return 'ui-dialog'.concat(e); }).join(' '),
                'ui-dialog-off-canvas':     $.map(classSuffixes, (e) => { return 'ui-dialog-off-canvas'.concat(e); }).join(' '),
                "ui-dialog-titlebar":       $.map(classSuffixes, (e) => { return 'ui-dialog-titlebar'.concat(e); }).join(' '),
                "ui-dialog-title":          $.map(classSuffixes, (e) => { return 'ui-dialog-title'.concat(e); }).join(' '),
                "ui-dialog-titlebar-close": $.map(classSuffixes, (e) => { return 'ui-dialog-titlebar-close'.concat(e); }).join(' '),
                "ui-dialog-content":        $.map(classSuffixes, (e) => { return 'ui-dialog-content'.concat(e); }).join(' '),
                "ui-dialog-buttonpane":     $.map(classSuffixes, (e) => { return 'ui-dialog-buttonpane'.concat(e); }).join(' '),
              },
              is_my_thing: true,
              width: 300,
            };
            // @see Drupal.ajax.bindAjaxLinks()
            // This built from the elmement data.
            instance.elementSettings.dialog = $.extend(true, instance.elementSettings.dialog, dialogOptions);
            // Key part to change.
            // @see Drupal.Ajax().
            // @see web/core/misc/ajax.es6.js
            instance.options.data.dialogOptions = $.extend(true, instance.options.data.dialogOptions, dialogOptions);
          });
        });
    }
  };
  // Add body class when modal is open.
  $(window).on('dialog:beforecreate', function (e, dialog, $element, settings) {
    if (settings.is_my_thing) {
      $('body').addClass('modal-dialog-open--off-canvas--my-thing');
    }
  });
  // Remove body class when modal is closed.
  $(window).on('dialog:beforeclose', function (e, dialog, $element) {
    let settings = $element.dialog('option');
    if (settings.is_my_thing) {
      $('body').removeClass('modal-dialog-open--off-canvas--my-thing');
      // Undo height being set.
      $('.another-element-resize').removeAttr('style');
    }
  });

  $(window).on('dialog:aftercreate', function (e, dialog, $element, settings) {
    if (settings.is_my_thing) {
      // @see Drupal.offCanvas.afterCreate().
      const eventData = { settings, $element, offCanvasDialog: this };
      $element.on('dialogContentResize.off-canvas', eventData, function (event) {
        let settings = event.data.settings;
        const $element = event.data.$element;
        const $container = Drupal.offCanvas.getContainer($element);
        const width = $container.outerWidth();
        $('.another-element-resize').css('height', width);
      });
    }
  });
})(jQuery, Drupal);