BioBigBoxApp.factory(
            "userService",
            ['$http', function($http) {
            	var user =  {userid: "",
								id: "",
								username: "", 
								firstname: "",
								lastname: "",
								typeid: "",
								smsnumber: "",
								smsnotifications: "",
								emailnotifications: ""};
				var userRetreived = false;
				var userTypesArray = Array();
            	var userService ={};

				userService.getUser = function(){
	            	if(!userRetreived){
	            		return userService.getUserData();
	            	}
	            	else
	            		return user;
	            };

	            userService.setUser = function(inUser){
	                	user = inUser;
	                	user.userid = inUser.id;

		        };

                userService.getEmptyUser = function(){
                    var u =  {userid: "",
                        id: "",
                        username: ""
                       };
                    return u;
                }

		        userService.getUserData = function(){
		        	return $http.get('/index.php/profile?json=true&rand='+Math.random())
					.then(function (result){
                            return result.data;
					});
				}
				return userService;
 }]);

BioBigBoxApp.factory(
	"msgService",
	function(){
		var msg = {
			type : "",
			text : ""
		}

		var msgService = {};

		msgService.setMsg = function(type, text){
			msg.type = type;
			msg.text = text;
		};

		msgService.getMsg = function(){
			return msg;
		}

		return msgService;
	}
);


BioBigBoxApp.factory("asDate", function () {
    return function (input) {
        return new Date(input);
    }
});



BioBigBoxApp.factory(
            "fileService",
            ['$http', function($http) {
            	var files =  Array();
				var filesRetreived = false;
				var fileStatuses = Array();
            	var fileService ={};

	            fileService.getfiles = function(){
	            	if(!filesRetreived){
	            		return filesService.getFileData();
	            	}
	            	else
	            		return files;
	            };

	            fileService.getStatuses = function(){
					return $http.get('/index.php/api/getStatuses')
					.then(function (result){
						return result.data;
					});
				};

	            fileService.setFile = function(inFile){
	            	if(inFile instanceof Array){
	            		files = inFile;
	            	}
	            	else{
	                	files.push(inFile);
	            	}
	            };

		        fileService.getFileData = function(){
		        	return $http.get('/index.php/files/?date='+new Date)
					.then(function (result){
						files = result.data.filesArray;
						fileStatuses = result.data.statuses;
						return result.data;
					});
				};

				fileService.updateStatus = function(fileId, statusId){
					return $http.get('/index.php/files/status/'+fileId+'/'+statusId)
						.then(function(result){
							});

				};

				fileService.addNote = function(fileId, note){
					var request = $http({
					method: "post",
					url: "index.php/files/addnote",
					headers: {'Content-Type': 'application/x-www-form-urlencoded'},
					data: 'fileid='+fileId+'&note='+note
					//serializeData($scope.user)//serializeData is a function defined in httpRequestHelpers.js
					});

					return request;
					/*
					request.success(function(html, $scope, status, headers){
					//$scope.userMsg = html.msg;
			 		});

			 		request.error(function(html){
					});
					*/
				};

				fileService.share = function(fileId, share_email){
					var request = $http({
					method: "post",
					url: "index.php/files/share",
					headers: {'Content-Type': 'application/x-www-form-urlencoded'},
					data: 'fileid='+fileId+'&share_email='+share_email
					});


					return request;
					//return $http.then(function(response);

/*					request.success(function(html, $scope, status, headers){
						var response = JSON.stringify(html.msg[1]);
							alert(response);
					});

			 		request.error(function(html){
						alert("Share failed. Please try again");
					});
					*/
				};


				fileService.setPatient = function(fileId, patient){
					var request = $http({
					method: "post",
					url: "index.php/files/patient",
					headers: {'Content-Type': 'application/x-www-form-urlencoded'},
					data: 'fileid='+fileId+'&patient='+patient
					//serializeData($scope.user)//serializeData is a function defined in httpRequestHelpers.js
					});

					request.success(function(html, $scope, status, headers){
			 			alert(JSON.stringify(html.msg));
					//$scope.userMsg = html.msg;
			 		});

			 		request.error(function(html){
						alert(JSON.stringify(html));
					});
				};

				fileService.deleteFile = function(fileId){
					var request = $http({
						method: "post",
						url: "index.php/files/delete",
						headers: {'Content-Type': 'application/x-www-form-urlencoded'},
						data: 'fileids='+fileId
					});

					var success =function(html, $scope, status, headers){
						return html;
					};

			 		var error = function(html){
						return html;
					};
					 return request.then(success, error);


				}



				return fileService;
			}]);


BioBigBoxApp.factory(
            "bgImageService",
            ['$http', function($http) {

            	var bgImageService = {};

            	bgImageService.getImages = function(){
            		return $http.get('/index.php/home/backgroundimages')
						.then(function(result){
							return result.data;
						});
            	};

            return  bgImageService;
	    }]);


BioBigBoxApp.factory(
    "adminService",
    ['$http', '$location', function($http,$location) {
        var adminService ={};

        adminService.getAdminData = function(){
            return $http.get('/index.php/admin/dashboard?json=true')
                .then(function (result){
                    return result.data;
                }, function(result){
					$location.path("/");
				});
        }

        adminService.getUser = function(){
            if(!userRetreived){
                return adminService.getUserData();
            }
            else
                return user;
        };

        adminService.setUser = function(inUser){
            user = inUser;
            user.userid = inUser.id;

        };

        adminService.getUserData = function(){
            return $http.get('/index.php/profile?json=true')
                .then(function (result){
                    return result.data;
                });
        }

		adminService.updateUserPackage= function(userid, packageid){
			return $http.get('/index.php/users/updatepackage/'+userid+'/'+packageid)
				.then(function (result){
					return result.data;
				});
		}
        return adminService;
    }]);