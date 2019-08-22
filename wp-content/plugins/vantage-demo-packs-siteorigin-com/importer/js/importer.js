jQuery( function( $ ){

    var $imp = $('#siteorigin-importer'),
        $fr = $imp.find('.frames .frame');

    $fr.eq(0).show();

    $imp.find('#import-accept').keyup( function(){
        var $$ = $(this);
        if( $$.val().trim().toLowerCase() == 'accept' ) {
            $imp.find('#start-import').attr('disabled', null);
        }
        else {
            $imp.find('#start-import').attr('disabled', true);
        }
    } );


    var importerRunning = false;

    $imp.find('#start-import').click( function(){

        if( ! confirm( importerSettings.strings[ 'confirm-start' ] ) ) {
            return false;
        }

        // To start, show the next frame
        $fr.hide();
        $fr.eq(1).show();
        importerRunning = true;

        // Create a progress bar
        var actions = JSON.parse( JSON.stringify( importerActions ) );

        var i = 0;
        var totalActions = actions.length;

        var doAction = function(){
            i++;

            var thisAction = actions.shift();

            if( thisAction != undefined ) {

                if( ! importerSettings.strings[ thisAction.action ] ) {
                    importerSettings.strings[ thisAction.action ] = '';
                }

                // Update the progress string
                $fr.find('.progress-message').html(
                    i + '/' + totalActions + ': ' +
                    importerSettings.strings[ thisAction.action ].replace( '%s', thisAction.text )
                );

                // Send this request to the server
                $.get(
                    importerSettings.url,
                    {
                        'action' : 'importer_action',
                        'importer_action' : thisAction.action,
                        'id' : thisAction.id,
                        'expires' : thisAction.expires,
                        'signature' : thisAction.signature,
                    },
                    function( r ){
                        $fr.find('.progress-bar-progress').width( ( i/totalActions*100 ) + "%" );

                        if( thisAction.action != 'finalize' ) {
                            doAction();
                        }
                        else {
                            // Display the final frame
                            $fr.find('.progress-message').hide();
                            $fr.find('.complete-message').show();
                            importerRunning = false;
                        }
                    }
                );
            }
        };
        doAction();
    } );

    // Confirm before exit if the importer is running
    window.onbeforeunload = confirmExit;
    function confirmExit() {
        if( importerRunning ) {
            return importerSettings.strings.confirm;
        }
    }
} );