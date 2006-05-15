<?php

    require_once('lib/fortissimo.php');
    require_once('lib/EVEObject.php');
    require_once('lib/EVERegion.php');
    require_once('lib/EVEConstellation.php');

    // systems contain things, this is the level at which action happens
    // within this great universe of ours.  a system is in a constellation
    // which is then in a region.

    class EVESystem extends EVEObject {
        # data of ours
        var $RegionId = null;
        var $ConstellationId = null;
        var $Security = null;

        # create a new System class object to represent a particular system
        function EVESystem( $id ) {
            # parent object construction
            $this->EVEObject( 'systems', 'systemid', $id );

            # pull out more information
            $this->RegionId = $this->int_Obj->regionid;
            $this->Security = $this->int_Obj->security;
            $this->ConstellationId = $this->int_Obj->constellationid;
        }

        # name of the region we're in
        function getRegionName() {
            return EVERegion::getName( $this->RegionId );
        }

        # get the name of the constellation we're stuck inside
        function getConstellationName() {
            return EVEConstellation::getName( $this->ConstellationId );
        }

        # pass through to parent's get name function
        function getName( $id = null ) {
            return parent::getName( 'systems', 'systemid', $id );
        }

        # and now pass through to get id function
        function getId( $name = null ) {
            return parent::getId( 'systems', 'systemid', $name );
        }

    }

?>
