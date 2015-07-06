$('document').ready(function() {
    
    $('.dm-tooltip').tooltip();
    $('.dm-popover').popover();
    
    $('.dm-check').click(function(){
        var chk = $('.dm-check').filter(':checked'); 
        if (chk.length > 0) {
            $('.dm-action').removeAttr('disabled');
        } else {
            $('.dm-action').attr('disabled', 'disabled');
        }
        if (chk.length > 1) {
            $('.dm-upload').attr('disabled', 'disabled');
        }
        var files = '<ul>';
        var fileids = [];
        chk.each(function(idx, val){
            id = $(val).val().substring(2);
            fileids.push(id);
            files += '<li>' + $('#fn' + id).html() + '</li>'; 
        });
        files += '</ul>';
        $('#dm-delete-files').html(files);
        $('#dm-delete-count').html(fileids.length);
        $('#dm-fileids').val(fileids);
        if (fileids.length > 0)
            $('#downloadzip').click(function() { 
                location.href = 'files/zip/' + encodeURI(String(fileids).replace(/,/g, ' ')) 
            });
        else
            $('#downloadzip').click(function(){});
    });
    
    if ($('#slideshow').length > 0)
        $('#slideshow').cycle({
            prev: '#prev',
            next: '#next',
            pause: 1,
            fx: 'scrollLeft',
            width: 1170
        });
    
    XDate.parsers.push(parseMySQL);
    
    if ($('#login-username').length > 0)
        $('#login-username').focus();
        
    if ($('#oldform').length > 0)    
        $('#contactform').modal('show');

});

function parseMySQL(str) {
    // parses dates like "yyyy-mm-dd hh:mm:ss"
    var parts = str.split(' ');
    var dateparts = parts[0].split('-');
    var timeparts = parts[1].split(':');
    if ((dateparts.length == 3) && (timeparts.length == 3)) 
        return new XDate(
            parseInt(dateparts[0]), // year
            parseInt(dateparts[0] ? dateparts[0]-1 : 0), // month
            parseInt(dateparts[2]), // day
            parseInt(timeparts[0]), // hours
            parseInt(timeparts[1]), // minutes
            parseInt(timeparts[2])  // seconds
        );
}

function show_cad_file(fileid, url) {
    filename = $('#fn' + fileid).html();
    $('#stl_file').html(filename);
	var canvas = document.getElementById('dm-canvas');
	var viewer = new JSC3D.Viewer(canvas);
	viewer.setParameter('InitRotationX', 0);
	viewer.setParameter('InitRotationY', 0);
	viewer.setParameter('InitRotationZ', 0);
	viewer.setParameter('ModelColor', '#CAA618');
	viewer.setParameter('BackgroundColor1', '#E5D7BA');
	viewer.setParameter('BackgroundColor2', '#383840');
	viewer.setParameter('RenderMode', 'textureflat');
	viewer.setParameter('MipMapping', 'on');
	viewer.setParameter('SceneUrl', url); // bank/Western_Bank.obj 
	viewer.init();
	viewer.update();
    $('#stlform').modal('show'); 
    set_viewed(fileid);
}

function show_image(fileid, url) {
    filename = $('#fn' + fileid).html();
    if (is_chrome)
        header = filename + ' (click image to download)';
    else
        header = filename + ' (right-click and select "Save as..." to download)';
    $('#image_file').html(header);
    $('#dm-image').html('<a href="' + url + '" download="' + filename + '"><img src="' + url + '" alt="' + filename + '" /></a>');
    $('#imageform').css({
        'width': '950px',
        'margin-left': function() {
            return -($(this).width() / 2);
        },
        'top': '40%'
    });
    $('#imageform').modal('show'); 
    set_viewed(fileid);
}

