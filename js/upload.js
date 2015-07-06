        var filecount = 0;
        var xhrs = [];
    
        function createCORSRequest(method, url) {
          var xhr = new XMLHttpRequest();
          if ("withCredentials" in xhr) {
            xhr.open(method, url, true);
          } else if (typeof XDomainRequest != "undefined") {
            xhr = new XDomainRequest();
            xhr.open(method, url);
          } else {
            xhr = null;
          }
          return xhr;
        }
        
        function handleFileSelect(evt) {
            
          if (!user) {
              if ($('#owner').val() == '') {
                alert('You must enter your email address.');
                return;
              } 
          }

          var files = evt.target.files; 
        
          for (var i = 0, f; f = files[i]; i++) {
            addFileHTML(filecount, f.name.replace('&', '-'));
            setProgress(filecount, 0, 'Queued.');
            uploadFile(f, filecount);
            filecount++;
          }
        }
        
        function addFileHTML(num, filename) {
            var uploadWrapper = document.getElementById('uploadWrapper');
            uploadWrapper.className = "active";
            var statuses = document.getElementById('statuses');
            var div = document.createElement('div');
            var label = document.createElement('label');
            label.innerHTML = filename.replace('&', '-') + ':';
            div.appendChild(label);
            var status = document.createElement('span')
            status.setAttribute('id', 'status' + num);
            div.appendChild(status);
            var percent = document.createElement('span');
            percent.setAttribute('id', 'percent' + num);
            percent.className = 'percent';
            var pbar = document.createElement('span');
            pbar.setAttribute('id', 'progress_bar' + num);
            pbar.className = 'progress_bar';
            pbar.appendChild(percent);
            div.appendChild(pbar);
            var cancel = document.createElement('span');
            cancel.className = 'badge badge-important';
            cancel.setAttribute('id', 'cancel' + num);
            var icon = document.createElement('i');
            icon.className = 'icon-remove icon-white';
            cancel.appendChild(icon);
            div.appendChild(cancel);
            statuses.appendChild(div);
        }
        
        /**
         * Execute the given callback with the signed response.
         */
        function executeOnSignedUrl(file, num, callback) {
          var xhr = new XMLHttpRequest();
          xhr.open('GET', '/upload/signput?name=' + encodeURIComponent(file.name) + '&type=' + (file.type ? file.type : 'application/octet-stream'), true) + '&size=' + file.size + '&email=' + $('#owner').val();
        
          // Hack to pass bytes through unprocessed.
          xhr.overrideMimeType('text/plain; charset=x-user-defined');
        
          xhr.onreadystatechange = function(e) {
            if (this.readyState == 4 && this.status == 200) {
              callback(decodeURIComponent(this.responseText));
            } else if(this.readyState == 4 && this.status != 200) {
              setProgress(num, 0, 'Could&nbsp;not&nbsp;contact&nbsp;signing&nbsp;script.&nbsp;Status:&nbsp;' + this.status);
            }
          };
        
          xhr.send();
        }
        
        function uploadFile(file, num) {  
          executeOnSignedUrl(file, num, function(signedURL) 
          {
            if (signedURL.length == 0)
                setProgress(num, 0, 'Could&nbsp;not&nbsp;upload&nbsp;-&nbsp;quota&nbsp;exceeded.', 2);
            else
                uploadToS3(file, num, signedURL);
          });
        }
        

        /*
        function convertToModelFile(uploadedFile){
          console.log(uploadedFile);
          var ts = new Date(uploadedFile.lastModified);
          var file ={
            id: uploadedFile.fileid,
            filename: uploadedFile.name,
            created: ts.toLocaleString(),
            status: "",
            url: uploadedFile.url,
            size: uploadedFile.size
            };
          return file;
        }
        */
        
        /**
         * Use a CORS call to upload the given file to S3. Assumes the url
         * parameter has been signed and is accessible for upload.
         */
        function uploadToS3(file, num, url) { 
          $("body").addClass("uploadBG");
          var xhr = createCORSRequest('PUT', url);
          if (!xhr) { 
            setProgress(num, 0, 'CORS&nbsp;not&nbsp;supported');
          } else { 
            xhr.onload = function() {
              if(xhr.status == 200) { 
                // now call the server with the file info to store in the db.
                var postfields = {
                    filename: encodeURIComponent(file.name),
                    size: file.size,
                    mimetype: (file.type ? file.type : 'application/octet-stream'),
                    email: ($('#owner').length > 0 ? $('#owner').val() : ''),
                    sharee: ($('#sharee').length > 0 ? $('#sharee').val() : '')
                };
                $.post('/upload/storefileinfo',
                       postfields,
                       function(ret) {
                            $('#cancel' + num).hide();
                            if (ret.length > 0) {
                                file = ret[0];
                                var message = 'File&nbsp;added&nbsp;to&nbsp;your&nbsp;BioBigBox';
                                if ($('#sharee').length > 0) message += '&nbsp;and&nbsp;shared';
                                setProgress(num, 100, message + '.', true);
                                console.log(file);
                                angular.element($("#uploadWrapper")).scope().addFileToQueue(file);
                            } else {
                                var message = 'Failed&nbsp;to&nbsp;add&nbsp;file&nbsp;to&nbsp;your&nbsp;BioBigBox.';
                                setProgress(num, 0, message, 2);
                            }
                       });
               } else { 
                setProgress(num, 0, 'Upload&nbsp;error:&nbsp;' + xhr.status);
              }
            };
        
            xhr.onerror = function(e) { 
              setProgress(num, 0, 'XHR&nbsp;error:&nbsp;' + e.type);
            };
        
            xhr.upload.onprogress = function(e) { 
              if (e.lengthComputable) { 
                var percentLoaded = Math.round((e.loaded / e.total) * 100);
                setProgress(num, percentLoaded, (percentLoaded == 100 ? 'Saving.' : 'Uploading.'));
              }
            };
        
            xhr.setRequestHeader('Content-Type', (file.type ? file.type : 'application/octet-stream'));
            xhr.setRequestHeader('x-amz-acl', 'private');
        
            xhrs[num] = xhr;
            var cancel = document.getElementById('cancel' + num)
            cancel.onclick = function() {
                num = this.getAttribute('id').substring(6);
                xhrs[num].abort();
                setProgress(num, 0, 'Cancelled.', 2);
            };
        
            xhr.send(file);
          }
        }
        
        function setProgress(num, percent, statusLabel, full) { 
          document.getElementById('progress_bar' + num).className = 'loading';
          var progress = document.getElementById('percent' + num);
          progress.style.width = percent + '%';
          progress.innerHTML = statusLabel + '&nbsp;' + percent + '%';
          if (full) {
            $('#uploadWrapper' + num).addClass('full');
            //$('#progress_bar' + num).addClass('full');
            progress.innerHTML = '&nbsp;' + statusLabel;
          } else {
            progress.innerHTML = '&nbsp;' + statusLabel + '&nbsp;' + percent + '%';
          }
        }    

        $('#files').bind('change', handleFileSelect);
