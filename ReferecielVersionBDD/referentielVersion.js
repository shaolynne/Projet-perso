function init() {
    document.querySelector("table tr:first-child").addEventListener('click', menu);
    foreach(document.querySelectorAll("table tr td"),
        function(td) { td.addEventListener('dblclick', popup) });
    foreach(document.querySelectorAll("menu input[type='checkbox']"),
        function(checkbox) { checkbox.addEventListener('click', checkboxClik) });
    document.querySelector("menu").addEventListener("click",function(){event.stopPropagation()});
}
function checkboxClik(evt) {
    switchLane(evt.target.name,evt.target.checked)
}
function switchLane(laneInfo, state) {
    var laneTypeAndName = laneInfo.split('_');
    var laneType = laneTypeAndName[0];
    var laneName = laneTypeAndName[1];
    if( laneType == 'env') {
        if( laneName == 'ALL' ) {
            foreach(document.body.querySelectorAll('table.main th:nth-child(1n+3)'),
                function(elm) {elm.style.display = state ? '' : 'none';});
            foreach(document.body.querySelectorAll('table.main td:nth-child(1n+3)'),
                function(elm) {elm.style.display = state ? '' : 'none';});
            foreach(document.body.querySelectorAll('menu tr td:nth-child(1) li:nth-child(1n+3) input'),
                function(elm){ elm.checked = (state ? 'checked' : '')});
            return;
        }
        var elm = elementContainingText( document.body.querySelectorAll('table.main th' ), laneName );
        foreach(document.body.querySelectorAll('table.main tr th:nth-child('+(elm.cellIndex+1)+')'),
            function(elm) {elm.style.display = state ? '' : 'none';});
        foreach(document.body.querySelectorAll('table.main tr td:nth-child('+(elm.cellIndex+1)+')'),
            function(elm) {elm.style.display = state ? '' : 'none';});
    }
    if( laneType == 'mod') {
        if( laneName == 'ALL' ) {
            foreach(document.body.querySelectorAll('table.main tr:nth-child(1n+2)'),
                function(elm) {elm.style.display = state ? '' : 'none';});
            foreach(document.body.querySelectorAll('menu tr td:nth-child(2) li:nth-child(1n+2) input'),
                function(elm) {elm.checked = (state ? 'checked' : '')});
            return;
        }
        var elm = elementContainingText( document.body.querySelectorAll('table.main tr td:first-child' ), laneName );
        elm.parentElement.style.display = state ? '' : 'none';
    }
}

function elementContainingText(elms, txt) {
    for (var i=0;i < elms.length; i++) {
        var elm = elms[i];
        if( elm.textContent.trim() == txt ) {
            return elm;
        }
    }
}
function menu() {
    var menu = document.body.querySelector("menu");
    menu.style.display = menu.style.display != 'block' ? 'block' : 'none'
}
window.addEventListener('load', init)

function popup(evt) {
    var screen = document.body.querySelector("#screen");
    if( !screen ) {
        screen = document.createElement("div");
        screen.id='screen';
        screen.addEventListener('click', hidePopup, false);
        popup = document.createElement("div");
        popup.id='popup';
        screen.appendChild(popup)
        document.body.appendChild(screen);
    }
    var popup = document.body.querySelector("#popup");
    var prodCell = evt.target.parentElement.parentElement.querySelector("td:nth-child(2) div");
    var selectedColumnName = document.body.querySelector("table.main").querySelector("tr:nth-child(1) th:nth-child("+(evt.target.parentElement.cellIndex+1)+")").textContent;
    var table = '<table><tr><th>PROD</th><th>' + selectedColumnName + '</th></tr>' +
        '<tr><td>'+prodCell.innerHTML+'</td><td>' + evt.target.innerHTML + '</td></tr></table>';
    popup.innerHTML = table;
    screen.style.display='';
}
function hidePopup(evt) {
    if( evt.target.id == 'screen' )
        document.body.querySelector("#screen").style.display='none';
    return false;
}
function cellHeightChanged() {
    var val = document.body.querySelector("#cellHeight").value;
    console.log( val );
    console.log( parseInt(val) + "!=" +  NaN + " - " + (!isNaN(val)) )
    foreach(document.body.querySelectorAll("table.main tr td div"),
        function(elm) {elm.style.height = (!isNaN(val) ? 16*parseInt(val) + 'px' : 'auto')});
    console.log( document.body.querySelector("table.main tr td div").style.height );
}
function foreach(elms, fct) {
    console.log( elms )
    console.log( fct )
    for(var i=0; i< elms.length; i++) {
        console.log( elms[i] )
        console.log( fct.call )
        fct.call(this,elms[i]);
    }
}