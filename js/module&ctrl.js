var nesApp = angular.module('nesApp', []);
			
			nesApp.controller('mainCtrl', function($scope,$timeout,$window){
				$scope.message = 'Здравствуйте, я буду оповещать Вас о совершенных действиях и давать подсказки';
				$scope.tit = "adressbook";
				$scope.list = [];
				$scope.cur = { np:'', str:'', h:'', ap:''};
				$scope.openl = function(img,obj,type){
					if (typeof(obj.id) != 'undefined')
					{
						var dest;
						switch(type){
							case 'main':{dest = $scope.list;break;}
							case 'locality':{dest = obj.streets;break;}
							case 'street':{dest = obj.homes;break;}
							case 'home':{dest = obj.aparts;break;}
						}
						if (typeof(dest[0]) != 'undefined')
						{
							img.img = 'images/naviopen.png';
							$scope.message = 'Закрываем: '+obj.name;
							switch(type){
								case 'locality':{obj.streets = [];break;}
								case 'street':{obj.homes = [];break;}
								case 'home':{obj.aparts = [];break;}
							}
						} else {
							if (type != 'main') $scope.message = 'Открываем: '+obj.name;
                          	var xhr = new XMLHttpRequest();
							var url = 'http://kibilizard.org/nes/open.php?type='+type+'&id='+obj.id;
                          	xhr.open('GET', url, false);
							xhr.send();
							if (xhr.status != 200) {
								console.log( xhr.status + ': ' + xhr.statusText );
							} else {
								switch(type){
									case 'main':{$scope.list = JSON.parse(xhr.responseText);break;}
									case 'locality':{obj.streets = JSON.parse(xhr.responseText);break;}
									case 'street':{obj.homes = JSON.parse(xhr.responseText);break;}
									case 'home':{obj.aparts = JSON.parse(xhr.responseText);break;}
								}
								img.img = 'images/naviclose.png';
							}
						}
					}
				}
				$scope.undol = function(cont,obj,type){
					if (typeof(obj.id) != 'undefined')
					{
						$scope.message = 'Удаляем: '+obj.name+' со всеми вложениями!';
						var id = obj.id;
						for (var i=0; i < cont.length; i++)
						{
							if (cont[i].id == id)
								break;
						};
						cont.splice(i,1);
						var xhr = new XMLHttpRequest();
						var url = 'http://kibilizard.org/nes/delette.php?type='+type+'&id='+id;
						xhr.open('GET', url, false);
						xhr.send();
					}
					else 
					{
						obj.name = 'введите...';
					}
				}
				$scope.onfocus = function(cont,obj,type){
					$scope.message = 'Введите значение и щелкнете в любом другом месте экрана для сохранения изменеий';
					if (typeof(obj.id) != 'undefined')
					{
						obj.tmp = obj.name;
					}
				}
				$scope.onblur = function(pid,cont,obj,type){
					var here = false;
					for (var i=0; i<cont.length; i++){
						if((cont[i].name == obj.name)&&(cont[i].id != obj.id))
						{
							here = true;
							$scope.message = 'Такая запись уже есть!';
						}
					}
					if (typeof(obj.id) != 'undefined')
					{
						
						if (here) obj.name = obj.tmp;
						else if (obj.name != obj.tmp){
							$scope.message = 'Заменяем '+obj.tmp+' на '+obj.name+'!';
							var xhr = new XMLHttpRequest();
							var url = 'http://kibilizard.org/nes/update.php?type='+type+'&id='+obj.id+'&name='+obj.name;
							xhr.open('GET', url, false);
							xhr.send();
						}
					}
					else 
					{
						if (here) obj.name = '';
						else if (obj.name != ''){
							$scope.message = 'Добавляем новую запись: '+obj.name+'!';
							var xhr = new XMLHttpRequest();
							var url = 'http://kibilizard.org/nes/insert.php?type='+type+'&pid='+pid+'&name='+obj.name;
							xhr.open('GET', url, false);
							xhr.send();
							if (xhr.status != 200) {
								console.log( xhr.status + ': ' + xhr.statusText );
							} else {
								var id = Number(xhr.responseText);
							}
							var name = obj.name;
							var i = cont.indexOf(obj);
							cont.splice(i,1);
							switch(type){
								case 'locality': 
								{
									cont.push({id: id,name: name,set: true,streets:[]});
									cont.push({set: false,name:'',sreets:[],});
									break;
								}
								case 'street': 
								{
									cont.push({id: id,name: name,set: true,homes:[]});
									cont.push({set: false,name:'',homes:[],});
									break;
								}
								case 'home': 
								{
									cont.push({id: id,name: name,set: true,aparts:[]});
									cont.push({set: false,name:'',aparts:[],});
									break;
								}
								case 'apart': 
								{
									cont.push({id: id,name: name,set: true});
									cont.push({set: false,name:''});
									break;
								}
							}
						}
					}
				}
				var obj = {id:1};
				$scope.openl(0,obj,'main');
				
				$scope.phoneBook = [];
				$scope.pb = true;
				$scope.search = {fio:'', bdate:'', addr:''};
				$scope.onSearchPhoneBook = [];
				$scope.change = function(){
					$scope.pb = !$scope.pb;
					if ($scope.tit == 'adressbook')
						$scope.tit = 'phonebook';
					else $scope.tit = 'adressbook';
					}
				$scope.ic = 0;
				$scope.curPage = 0;
				$scope.pageLength = 25;
				$scope.onLoad = true;
				$scope.onSearch = false;
				var getPB = function(){
					var xhr = new XMLHttpRequest();
					var url = 'http://kibilizard.org/nes/openpb.php?i='+$scope.ic;
					xhr.open('GET', url, false);
					xhr.send();
					if (xhr.status != 200) {
						console.log( xhr.status + ': ' + xhr.statusText );
					} else {
						var answer = JSON.parse( xhr.responseText );
						answer.forEach(function(item){
							$scope.phoneBook.push(item);
						});
						$scope.ic++;
						if ($scope.ic < 100) $timeout(getPB,500);
						else 
						{
							
							$scope.message = 'Все данные телефонной книги загружены!';
							$scope.onLoad = false;
							$scope.onSearch = false;
						}
					}
				}
				$scope.getNumAsArray = function(num){return new Array(num);}
				$scope.pbsearch = function(){
					if (!$scope.onSearch){
						$scope.message = 'Ищем совпадения по ФИО: '+$scope.search.fio+' дате рождения: '+$scope.search.bdate+' адрессу: '+$scope.search.addr;
						var str='?';
						var check = false;
						if ($scope.search.fio != '')
						{
							str += 'name='+$scope.search.fio+'&';
							check = true;
						}
						if ($scope.search.bdate != '')
						{
							str += 'bdate='+$scope.search.bdate+'&';
							check = true;
						}
						if ($scope.search.addr != '')
						{
							str += 'addr='+$scope.search.addr+'&';
							check = true;
						}
						if (check)
						{
							var xhr = new XMLHttpRequest();
							var url = 'http://kibilizard.org/nes/search.php'+str;
							xhr.open('GET', url, false);
							xhr.send();
							if (xhr.status != 200) {
								console.log( xhr.status + ': ' + xhr.statusText );
							} else {
								$scope.onSearchPhoneBook = JSON.parse( xhr.responseText );
								if (typeof($scope.onSearchPhoneBook[0]) == 'undefined')
									$scope.message = 'Ничего не найдено, возвращаемся к списку';
								else{
									$scope.message = 'Выводим найденые совпадения';
									$scope.onSearch = true;
								}
							}
						}
						else $scope.message = 'не введены данные для поиска!';
					}
					else 
					{
						$scope.message = 'Возвращаемся к списку';
						$scope.onSearch = false;
						$scope.onSearchPhoneBook = [];
					}
				}
				getPB();
				getPB();
				$window.onscroll = function(){
					if (!$scope.pb)
					{
						$scope.counter = document.body.scrollTop || document.documentElement.scrollTop || 0;
						$scope.$apply();
						var inputs = document.getElementsByTagName('input');
						var divs = [
							document.getElementById('np').getBoundingClientRect().right,
							document.getElementById('str').getBoundingClientRect().right,
							document.getElementById('h').getBoundingClientRect().right
							];
						var inp = {np:[],str:[],h:[],ap:[]};
						for (var i=0; i<inputs.length; i++)
						{
							var a1 = inputs[i].getBoundingClientRect().top;
								var a = inputs[i].getBoundingClientRect().left;
								if (a > divs[0])
								{
									if(a > divs[1])
									{
										if (a > divs[2])
											inp.ap.push({val:inputs[i].value,top:a1});
										else inp.h.push({val:inputs[i].value,top:a1});
									}
									else inp.str.push({val:inputs[i].value,top:a1});;
								}
								else inp.np.push({val:inputs[i].value,top:a1});
						}
						$scope.cur.np = '';
						for (i=0; i < inp.np.length; i++){
							if (inp.np[i].top < 90)
								$scope.cur.np = inp.np[i].val;
						}
						$scope.cur.str = '';
						for (i=0; i < inp.str.length; i++){
							if (inp.str[i].top < 90)
								$scope.cur.str = inp.str[i].val;
						}
						$scope.cur.h = '';
						for (i=0; i < inp.h.length; i++){
							if (inp.h[i].top < 90)
								$scope.cur.h = inp.h[i].val;
						}
						$scope.cur.ap = '';
						for (i=0; i < inp.ap.length; i++){
							if (inp.ap[i].top < 90)
								$scope.cur.ap = inp.ap[i].val;
						}
						$scope.$apply();
					}
				};
			});
			nesApp.filter('startTo', function(){
				return function(input,start){
					return input.slice(start);
				}
			});