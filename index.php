<!DOCTYPE html>
<html ng-app="nesApp">
	<head>
		<title>test for NES</title>
		<link rel="stylesheet" type="text/css" href="mstyle.css">
		<script type ="text/javascript" src="js/angular.min.js"></script>
		<script type ="text/javascript" src="js/module&ctrl.js"></script>
	</head>
	<body ng-controller="mainCtrl">
		<div id="header" >
          <div ng-click="change();" style="float:left; font-style: oblique; cursor:pointer;"> go to {{tit}} </div>
          <div style="float:right; width:350px; height:50px; border: 4px groove grey; background: #ffffff; position:relative; left:-30px;">{{message}}</div>
		</div>
		<div id="hshadow"></div>
		
		<div ng-if="!pb">
		<div id="addrhead">
			<div id="np">н.п.: {{cur.np}}</div>
			<div id="str">ул.: {{cur.str}}</div>
			<div id="h">д.: {{cur.h}}</div>
			<div id="ap">кв: {{cur.ap}}</div>
		</div>
		<div id="addr">
			<div class="row" ng-repeat="local in list track by $id(local)">
			<div class="locality">
				<div class="cell" ng-init="img = local.set ? 'images/naviopen.png' : 'images/navinul.png'; undo = local.set ? 'images/delette.png' : 'images/add.png';">
					<img ng-src="{{undo}}" style="width:10px; float:left" ng-click="undol(list,local,'locality')">
					<input type="text" ng-class="{'clean':!local.set}" ng-model="local.name" ng-focus="onfocus(list,local,'locality')" ng-blur="onblur(0,list,local,'locality')">
					<img ng-src="{{img}}" style="width:10px; float:right" ng-click="openl(this,local,'locality')">
				</div>
				<div class="streets">
					<div class="row" ng-repeat="street in local.streets track by $id(street)">
					<div ng-class="{'locality':street.set,'lnul':!street.set}">
						<div class="cell" ng-init="img = street.set ? 'images/naviopen.png' : 'images/navinul.png'; undo = street.set ? 'images/delette.png' : 'images/add.png';">
							<img ng-src="{{undo}}" style="width:10px; float:left" ng-click="undol(local.streets,street,'street')">
							<input type="text" ng-class="{'clean':!street.set}"  ng-model="street.name" ng-focus="onfocus(local.streets,street,'street')" ng-blur="onblur(local.id,local.streets,street,'street')">
							<img ng-src="{{img}}" style="width:10px; float:right" ng-click="openl(this,street,'street')">
						</div>
						<div class="houses">
							<div class="row" ng-repeat="house in street.homes track by $id(house)">
							<div ng-class="{'locality':house.set,'lnul':!house.set}">
								<div class="cell house" ng-init="img = house.set ? 'images/naviopen.png' : 'images/navinul.png'; undo = house.set ? 'images/delette.png' : 'images/add.png';">
									<img ng-src="{{undo}}" style="width:10px; float:left" ng-click="undol(street.homes,house,'home')">
									<input type="number" ng-class="{'clean':!house.set}"  size="10px" ng-model="house.name" style="width:38px;" ng-focus="onfocus(street.homes,house,'home')" ng-blur="onblur(street.id,street.homes,house,'home')">
									<img ng-src="{{img}}" style="width:10px; float:right" ng-click="openl(this,house,'home')">
								</div>
								<div class="apartments">
									<div class="row" ng-repeat="apart in house.aparts track by $id(apart)">
									<div ng-class="{'locality':apart.set,'lnul':!apart.set}">
										<div class="cell apart" ng-init="undo = apart.set ? 'images/delette.png' : 'images/add.png';">
											<img ng-src="{{undo}}" style="width:10px; float:left" ng-click="undol(house.aparts,apart,'apart')">
											<input type="number" ng-class="{'clean':!apart.set}"  size="10px" ng-model="apart.name" style="width:38px;" ng-focus="onfocus(house.aparts,apart,'apart')" ng-blur="onblur(house.id,house.aparts,apart,'apart')">
										</div>
									</div>
									</div>
								</div>
							</div>
							</div>
						</div>
					</div>
					</div>
				</div>
			</div>
			</div>
		</div>
		</div>
		
		<div id="phonebook" ng-if="pb">
			<div id="search">
				<div class="row">
					<div class="cell" style="width:220px;">
					<label>ФИО: <input type="text" size="20px" class="clean" ng-focus="curPage=0;" ng-model="search.fio"></label>
					</div>
					<div class="cell" style="width:220px;">
					<label>д.р.: <input type="text" size="20px" class="clean" ng-focus="curPage=0;" ng-model="search.bdate"></label>
					</div>
					<div class="cell" style="width:220px;">
					<label>адрес: <input type="text" size="20px" class="clean" ng-focus="curPage=0;" ng-model="search.addr"></label>
					</div>
					<div class="cell" style="width:80px; float:right;">
					<label>стр: <select ng-model="curPage">
							<option ng-repeat="num in getNumAsArray(10000/25) track by $index" ng-value="$index">{{$index+1}}</option>
						</select></label>
					</div>
				</div>
				<div ng-if="onLoad" style="padding-top:10px; padding-bottom:10px; width:100%;">
					<div class="cell" style="padding-top:10px;">Загрузка: {{ic*100}}/10000<img src="images/naviload.gif" style="width:20px; float:right;"></div>
					<div class="cell" style="width:260px;">Ускоренный поиск пока не доступен, для поиска нажмите кнопку</div>
					<div class="cell butt" ng-if="!onSearch" ng-click="pbsearch()">поиск</div>
					<div class="cell butt" ng-if="onSearch" ng-click="pbsearch()">назад</div>
				</div>
			</div>
			<div style="height:22px; display:block;"></div>
			<div ng-if="onLoad" style="height:46px; display:block;"></div>
			
			<div class="phlist" ng-if="!onSearch && onLoad">
				<div class="row" ng-repeat="abon in phoneBook | startTo: curPage * pageLength | limitTo:pageLength">
				<div class="locality">
					<div class="fio  cell">{{abon.name}}</div>
					<div class="bdate cell">{{abon.bdate}}</div>
					<div class="adr cell">{{abon.addr}}</div>
					<div class="phones cell">
						<div class="cell" ng-repeat="phone in abon.phones">{{phone}}</div>
					</div>
				</div>
				</div>
			</div>
			<div class="phlist" ng-if="!onLoad && !onSearch">
				<div class="row" ng-repeat="abon in phoneBook | filter:{name:search.fio, bdate:search.bdate, addr:search.addr} | startTo: curPage * pageLength | limitTo:pageLength">
				<div class="locality">
					<div class="fio  cell">{{abon.name}}</div>
					<div class="bdate cell">{{abon.bdate}}</div>
					<div class="adr cell">{{abon.addr}}</div>
					<div class="phones cell">
						<div class="cell" ng-repeat="phone in abon.phones">{{phone}}</div>
					</div>
				</div>
				</div>
			</div>
			<div class="phlist" ng-if="onSearch">
				<div class="row" ng-repeat="abon in onSearchPhoneBook | startTo: curPage * pageLength | limitTo:pageLength">
				<div class="locality">
					<div class="fio  cell">{{abon.name}}</div>
					<div class="bdate cell">{{abon.bdate}}</div>
					<div class="adr cell">{{abon.addr}}</div>
					<div class="phones cell">
						<div class="cell" ng-repeat="phone in abon.phones">{{phone}}</div>
					</div>
				</div>
				</div>
			</div>
		</div>
		
	</body>
</html>