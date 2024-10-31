/*  Popupper v1.6 Plugin for WordPress 
    Copyright (C) 2008 Edward P. Hogan    

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

var popup_dialog = {
  preInit : function() {
    var url;

    tinyMCEPopup.requireLangPack();

    if (url = tinyMCEPopup.getParam("external_image_list_url"))
      document.write('<script language="javascript" type="text/javascript" src="' + tinyMCEPopup.editor.documentBaseURI.toAbsolute(url) + '"></script>');
  },

  init : function() {
    var f = document.forms[0], ed = tinyMCEPopup.editor;

    // Setup browse button
    document.getElementById('srcbrowsercontainer').innerHTML = getBrowserHTML('srcbrowser','src','image','theme_advanced_image');
    if (isVisible('srcbrowser'))
      document.getElementById('src').style.width = '180px';

    e = ed.selection.getNode();

    if (e.nodeName == 'IMG') {
      f.src.value = ed.dom.getAttrib(e, 'src');
      f.alt.value = ed.dom.getAttrib(e, 'alt');
      f.width.value = ed.dom.getAttrib(e, 'width');
      f.height.value = ed.dom.getAttrib(e, 'height');
      f.insert.value = ed.getLang('update');
      selectByValue(f, 'align', this.getAttrib(e, 'align'));
      this.updateStyle();
    }
  },

  update : function() {
    var f = document.forms[0], nl = f.elements, ed = tinyMCEPopup.editor, args = {}, el,e;

    tinyMCEPopup.restoreSelection();
    /* the popup is empty */
    if ((f.src.value === '') && (f.popuptext.value === '')) {
      tinyMCEPopup.close();
      return;
    }

    el = ed.selection.getNode();

    var uniq_key = f.uniq_key.value;
    var img = f.src.value;
    var img_width = f.width.value;
    var img_height = f.height.value;
    var alt = f.alt.value;
    var textinfo = f.popuptext.value;
    
    var decorate = getSelectValue(f, 'linkdecorate');
    var start_anchor     = "<a id=\"ctx_" + uniq_key + "\">";
    var start_decoration = "";
    var end_decoration   = "";
    var end_anchor       = "</a>";

    switch (decorate) {
      /* Underlining */
      case 'double-underline':
        start_decoration = "<span style=\"border-bottom-style: double; border-bottom-width: medium;\">";
        end_decoration =   "</span>";
        break;
      case 'dashed-underline':
        start_decoration = "<span style=\"border-bottom-style: dashed; border-bottom-width: thin;\">";
        end_decoration =   "</span>";
        break;
      case 'bold':
        start_decoration = "<span style=\"font-weight: bold;\">";
        end_decoration =   "</span>";
        break;
      case 'bold-underline':
        start_decoration = "<span style=\"border-bottom-style: solid; border-bottom-width: thin; font-weight: bold;\">";
        end_decoration =   "</span>";
        break;
      /* Bordering */
      case 'dashed-box':
        start_decoration = "<span style=\"border-style: dashed; border-width: thin;\">";
        end_decoration =   "</span>";
        break;
      case 'dotted-box':
        start_decoration = "<span style=\"border-style: dotted; border-width: thin;\">";
        end_decoration =   "</span>";
        break;
      /* Highlighting */
      case 'yellow-highlight':
        start_decoration = "<span style=\"background-color:#FFFF99;\">";
        end_decoration =   "</span>";
        break;
      case 'light-blue-highlight':
        start_decoration = "<span style=\"background-color:#87CEFA;\">";
        end_decoration =   "</span>";
        break;
      case 'light-green-highlight':
        start_decoration = "<span style=\"background-color:#90EE90;\">";
        end_decoration =   "</span>";
        break;
      case 'pink-highlight':
        start_decoration = "<span style=\"background-color:#FFB6C1;\">";
        end_decoration =   "</span>";
        break;
      case 'underline':
      default:
        start_decoration = "<span style=\"text-decoration: underline;\">";
        end_decoration =   "</span>";
        break;
    }
        
    var popline_text = start_anchor + start_decoration + ed.selection.getContent() + end_decoration + end_anchor;
    ed.execCommand('mceInsertRawHTML', false, popline_text, {skip_undo : 1});
    ed.undoManager.add();
    return true;
  },
  
  updateStyle : function() {
    var dom = tinyMCEPopup.dom, st, v, cls, oldcls, rep, f = document.forms[0];

    if (tinyMCEPopup.editor.settings.inline_styles) {
      st = tinyMCEPopup.dom.parseStyle(this.styleVal);

      // Handle align
      v = getSelectValue(f, 'align');
      cls = '';
      cls = cls ? cls.replace(/alignright\s*|alignleft\s*|aligncenter\s*/g, '') : '';
      cls = cls ? cls.replace(/^\s*(.+?)\s*$/, '$1') : '';
      if (v) {
        if (v == 'left' || v == 'right') {
          st['float'] = v;
          delete st['vertical-align'];
          oldcls = cls ? ' '+cls : '';
        } else {
          st['vertical-align'] = v;
          delete st['float'];
        }
      } else {
        delete st['float'];
        delete st['vertical-align'];
      }

      // Merge
      st = tinyMCEPopup.dom.parseStyle(dom.serializeStyle(st));
      this.styleVal = dom.serializeStyle(st);
    }
  },
  
  linkDecorateOnChange : function () {
    var decorate = getSelectValue(document.forms[0], 'linkdecorate');
    var linksampletext = document.getElementById('linksampletext');
    /* clear old style */
    linksampletext.style.setProperty('background-color', 'none');
    linksampletext.style.setProperty('border-style', 'none');
    linksampletext.style.setProperty('border-width', 'none');
    linksampletext.style.setProperty('font-weight', 'normal');
    linksampletext.style.setProperty('text-decoration', 'none');
    switch (decorate) {
      /* Underlining */
      case 'double-underline':
        linksampletext.style.setProperty('border-bottom-style', 'double');
        linksampletext.style.setProperty('border-bottom-width', 'medium');
        break;
      case 'dashed-underline':
        linksampletext.style.setProperty('border-bottom-style', 'dashed');
        linksampletext.style.setProperty('border-bottom-width', 'thin');
        break;
            case 'bold':
        linksampletext.style.setProperty('font-weight', 'bold');
        break;
      case 'bold-underline':
        linksampletext.style.setProperty('text-decoration', 'underline');
        linksampletext.style.setProperty('font-weight', 'bold');
        break;
      /* Bordering */
      case 'dashed-box':
        linksampletext.style.setProperty('border-style', 'dashed');
        linksampletext.style.setProperty('border-width', 'thin');
        break;
      case 'dotted-box':
        linksampletext.style.setProperty('border-style', 'dotted');
        linksampletext.style.setProperty('border-width', 'thin');
        break;
      /* Highlighting */
      case 'yellow-highlight':
        linksampletext.style.setProperty('background-color', '#FFFF99'); /* LemonChiffon */
        break;
      case 'light-blue-highlight':
        linksampletext.style.setProperty('background-color', '#87CEFA'); /* LightSkyBlue */
        break;
      case 'light-green-highlight':
        linksampletext.style.setProperty('background-color', '#90EE90'); /* LightGreen */
        break;
      case 'pink-highlight':
        linksampletext.style.setProperty('background-color', '#FFB6C1'); /* LightPink */
        break;
      case 'underline':
      default:
        linksampletext.style.setProperty('text-decoration', 'underline');
        break;
    }
    return true;
  },
  
  preview : function() {
    var f = document.forms[0];
    var img = f.src.value;
    var img_width = f.width.value;
    var img_height = f.height.value;
    var text = f.popuptext.value;
    
    var img_src = "";
    var extra_align = "";
    var popupper_text = null;
    var popupper_width = null;
    
    if (previewToolip != null) {
      previewToolip.doHide();
      previewToolip.destroy();
    }
    
    if (img != "") {
      if (img_width != "") {
        img_width_text = " width='" + img_width + "'";
      }
      if (img_height != "") {
        img_height_text = " height='" + img_height + "'";
      }
      if (text != "") {
        extra_align = " hspace='10' vspace='10' align='left'";
      }
      img_src = "<img src='" + img + "'" + img_width_text + img_height_text + extra_align + " alt='' />"; 
    }
    else {
      img_src = null;
    }
    if (img_src != null) {
      if (text == "") {
        /* case 1: image-only */
        popupper_text = "<p>" + img_src + "</p>";
      }
      else {
        /* case 2: image and text */
        popupper_text = "<table><tr><td><p>" + img_src + "</p><p align='justify'>" + text + "</p></td></tr></table>";
      }
    }
    else {
      /* case 3: text-only */
      popupper_text = "<p>" + text + "</p>";
      popupper_width = "150px";
    }
    if (popupper_text == null) {
      /* xxx alert that nothing to preview */
      return true;
    }
    previewToolip = new YAHOO.widget.Tooltip("preview_tooltip",
                         { context: "preview",
                           text : popupper_text,
                           width : "auto",
                           showDelay : 0 });
    previewToolip.doShow();
    return true;
  },

  getAttrib : function(e, at) {
    var ed = tinyMCEPopup.editor, dom = ed.dom, v, v2;

    if (ed.settings.inline_styles) {
      switch (at) {
        case 'align':
          if (v = dom.getStyle(e, 'float'))
            return v;

          if (v = dom.getStyle(e, 'vertical-align'))
            return v;

          break;

        case 'hspace':
          v = dom.getStyle(e, 'margin-left')
          v2 = dom.getStyle(e, 'margin-right');
          if (v && v == v2)
            return parseInt(v.replace(/[^0-9]/g, ''));

          break;

        case 'vspace':
          v = dom.getStyle(e, 'margin-top')
          v2 = dom.getStyle(e, 'margin-bottom');
          if (v && v == v2)
            return parseInt(v.replace(/[^0-9]/g, ''));

          break;

        case 'border':
          v = 0;

          tinymce.each(['top', 'right', 'bottom', 'left'], function(sv) {
            sv = dom.getStyle(e, 'border-' + sv + '-width');

            // False or not the same as prev
            if (!sv || (sv != v && v !== 0)) {
              v = 0;
              return false;
            }

            if (sv)
              v = sv;
          });

          if (v)
            return parseInt(v.replace(/[^0-9]/g, ''));

          break;
      }
    }

    if (v = dom.getAttrib(e, at))
      return v;

    return '';
  },

  resetImageData : function() {
    var f = document.forms[0];

    f.width.value = f.height.value = "";  
  },

  updateImageData : function() {
    var f = document.forms[0], t = popup_dialog;

    if (f.width.value == "")
      f.width.value = t.preloadImg.width;

    if (f.height.value == "")
      f.height.value = t.preloadImg.height;
  },

  getImageData : function() {
    var f = document.forms[0];

    this.preloadImg = new Image();
    this.preloadImg.onload = this.updateImageData;
    this.preloadImg.onerror = this.resetImageData;
    this.preloadImg.src = tinyMCEPopup.editor.documentBaseURI.toAbsolute(f.src.value);
  }
};

var previewToolip = null;
popup_dialog.preInit();
tinyMCEPopup.onInit.add(popup_dialog.init, popup_dialog);
