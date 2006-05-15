<?php

    require_once('lib/fortissimo.php');
    require_once('lib/EVEObject.php');

    # regions are very boring, they don't have much in them except for
    # a very basic name... yay

    class EVERegion extends EVEObject {
        # create a new System class object to represent a particular system
        function EVERegion( $id ) {
            # parent object construction
            $this->EVEObject( 'regions', 'regionid', $id );
        }

        # pass through to parent's get name function
        function getName( $id = null ) {
            return parent::getName( 'regions', 'regionid', $id );
        }

        # pass through to parent's get id function
        function getId( $name = null ) {
            return parent::getId( 'regions', 'regionid', $name );
        }

        # return a link to an info page on this region
        function getLink( $id = null ) {
            return parent::getLink( 'regions', 'regionid', $id );
        }
    }

?>
