/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
        
        config.allowedContent = true;
        
        config.contentsCss = "/Public/Admin/ckeditor.css?a="+Math.random();
        
        config.stylesSet = [];
        
        config.tabSpaces = 4;
        
        
        config.width = 850;
        // 文件管理器
        config.filebrowserBrowseUrl = '/admin.php/Editor/elfinder';
};
