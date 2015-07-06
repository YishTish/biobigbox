var BioBigBoxApp = angular.module('BioBigBox', ["ngRoute","ngCookies", "ui.bootstrap"]);

var mainDivDisplay = true;

BioBigBoxApp.config([
	'$routeProvider',function($routeProvider) {
		$routeProvider.
			/*when('/page/:name',{
				templateUrl: "html/contentDiv.html",
				controller:  ['$routeParams', '$scope', function($routeParams, $scope){
					$scope.url =  "html/"+$routeParams.name+".html";
					switch($routeParams.name){
						case 'faq':
							$scope.title = "FAQ";
							break;
						case 'about':
							$scope.title = "About Us";
							break;
						case 'contact':
							$scope.title = "Contact Us";
							break;
						case 'plans':
							$scope.title = "Plans";

					};

				}]
		}).*/
        when('/register',{
            controller: 'registerCtrl'
        }).
		when('/userData',{
			templateUrl: "html/contentDiv.html",
			controller: "userCtrl"
		}).
		when('/upload',{
			templateUrl: "html/contentDiv.html",
			controller:  ['$routeParams', '$scope', function($routeParams, $scope){
					$scope.url =  "html/uploadDiv.html";
					$scope.title = "Upload";
					}]

		})
		.when('/files',{
			templateUrl: "html/contentDiv.html",
			controller:  ['$scope', function($scope){
					$scope.url =  "html/files.html";
					$scope.title = "File List";
					$scope.listtoshow = "vault";
				}]

		})
		.when('/newpassword/:uuid/:userid',{
			templateUrl: "html/contentDiv.html",
			controller:  ['$scope', '$routeParams', '$http', '$location', function($scope,$routeParams,$http,$location){
						$scope.url =  "html/newPasswordForm.html";
						$scope.title = "New Password";
						$scope.uuid = $routeParams.uuid;
						$scope.userid = $routeParams.userid;

						$scope.resetPassword = function(userParams){
							console.log($scope.uuid);
							console.log($scope.userid);
							console.log(userParams);
							var formParams = "userid="+$scope.userid+"&uuid="+$scope.uuid+"&password="+userParams.password+"&retypepwd="+userParams.retypepwd;
							$http.post("/index.php/auth/setpassword",formParams,{"headers":{"Content-Type": "application/x-www-form-urlencoded"}}).success(
								function(html, status){
									alert(html[1]);
									$location.path("#");
								}).error(function(html){
									alert(html[1]);
									$location.path("#");
								});
						};

			}]
    	})
        .when('/admin',{
            templateUrl: "html/contentDiv.html",
            controller:  ['$scope', '$routeParams', '$http', '$location', function($scope) {
				$scope.url = "html/admin.html";
                $scope.title = "Admin Dashboard";
			}]
         })
        .when('/message/:status/:message',{
            templateUrl: "html/contentDiv.html",
            controller:  ['$scope','$routeParams', function($scope,$routeParams){
                $scope.url =  "html/message.html";
                $scope.title = $routeParams.status;
                $scope.message = $routeParams.message;
            }]

        })
	}]);

//We already have a limitTo filter built-in to angular,
//let's make a startFrom filter
BioBigBoxApp.filter('startFrom', function() {
    return function(input, start) {
    	if(input){
	    	start = +start; //parse to int
	        return input.slice(start);
    	}
    }
});


