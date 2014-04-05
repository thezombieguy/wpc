// Uploading files
var file_frame;

(function($){
  //live deprecated? not in this version?
  $('.wpc-button').live('click', function( event ){

    event.preventDefault();

    // if its there, reset it so it can open new again with new data.
    if ( file_frame ) {
      file_frame = null;
    }

    var button = $(this);

    var id = button.attr('id').replace('_button', '');
    var filetype = button.data('filetype');
    var settings = {
      title: $( this ).data( 'uploader_title' ),
      button: {
        text: $( this ).data( 'uploader_button_text' ),
      },

      multiple: false  // Set to true to allow multiple files to be selected
    };

    if(filetype === 'image'){
      settings.library = {type: 'image'};
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media(settings);

    // When an image is selected, run a callback.
    file_frame.on( 'select', function() {
      // We set multiple to false so only get one image from the uploader
      attachment = file_frame.state().get('selection').first().toJSON();
      $("#"+id).val(attachment.url);
      //file_frame.close();

      //kill it njow
      file_frame = null;
      // Do something with attachment.id and/or attachment.url here
      switch(filetype){
        case 'image':
          break;
        case 'file':
          $("#file-"+id).attr('href', attachment.url);
          $("#file-"+id).text(attachment.filename);
          break;

      }
    });

    // Finally, open the modal
    file_frame.open();
  });
})(jQuery);

var target;

(function($){

  $(document).on('click', '.wpc-link-btn', function(event) {
    target = $($(this).data('linkid'));
    wpActiveEditor = true;  // We need to override this var as the link dialogue is expecting an actual wp_editor instance
    wpLink.open();  // Open the link popup

    if(target.val() != ''){
      var data = JSON.parse(target.val());
      $('#wp-link #url-field').val((data.href != '') ? data.href : 'http://');
      $('#wp-link #link-title-field').val(data.title);
      if(data.target == '_blank') {
        $('#wp-link #link-target-checkbox').attr('checked');
      }
    }
    return false;
  });

  $(document).on('click', '.wpc-remove-link', function(event) {
    event.preventDefault();
    //console.log('remove');
    var targ = $($(this).data('target'));
    targ.val('');
    var linktarget = $('#'+targ.attr('id')+'_link');
    linktarget.hide();
    $(this).hide();
  });

  $(document).on('click', '#wp-link-submit', function(event) {
    var linkAtts = wpLink.getAttrs(); // The links attributes (href, target) are stored in an object, which can be access via  wpLink.getAttrs()

    var link = $('#'+target.attr('id')+'_link');
    if(linkAtts.href != 'http://' && linkAtts.href != '') {
      target.val(JSON.stringify(linkAtts)); // Get the href attribute and add to a textfield, or use as you see fit
      link.attr('href', linkAtts.href);
      link.attr('target', linkAtts.target);
      link.text((linkAtts.title != '') ? linkAtts.title : linkAtts.href);
      link.show();
      link.next('.link').show();
    }
    // To close the link dialogue, it is again expecting an wp_editor instance, so you need to give it something to set focus back to.
    wpLink.textarea = target;
    wpLink.close(); // Close the dialogue
    //are these two lines neccessary?
    //event.preventDefault ? event.preventDefault() : event.returnValue = false;  // Trap any events
    event.stopPropagation();  // Trap any events
    return false;
  });

  $(document).on('click', '#wp-link-cancel', function(event) {
    wpLink.textarea = target;
    wpLink.close();
    //event.preventDefault ? event.preventDefault() : event.returnValue = false;    // Trap any events
    event.stopPropagation();  // Trap any events
    return false;
  });


})(jQuery);


