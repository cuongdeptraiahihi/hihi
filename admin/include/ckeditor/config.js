/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	config.filebrowserBrowseUrl = 'http://localhost/www/TDUONG/admin/include/ckeditor/ckfinder/ckfinder.html';
	config.filebrowserImageBrowseUrl = 'http://localhost/www/TDUONG/admin/include/ckeditor/ckfinder/ckfinder.html?type=Images';
	config.filebrowserUploadUrl = 'http://localhost/www/TDUONG/admin/include/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = 'http://localhost/www/TDUONG/admin/include/ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
	
};
