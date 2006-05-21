<?php

    require_once('lib/fortissimo.php');
    require_once('lib/EVEObject.php');

    // an alliance is a group of corporations.  they contain no data in and of
    // themselves, except for the current 'alliance note'

    class EVEAlliance extends EVEObject {
        # data of ours
        var $AllianceId = null;
        var $Note = null;

        # create a new Alliance class object to represent a particular player alliance
        function EVEAlliance( $id ) {
            # parent object construction
            $this->EVEObject( 'alliances', 'allianceid', $id );

            # pull out more information
            $this->AllianceId = $this->int_Obj->allianceid;
            $this->Note = $this->int_Obj->note;
        }

        # return our note
        function getNote() {
            return $this->Note;
        }

        # and now pass through to get id function
        function getId( $name = null ) {
            // the game sometimes uses None or Unknown to reference alliances
            // that don't exist.  we never want to autovivify those alliances,
            // so we return 0.
            if (! is_null( $name ) && ( ( $name == 'None') || ( $name == 'Unknown' ) ) ) {
           	    return 0;
            }

            // fall back to the parent functionality
            return parent::getId( 'alliances', 'allianceid', $name );
        }

        # return a link to an info page on this alliance
        function getLink( $id = null ) {
            return parent::getLink( 'alliances', 'allianceid', $id );
        }

    }

?>
