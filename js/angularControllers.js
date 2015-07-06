BioBigBoxApp.controller('mainCtrl', function($scope, $location, bgImageService){

	$scope.dropDownLinks = [
        { title: "Pricing", link: "pricing", text: "Pricing"},
        { title: "Contact-Us", link: "contact", text: "Contact Us"},
        { title: "Terms", link: "terms", text: "Terms"},
        { title: "FAQ", link: "faq", text: "FAQ"}
	];

	$scope.displayDiv = true;
	
	$scope.title = "";


	

	setupBgImage = function(img){
		$scope.bgImage = img;
	}

	$scope.getBgImage = function(){
		return $scope.bgImage();
	}
	/*
	$scope.showCenterDiv = function(title){
		$scope.displayDiv = true;
		$scope.title = title;
	}*/

	$scope.hideCenterDiv = function(){
		$location.path("/");
	}

	$scope.centerDivCurrent = function(){
		return $scope.displayDiv;
	}
});


BioBigBoxApp.controller('registerCtrl', function($scope){
    loadDiv("registerForm.html");
    $scope.showForm();

	function loadDiv(fileName){
		if(!fileName || formLoaded == fileName ){
			$scope.showForm = function() {
				formLoaded = "";
				return  "";
			}
		}
		else{
			$scope.showForm = function() {
				formLoaded = fileName;
				return  "html/"+fileName;
			}

		}
	};

});


BioBigBoxApp.controller('userCtrl', function($rootScope, $scope, $http, $location, transformRequestAsFormPost, userService, msgService){

	$scope.title =  "User Data";
	$scope.url = "html/detailsForm.html";
	userService.getUserData().then(function(data){
		if(data.user != null){
			$scope.user=data.user;
			$scope.userTypesArray = Array();
			for(userType in data.usertypes){
				$scope.userTypesArray.push(data.usertypes[userType]);
			}
		}
		else $scope.user="";
	});

	$scope.message = msgService.getMsg();

	setMsg = function(msg){
		$rootScope.$broadcast("message_recieved");
		msgService.setMsg(msg[0],msg[1]);
		$scope.message = msgService.getMsg();
	}


	$scope.receiveSms = function(){
		if($scope.user){
			return ($scope.user.smsnotifications==true);
		}
		return false;
	};
	
	$scope.receiveEmail = function(){
		if($scope.user)
			{
				return ($scope.user.emailnotifications==true);
			}
		return false;
	};

	$scope.getUserData = function(){
		return $scope.user;
	};
	
	//$scope.getUserata();
	$scope.showValidationMessages = false;

	$scope.saveProfile = function(){
		userService.setUser($scope.user);
		var request = $http({
				method: "post",
				url: "index.php/users/saveProfile",
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				data: serializeData($scope.user)//serializeData is a function defined in httpRequestHelpers.js
			});

		 request.success(function(html, $scope, status, headers){
		 		setMsg(html.msg);
				//$scope.userMsg = html.msg;
		 });

		 request.error(function(html){
				alert(JSON.stringify(html));
			});
		}
		

	

});

