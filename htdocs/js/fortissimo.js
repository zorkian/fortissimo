/* the last kill in use */
//var lastKillId = 0;
//var oldClasses = new Array(8);

/* submit a damn form */
function submitForm(name) {
    if (! document.getElementById) { return true; }

    var obj = document.getElementById(name);
    if (! obj) { return true; }

    obj.submit();
}

/* set the classes on a row */
function appendRowClasses(killid, classn) {
    // make sure we can do this
    if (! document.getElementById) { return true; }

    // get object
    for (var i = 1; i <= 8; i++) {
        var obj = document.getElementById('t' + i + 'k' + killid);
        if (obj) {
            oldClasses[i-1] = obj.className;
            var str = new String(obj.className);
            str = str.replace("talt1", classn);
            str = str.replace("talt2", classn);
            obj.className = str;
            obj.className = obj.className + ' ' + classn;
        }
    }
}

/* restore classes on a row back to what they were */
function restoreRowClasses(killid) {
    for (var i = 1; i <= 8; i++) {
        var obj = document.getElementById('t' + i + 'k' + killid);
        if (obj) {
            obj.className = oldClasses[i-1];
        }
    }
}

/* function to show when a kill row is hovered over */
function doHoverKill(killid) {
    if (! document.getElementById) { return true; }

    if (lastKillId > 0) {
        restoreRowClasses(lastKillId);
    }

    appendRowClasses(killid, 'bright');
    lastKillId = killid;

    return true;
}

/* setup all of the functions for us to use */
function setupFunctions() {
    
}
