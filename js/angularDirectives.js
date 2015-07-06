BioBigBoxApp.directive('bioBigBoxHeader', function(){
	return{
		restrict: 'AEC',
		replace: 'true',
		templateUrl: 'html/header.html',
		controller: 'headerCtrl'
	};
});

BioBigBoxApp.directive('bioBigBoxFooter', function(){
	return{
		restrict: 'AEC',
		replace: 'true',
		//templateUrl: 'html/footer.html'
		templateUrl: ''
	};
});

BioBigBoxApp.directive('contentTop', function(){
	return{
		restrict: 'AEC',
		replace: 'true',
		templateUrl: 'html/contentWrapperTop.html'
	};
});

BioBigBoxApp.directive('formMessage', ['$timeout', '$rootScope', function($timeout, $rootScope){
	return{
		restrict: 'AEC',
		replace: 'true',
		template: "<div ng-class=\"{errorMessage : type=='error' , successMessage : type=='success'}\">{{ message }} </div>",
		scope: {
			message : '@',
			type : '@'
		},
		link: function(scope, elem, attr) {
			$rootScope.$on("message_recieved",function() {
				$timeout(function() {
					scope.message="";
				}, 5000);
			});
		}
	};
}]);

BioBigBoxApp.directive('fileList',function(){
	return{
		restrict: 'AEC',
		replace: 'true',
		templateUrl: 'html/fileList.html',
		scope:{
			listtoshow: '@'
		},
		controller: 'fileCtrl'
	};
});
//}
BioBigBoxApp.directive('del',['$modal', function($modal){
	return{
		restrict: 'A',
		scope: {
			fileId: '@'
		},
		link: function(scope, elem, attr){
			elem.click(function (){
				var modalInstance = $modal.open(
					{
						templateUrl: '/html/modals/confirmDelete.html',
						controller: modalCtrl,
						fileId: scope.fileId,
						size: 'sm',
						resolve: {
							fileId : function(){
								return scope.fileId;
							}
						}
					}
				);
				modalInstance.result.then(function(response){
					elem.parent().parent().parent().hide();
				});
			});
		}
}}]);


BioBigBoxApp.directive('updatevalue', function(){
	return{
		restrict: 'A',
		link: function(scope,elem,attr){
				elem.click(function(){
					elem.parent().removeClass("displayingDiv");
					elem.parent().addClass("editingDiv");
				})
		}
			//console.log(attr);
		//}
	}
});

BioBigBoxApp.directive('viewvalue', function(){
	return{
		restrict: 'A',
		//controller: 'fileCtrl',
		link: function(scope,elem,attr){
				elem.bind('blur',function(){
					elem.parent().removeClass("editingDiv");
					elem.parent().addClass("displayingDiv");
				}),
				elem.bind('keyup',function(key){
					if(key.keyCode==13)
					{
						elem[0].blur();
					}
					if(key.keyCode==27)
					{
						elem[0].value="";
						elem.parent().removeClass("editingDiv");
						elem.parent().addClass("displayingDiv");
					}
				})
		}
			//console.log(attr);
		//}
	}
});


BioBigBoxApp.directive('addnoteelement', function(fileService){
	return{
		restrict: 'A',
		scope: {
			fileId : '@'
		},
		link: function(scope,elem,attr){
				elem.bind('blur',function(){
					var noteContent = elem[0].value;
					fileService.addNote(scope.fileId, noteContent).then(function(res){
						if(res.data.status=="success") {
							var newElement = "<i class=\"fa fa-file-o\"  notevalue=\""+noteContent+"\" tooltip=\"{{note.note}}\" title=\""+noteContent+"\"> </i>";
							elem.parent().append(newElement);
						}
						alert(res.data.msg);
						attr.$$element[0].value="";
					})


					//elem.parent().append("<i class=\"fa fa-file-o\" title=\""+elem.context.value+"\"> </i>");
				})
		}
			//console.log(attr);
		//}
	}
});

BioBigBoxApp.directive('addShare', function(fileService){
	return{
		restrict: 'A',
		scope: {
			fileId : '@'
		},
		link: function(scope,elem,attr){
				elem.bind('blur',function(){
						var emailAddress = elem[0].value;
						console.log(attr.$$element[0].value);
						fileService.share(scope.fileId, emailAddress).then(function(res){
							if(res.data.status=="success")
								elem.parent().append("<i class=\"fa fa-user\" title=\""+elem.context.value+"\"> </i>");
							alert(res.data.msg);
							file.newShare = "";
							attr.$$element[0].value="";
						})
				});
		}
			//console.log(attr);
		//}
	}
});


BioBigBoxApp.directive('updatePackage', function(adminService){
	return{
		restrict: 'A',
		scope:{
			userId: '@'
		},
		link: function(scope,elem,attr){
			elem.bind('change',function(){
				adminService.updateUserPackage(scope.userId, elem[0].value).then(
					function(result){
						//alert("package updated successfully");
					}
				);

			});
		}
		//console.log(attr);
		//}
	}
});
BioBigBoxApp.directive('emailInput', function(){
	return{
		restrict: 'A',
		link: function(scope,elem,attr) {
			elem.bind('blur', function () {
				scope.checkEmail=true;
				console.log(elem[0].value)
			});
			elem.bind('focus', function () {
				scope.checkEmail=false;
			});
		}
	};
});

/*BioBigBoxApp.directive('modifyTab', function(){
	return{
		restrict: 'A',
		scope: {
			clicked: '@'
		},
		link: function(scope, elem, attr){
			elem.click(function(){
				if(clicked==true){
					elem.className = ""
				}
			})
		}
	}

}
);*/