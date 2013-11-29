/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{

config.resize_enabled = false;
//config.resize_maxWidth = 500;
config.width = 700;
config.height = 300;
config.fillEmptyBlocks = false;
config.tabSpaces = 0;
config.forcePasteAsPlainText = true;
config.enterMode = CKEDITOR.ENTER_DIV;
config.defaultLanguage = globalLanguage;
config.language = globalLanguage;


config.removePlugins = 'contextmenu';
extraPlugins:'spoiler';
config.filebrowserBrowseUrl = '/../js/ckeditor/plugins/kcfinder/browse.php?type=files';
config.filebrowserImageBrowseUrl = '/../js/ckeditor/plugins/kcfinder/browse.php?type=images';
config.filebrowserFlashBrowseUrl = '/../js/ckeditor/plugins/kcfinder/browse.php?type=flash';
config.filebrowserUploadUrl = '/../js/ckeditor/plugins/kcfinder/upload.php?type=files';
config.filebrowserImageUploadUrl = '/../js/ckeditor/plugins/kcfinder/upload.php?type=images';
config.filebrowserFlashUploadUrl = '/../js/ckeditor/plugins/kcfinder/upload.php?type=flash';

CKEDITOR.on( 'dialogDefinition', function( ev )
   {
      // Take the dialog name and its definition from the event data.
      var dialogName = ev.data.name;
      var dialogDefinition = ev.data.definition;
            
      // Check if the definition is from the dialog we're
      // interested in (the 'link' dialog).
//      if ( dialogName == 'link' )
//      {
         // Remove the 'Target' and 'Advanced' tabs from the 'Link' dialog.
//         dialogDefinition.removeContents( 'target' );
//         dialogDefinition.removeContents( 'advanced' );
 
         // Get a reference to the 'Link Info' tab.
//         var infoTab = dialogDefinition.getContents( 'info' );
 
         // Remove unnecessary widgets from the 'Link Info' tab.         
//         infoTab.remove( 'linkType');
         //infoTab.remove( 'protocol');
//      }
//      if ( dialogName == 'image' )
//      {
	 // Remove the 'advanced' and 'link' tabs from the 'Image' dialog.
	 //dialogDefinition.removeContents( 'advanced' );
	 //dialogDefinition.removeContents( 'Link' );

	 //var infoTab = dialogDefinition.getContents( 'info' );

	 // Remove unnecessary widgets from the 'Image Info' tab.
	 //infoTab.remove( 'ratioLock' );
	 //infoTab.remove( 'txtHeight' );
	 //infoTab.remove( 'txtWidth' );
	 //infoTab.remove( 'htmlPreview' );
	 //infoTab.remove( 'txtBorder' );
	 //infoTab.remove( 'txtHSpace' );
	 //infoTab.remove( 'txtVSpace' );
	 //infoTab.remove( 'cmbAlign' );
	 //dialogDefinition.removeContents( 'info' );
      //}
//      if ( dialogName == 'MediaDialog' )
//      {
//	 dialogDefinition.removeContents( 'playlist' );
//	 dialogDefinition.removeContents( 'player' );
//	 dialogDefinition.removeContents( 'single' );

//      }
   });
   


//config.toolbar = 'Basic';

	config.toolbar_Basic =
	[
		{ name: 'basicitems', items: ['Bold','Italic','Underline'] },
		{ name: 'paragraph', items: ['NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'] },
        { name: 'colors', items: ['TextColor'] },
        { name: 'styles', items: ['Format','FontSize'] },
        { name: 'links', items: ['Link','Unlink'] }
	]

//config.toolbar = 'Full';
	extraPlugins : 'bbcode';
	config.toolbar_Full = 
	[
		{ name: 'document', items: ['Source','-','DocProps','Preview','Print','-','Templates'] },
		{ name: 'clipboard', items: ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'] },
		{ name: 'editing', items: ['Replace','-','SelectAll','-','SpellChecker'] },
		{ name: 'forms', items: ['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'] },
		'/',
		{ name: 'basicstyles', items: ['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'] },
		{ name: 'paragraph', items: ['NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl'] },		
		{ name: 'links', items: ['Link','Unlink','Anchor'] },
		{ name: 'insert', items: ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe', 'Spoiler'] },
		'/',
		{ name: 'styles', items: ['Styles','Format','Font','FontSize'] },
		{ name: 'colors', items: ['TextColor','BGColor'] },
		{ name: 'tools', items: ['Maximize','ShowBlocks','-','About'] },
	];
	
//config.toolbar = 'Omlook';
 
	config.toolbar_Omlook =
	[
		{ name: 'document', items : [ 'Preview' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Replace','-','SelectAll' ] },		
		{ name: 'links', items: ['Link','Unlink'] },

	];
//config.toolbar = 'NewAd';
 
	config.toolbar_NewAd =
	[
		{ name: 'clipboard', items: ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'] },
		{ name: 'editing', items: ['Replace','-','SelectAll'] },
                { name: 'insert', items: ['Table'] },
                { name: 'styles', items: ['Font','FontSize'] },
		'/',
		{ name: 'basicstyles', items: ['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'] },
		{ name: 'paragraph', items: ['NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'] },		
		{ name: 'links', items: ['Link','Unlink'] },
	];
};


//This is an example of creating a new toolbar
//CKEDITOR.editorConfig = function( config )
//{
//	config.toolbar = 'Omlook';
// 
//	config.toolbar_Omlook =
//	[
//		{ name: 'document', items : [ 'NewPage','Preview' ] },
//		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
//		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','Scayt' ] },
//
//	];
//};