function show_doc(fileid, url) {
    filename = $('#fn' + fileid).html();
    $('#image_file').html(filename);
    $('#dm-image').html('<iframe src="http://docs.google.com/viewer?url=' + encodeURIComponent(url) + '&embedded=true" width="910" height="780" style="border: none;"></iframe>');
    $('#imageform').css({
        'width': '950px',
        'margin-left': function() {
            return -($(this).width() / 2);
        },
        'top': '30%'
    });
    $('#imageform').modal('show'); 
    set_viewed(fileid);
}

function show_versions(fileid) {
    if (fileid == 0) {
        var chk = $('.dm-check').filter(':checked');
        fileid = chk.first().val().substr(2); 
    }
    set_viewed(fileid);
    filename = $('#fn' + fileid).html();
    $('#version_file').html(filename);
    vertab = '';
    $.each(versions[fileid], function(key, v){
        created = new XDate(v.created);
        vertab += '<tr>';
        vertab += '<td>' + v.version + '</td>';
        vertab += '<td><a href="' + v.url + '">' + v.filename + '</a></td>';
        vertab += '<td>' + created.toString('MMM d, yyyy - HH:mm') + '</td>';
        if (v.firstname == '' || v.lastname == '')
            vertab += '<td>' + v.username + '</td>';
        else
            vertab += '<td>' + v.firstname + ' ' + v.lastname + '</td>';
        vertab += '</tr>';
    });
    $('#dm-versions').html(vertab);
    $('#dm-versionid').val(fileid);
    $('#versionform').modal('show');
}

function show_shares(fileid) {
    set_viewed(fileid);
    filename = $('#fn' + fileid).html();
    $('#share_file').html(filename);
    sharers = '';
    $.each(shares[fileid], function(key, s){
        sharers += '<li>';
        if (s.firstname || s.lastname)
            sharers += s.firstname + ' ' + s.lastname + ' (' + s.username + ')'; 
        else
            sharers += s.username;
        sharetime = new XDate(s.datetime);
        sharers += ' - [' + sharetime.toString('MMM d, yyyy - HH:mm') + ']</li>';
    });
    $('#dm-shares').html(sharers);
    $('#dm-shareid').val(fileid);
    $('#shareform').modal('show');
}

function show_notes(fileid) {
    set_viewed(fileid);
    filename = $('#fn' + fileid).html();
    $('#notes_file').html(filename);
    notetab = '';
    $.each(notes[fileid], function(key, n){
        added = new XDate(n.added);
        notetab += '<tr>';
        notetab += '<td width="130">' + added.toString('MMM d, yyyy - HH:mm') + '</td>';
        notetab += '<td width="110">' + n.owner + '</td>';
        notetab += '<td>' + n.note + '</td>';
        notetab += '</tr>';
    });
    $('#dm-notes').html(notetab);
    $('#dm-noteid').val(fileid);
    $('#notesform').modal('show');
}

function set_status(el) {
    var fileid = el.id.substring(3);
    set_viewed(fileid);
    var statusid = $(el).val();
    $.get('files/status/' + fileid + '/' + statusid);
    if (statusid == 2) 
        $('#row' + fileid).addClass('status-open');
    else
        $('#row' + fileid).removeClass('status-open');
    $('#status' + fileid).html(statuses[statusid].name + ' <b class="caret"></b>');
    $('#sdd' + fileid).hide();
    $('#status' + fileid).show();    
}

function hidedd(el) {
    var fileid = el.id.substring(3);
    $('#sdd' + fileid).hide();
    $('#status' + fileid).show();    
}

function set_viewed(fileid) {
    $.get('files/viewed/' + fileid);
    $('#row' + fileid).removeClass('not-viewed');
    return true;
}

function change_patient(fileid) {
    set_viewed(fileid);
    filename = $('#fn' + fileid).html();
    $('#patient_file').html(filename);
    patientname = $('#dm-patientname' + fileid).html(); 
    $('#patient').val(patientname);
    $('#dm-patientfileid').val(fileid);
    $('#patientform').modal('show');
}

function change_package(select, userid) {
    $.get('../users/updatepackage/' + userid + '/' + select.value);
}
