<?php

    require_once('lib/fortissimo.php');
    require_once('lib/EVEObject.php');

    # constellations contain a bunch of systems, and are contained themselves
    # within a region

    class EVEConstellation extends EVEObject {
        # create a new System class object to represent a particular system
        function EVEConstellation( $id ) {
            # parent object construction
            $this->EVEObject( 'constellations', 'constellationid', $id );
        }

        # pass through to parent's get name function
        function getName( $id = null ) {
            return parent::getName( 'constellations', 'constellationid', $id );
        }

        # pass through to parent's get id function
        function getName( $name = null ) {
            return parent::getId( 'constellations', 'constellationid', $name );
        }
    }

?>
