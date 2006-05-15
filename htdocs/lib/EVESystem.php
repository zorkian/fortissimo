<?php

    require_once('lib/fortissimo.php');
    require_once('lib/EVEObject.php');
//    require_once('lib/EVERegion.php');

    class EVESystem extends EVEObject {
        # data of ours
        var $RegionId = null;
        var $ConstellationId = null;

        # create a new System class object to represent a particular system
        function EVESystem( $systemId ) {
            # parent object construction
            $this->EVEObject( 'systems', 'systemid', $systemId );

            # pull out more information
            $this->RegionId = $this->int_Obj['regionid'];
            $this->ConstellationId = $this->int_Obj['constellationid'];
        }

        # these are methods that can be called without having an actual system,
        # so these are class methods for helping people do things
        function getRegionName() {
//            return EVERegion::getRegionName( $this->RegionId );
        }
    }

?>
