<?php

    require_once('lib/fortissimo.php');

    # this is a generic class that represents an object, where an object
    # in this case is defined as a single row pulled from the database..
    # good candidates for EVEObject representation are systems, regions,
    # items, things that don't require multiple SQL queries to load
    class EVEObject {
        # data of ours
        var $Id = null; # our unique id
        var $Name = null; # our unique name
        var $Table = null; # name of the table to find data in
        var $PrimaryKeyCol = null; # name of the primary key column
        var $int_Obj = null; # our internal row array

        # create a new System class object to represent a particular system
        function EVEObject( $table, $primkeycol, $id ) {
            global $ft;

            # load the item from the database
            $this->Id = $id;
            $this->Table = $table;
            $this->PrimaryKeyCol = $primkeycol;

            # now let's load up the internal object
            $this->int_Obj =
                $ft->dbh->_select_row_as_object(
                    "SELECT * FROM tbl:$table WHERE $primkeycol = ?",
                    array( $id )
                );
            if ( is_null( $this->int_Obj ) ) {
                # FIXME: flag an error condition here
                exit(0);
            }

            # pull out some useful data
            $this->Name = $this->int_Obj->name;
        }

        # get the name of something
        function getName( $table, $primkeycol, $id ) {
            global $ft;

            # if no id, use our name
            if ( ! is_null( $this ) ) {
                return $this->Name;
            }

            # okay, so let's load it
            $row = $ft->dbh->_select_row_as_object(
                "SELECT * FROM tbl:$table WHERE $primkeycol = ?",
                array( $id )
            );
            if ( is_null( $row ) ) {
                return Null;
            }
            return $row->name;
        }

    }

?>