BioBigBoxApp.controller('headerCtrl', function($scope, $http, userService, $location, msgService){
	formLoaded = false;
	$scope.userLoggedIn = false;
	$scope.userTitle = "";
	$scope.forgotPasswordText = "Forgot Password?";
	$scope.selectedTab = "";


	$scope.isAdmin = false;

	$scope.showLoginForm = function(){
		loadDiv("loginForm.html")
		$scope.selectedTab = "login";
	};

	
	$scope.showRegisterForm = function(){
		loadDiv("registerForm.html");
		$scope.selectedTab = "register";
	};
	
	$scope.closeDiv = function(){
		loadDiv();
	};

	function loadDiv(fileName){
		if(!fileName || formLoaded == fileName ){
			$scope.showForm = function() {
				formLoaded = "";
				return  "";
			}
		}
		else{
			$scope.showForm = function() {
				formLoaded = fileName;
				return  "html/"+fileName;
			}

		}
	};

	$scope.getUserName = function(){
		if($scope.user == undefined || $scope.user.username == undefined || $scope.user.username==""){
			return;
		}
		var length = $scope.user.username.length
		if(length > 30){
			return $scope.user.username.substr(0,10)+"..."+$scope.user.username.substr(-length,10);
		}
		else{
			return $scope.user.username;
		}
	};

	setupScopeUser = function(){
		userService.getUserData().then( function(data){
			if(data.user){
				$scope.user = data.user;
				$scope.userLoggedIn = true;
				if($scope.user.typeid == 1){
					$scope.isAdmin = true;
				}

                $scope.userTitle = $scope.user.firstname;
				//updateUserTitle();
			}
			else {
                $scope.user = {id:"",username:""};
				preLoadForms();
            }
		});
	};

	preLoadForms = function(){
		var path = $location.path();
		if(path.substr(path.lastIndexOf("/")+1)=="register"){
		//if(path.indexOf("register") > 0){
			$scope.showRegisterForm();
		}
		else if(path.substr(path.lastIndexOf("/")+1)=="login"){
		//else if(path.indexOf("login") > 0){
			$scope.showLoginForm();
		}
	}


	updateUserTitle = function(){
		if($scope.user.firstname !== undefined && $scope.user.firstname!=""){
				$scope.userTitle = $scope.user.firstname;
			}
			else{
				$scope.userTitle = $scope.user.username;
			}
	}

	$scope.login = function(){
			$http.post('/login',$scope.user).success(
			function(html, status){
                $scope.showForm = "";
				setupScopeUser();
                $scope.loggedIn = true;
				$location.path("/files");
//				msgService.setMsg("success","Login Successful");
                console.log("User:" +$scope.userLoggedIn);
                console.log($scope.user);

			}).error(function(html){
				$scope.error =  html.error;
			});
	};

	$scope.logout = function(){
		$http.post('/logout').success(
			function(html, status){
				$scope.user = userService.getEmptyUser();
                $scope.userLoggedIn = false;
				$scope.isAdmin = false;
				$scope.closeDiv();
				$location.path("/");
				msgService.setMsg("success","You have logged out successfully");
				$location.path("/");
			}).error(function(html){
				msgService.setMsg("error","Log out failure. Please try again");
			})
	};
	

	$scope.showUploadDiv = function(action){
		//$location.path("/uploadDiv");
        if(action=="send"){
		    loadDiv("shareDiv.html");
			$scope.selectedTab = "send";
		}
        else
         {
         	loadDiv("uploadDiv.html");
        	$scope.selectedTab = "upload";
         }   
	};


	$scope.register = function(){
		console.log($scope.user);
		if($scope.user.email!=$scope.user.emailConfirm){
			alert("The email addresses you've provided to not match: " +$scope.user.email+", "+$scope.user.emailConfirm);
			return;
		}
		$scope.user.password_2 = $scope.user.password;
		$http.post('/register',$scope.user).success(
			function(html, $cookies){
				$scope.user.username=html.user.username;
				$scope.user.id=html.user.id;
				console.log($scope.user);
				console.log(html);
				$scope.showForm = "";
				updateUserTitle();
				$scope.login();
			}).error(function(html){
				$scope.user.error =  html.responseMessage;//{error: html.responseMessage};
			});
	};

	$scope.forgotPassword = function(){
		if($scope.user.username==undefined || $scope.user.username==""){
			$scope.forgotPasswordError = true;
			return;
		}
		$scope.forgotPasswordText = "PLease wait...";
		$http.get("/forgotpwd?username="+$scope.user.username)
			.success(function(data, status, headers, config){
				alert(data[1]);
				$scope.forgotPasswordText = "Forgot Password?";
			}).
			error(function(data, status, headers, config){
				alert(data[1]);
				$scope.forgotPasswordText = "Forgot Password?";
			});
	}

	$scope.getForgotPasswordText = function(){
		return $scope.forgotPasswordText;
	}
	
	setupScopeUser();
});




