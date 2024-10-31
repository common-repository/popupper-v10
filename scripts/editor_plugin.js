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
var plugin_url    = '../wp-content/plugins/popupper-v10';

(function(){
  tinymce.create('tinymce.plugins.TinyMCE_PopupperPlugin', {

    /**
     * Local Variables
     */

    /**
     * Returns information about the plugin as a name/value array.
     * The current keys are longname, author, authorurl, infourl and version.
     *
     * @return {Object} Name/value array containing information about the plugin.
     */
    getInfo : function() {
      return {
        longname  : 'Popupper plugin',
        author    : 'Ed Hogan',
        authorurl : 'http://ehogan.itis5am.com:8080/',
        infourl   : 'http://ehogan.itis5am.com:8080/popupper',
        version   : "1.6"
      };
    },

    /**
     * Initializes the plugin, this will be executed after the plugin has been created.
     * This call is done before the editor instance has finished it's initialization so use the onInit event
     * of the editor instance to intercept that event.
     *
     * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
     * @param {string} url Absolute URL to where the plugin is located.
     */
    init : function(ed, url) {
      ed.onNodeChange.add(function(ed, cm, n) {
        if(n.nodeName.toLowerCase()=='a'){
          cm.setDisabled('popupper',1);
        }
        else {
          if ((ed.selection.getContent() != '') && (n.nodeName.toLowerCase() != 'img')){
            cm.setDisabled('popupper', 0);
          }
          else {
            cm.setDisabled('popupper', 1);
          }
        }
      });
    },

    showPopupDialog : function (cm) {
      tinyMCE.activeEditor.windowManager.open({url : plugin_url + '/popup_dialog.php',
                                               width : 400,
                                               height : 275,
                                               inline : true},
                                              {custom_param:1});
    },

    /**
     * Creates control instances based in the incoming name. This method is normally not
     * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
     * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
     * method can be used to create those.
     *
     * @param {String} n Name of the control to create.
     * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
     * @return {tinymce.ui.Control} New control instance or null if no control was created.
     */
    createControl : function(n, cm) {
      var t = this, c;
      if (n == 'popupper') {
        var b = cm.createButton('popupper',
                    {title : _popupper_v10.i18n.title,
                     image : plugin_url + '/images/note_add.png',
                     onclick : function() { t.showPopupDialog(cm); }
                    });
        return b;
      }
    }
  
  }); /* end of tinymce.create */

  /* Register the plugin */
  tinymce.PluginManager.add('popupper', tinymce.plugins.TinyMCE_PopupperPlugin);
})();

