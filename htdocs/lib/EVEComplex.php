<?php

    require_once('lib/fortissimo.php');
    require_once('lib/EVEObject.php');
	require_once('lib/EVESystem.php');
	require_once('lib/EVECorporation.php');

    # a complex is a thing in space that people can run, it's like a dungeon or
	# an instance from other games

    class EVEComplex extends EVEObject {
		# class data
		var $Rating = null;
		var $SystemId = null;
		var $CorpId = null;

        # create a new Complex class object to represent a particular complex
        function EVEComplex( $id ) {
            # parent object construction
            $this->EVEObject( 'plexes', 'plexid', $id );

			# propogate other internal data
			$this->Rating = $this->int_Obj->rating;
			$this->SystemId = $this->int_Obj->systemid;
			$this->CorpId = $this->int_Obj->corpid;
        }

        # pass through to parent's get name function
        function getName( $id = null ) {
            return parent::getName( 'plexes', 'plexid', $id );
        }

        # pass through to parent's get id function
        function getId( $name = null ) {
            return parent::getId( 'plexes', 'plexid', $name );
        }

        # return a link to an info page on this complex
        function getLink( $id = null ) {
            return parent::getLink( 'plexes', 'plexid', $id );
        }

		# return our rating
		function getRating() {
			return $this->Rating;
		}

		# return id of system we're in
		function getSystemId() {
			return $this->SystemId;
		}

		# return name of the system we're in
		function getSystemName() {
			return EVESystem::getName( $this->SystemId );
		}

		# return id of corp that owns us
		function getCorpId() {
			return $this->CorpId;
		}

		# return name of the controlling corporation
		function getCorpName() {
			return EVECorporation::getName( $this->CorpId );
		}

		# return object for system we're in
		function getSystem() {
			return EVESystem->new( $this->SystemId );
		}

		# return object for corp that controls us
		function getCorp() {
			return EVECorporation->new( $this->CorpId );
		}

		# generic searcher, can find complexes given some sort of criteria such as
		# a system, a rating, a region, or similar
		function findComplexes( $search ) {
			$res = array();

			# if they gave us null criteria, fail
			if ( is_null( $search ) ) {
				return $res;
			}

			# we're going to need these arrays to do the query
			$where = array();
			$bind = array();

			# look some things up if needed
			if ( $system_name = $search['system'] ) {
				$search['system_id'] = EVESystem::getId( $system_name );
			}
			if ( $region_name = $search['region'] ) {
				$search['region_id'] = EVERegion::getId( $region_name );
			}

			# now assemble the clauses
			if ( $system_id = $search['system_id'] ) {
				array_push( $where, 'p.systemid = ?' );
				array_push( $bind, $system_id );
			}
			if ( $region_id = $search['region_id'] ) {
				array_push( $where, 'r.regionid = ?' );
				array_push( $bind, $region_id );
			}
			if ( $rating = $search['rating'] ) {
				array_push( $where, 'p.rating = ?' );
				array_push( $bind, $rating );
			}

			# if they have no criteria then handle that edge case
			if ( count( $where ) == 0 ) {
				array_push( $where, '1' );
			}

			# create the SQL and run it
			global $ft;
			$whereclause = implode( FIXME: whatever this should be );
			$rows = $ft->dbh->_select_column( "
					SELECT p.plexid
					FROM tbl:plexes p, tbl:systems s
					WHERE $whereclause
				", $bind );

			# now load into useful information
			if ( is_null( $rows ) || count( $rows ) == 0 ) {
				return $res;
			}
			foreach ( $rows as $id ) {
				array_push( $res, EVEComplex->new( $id ));
			}
			return $res;
		}
    }

?>