BioBigBoxApp.controller('fileCtrl', function($scope, $http, $location, fileService,msgService, userService, $filter, $modal){
	
	userService.getUser().then(function(data){
		if(data == undefined || data.length==0){
			$scope.loggedIn = false;
		}
		else{
				$scope.user = data.user;
				$scope.loggedIn = true;
		}
	});


	fileService.getStatuses().then(function(data){
		$scope.statuses = Array();
		for(status in data){
			$scope.statuses.push(data[status]);
		}
	});

	
	tempFiles = Array();

	$scope.getNoteContent = function(){
		return $scope.fileList[0].notes[0].note;
	}


	//listtoshow is sent in the filelist directive definition. Its either temp (for uploaded files) or vault (for logged in users, who have files)
	if($scope.listtoshow == "temp"){
		$scope.fileList = tempFiles;
	}

	else if($scope.listtoshow == "vault"){
		fileService.getFileData().then(function(data){
		$scope.files = data.filesArray;
		$scope.patients = data.patients;

		for(file in $scope.files){
			if($scope.files[file].patientid && $scope.files[file].patientid != 0){
				if(file.status==null || file.status==""){
					file.status = 0;
				}
				var id = $scope.files[file].patientid
				$scope.files[file].patientName = data.patients[id];
				$scope.files[file].removed = false;
			}
		}
			
			$scope.fileList = $scope.files;
		});
	}

	$scope.startingPoint = 0;
	$scope.numPerPage = 5;

	
	$scope.addFileToQueue = function(file){
		if(file["size"] > 0){
			tempFiles.push(file);
			$scope.$apply();
		}
		else{
			console.log("No temporary files");
		}
	};

	$scope.getTempFiles = function(){
		return tempFiles;
	};

	$scope.formatFileSize = function(file){
		var fileSize = file.size;
		return (fileSize / 1024).toFixed(2)+" KB";
	};

	$scope.deleteFile = function(fileIndex){
		var fileId = $scope.fileList[fileIndex].id;
		fileService.deleteFile(fileId);
	};

	$scope.hasFiles = function(){
		return true;
		if($scope.fileList) return true;
		else return false;
	}

	$scope.updateStatus = function(fileId, statusId){
		return fileService.updateStatus(fileId, statusId);
		//console.log("file Id: "+fileId+", Status: "+statusId);
	}

	$scope.setPatient = function(fileId, patient){
		console.log(patient);
		//return fileService.setPatient(fileId, patient);
		//console.log("file Id: "+fileId+", Status: "+statusId);
	}


	/*
	$scope.addNote = function(fileId, note){
		return fileService.addNote(fileId, note);
	}

	$scope.share = function(fileId, share_email){
		fileService.share(fileId, share_email).then(function(res){
			alert(res.data.msg);
		})


	}*/

	$scope.confirmDelete = function(index, fileId){
		/*
		var modalInstance = $modal.open(
				{
					templateUrl: '/html/modals/confirmDelete.html',
					controller: modalCtrl,
					fileId: fileId,
					size: 'sm',
					resolve: {
						fileId : function(){
							return fileId;
						}
					}
				}
			);
		modalInstance.result.then(function(response){
			$scope.fileList.splice(index,1);
	
		});
*/
	};


	$scope.fileExists = function(id){
		for(file in $scope.fileList){
			if($scope.fileList[file].id == id){
				return true;
			}
		}
		return false;
	}
	

});

var modalCtrl = function($scope, $modalInstance, fileId, fileService){
	$scope.fileId = fileId;

	$scope.deleteFile = function(fileId){
		 $modalInstance.close(fileService.deleteFile(fileId));
	};
}


BioBigBoxApp.controller('adminCtrl', function($scope, $location, adminService) {

    adminService.getAdminData().then(function(data){
        var keys = ['newusers','users','usertypes','packages','filecounts'];
        for(key in keys) {
			$scope[keys[key]] = data[keys[key]];
		};
		for(user in $scope.users){
			$scope.users[user].packageName = $scope.packages[$scope.users[user].packageid];
		}
    });


});