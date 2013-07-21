google.load("visualization", "1", {packages:["corechart"]});
//google.setOnLoadCallback(drawScatterChart);

function drawTable(){   
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'Name');   data.addColumn('number', 'Salary');
	data.addColumn('boolean', 'Full Time');
	data.addRows(5);
	data.setCell(0, 0, 'John');
	data.setCell(0, 1, 10000, '$10,000');
	data.setCell(0, 2, true);
	data.setCell(1, 0, 'Mary');
	data.setCell(1, 1, 25000, '$25,000');
	data.setCell(1, 2, true);
	data.setCell(2, 0, 'Steve');
	data.setCell(2, 1, 8000, '$8,000'); 
	data.setCell(2, 2, false); 
	data.setCell(3, 0, 'Ellen');  
	data.setCell(3, 1, 20000, '$20,000');  
	data.setCell(3, 2, true);  
	data.setCell(4, 0, 'Mike'); 
	data.setCell(4, 1, 12000, '$12,000'); 
	data.setCell(4, 2, false);  
	var table = new google.visualization.Table(document.getElementById('table_div')); 
	table.draw(data, {showRowNumber: true}); 
	google.visualization.events.addListener(table, 'select', function() {     
		var row = table.getSelection()[0].row; 
		alert('You selected ' + data.getValue(row, 0));   
		}
	); 
}

function drawScatterChart(xAxis,yAxis,div){
//	alert("enter drawScatterChart()");
	
	var xArray = xAxis.split(","); 
	var yArray = yAxis.split(",");
	
	var data = new google.visualization.DataTable();  
//	alert("DataTable manufacted!");
	data.addColumn('number', 'xAxis');   
	data.addColumn('number', 'yAxis');      
	data.addRows(xArray.length);   
	for(i=0;i < xArray.length;i++){
		data.setValue(i, 0, parseFloat(xArray[i]));     
		data.setValue(i, 1, parseFloat(yArray[i]));
	}    
	
	var chart = new google.visualization.ScatterChart(div);  
	chart.draw(data, {width: 400, height: 240,
		              title: 'xAxis vs. yAxis comparison',
					  hAxis:{title: 'xAxis',minValue: 0,maxValue: 2},
					  vAxis: {title: 'yAxis',minValue: 0,maxValue: 2},
					  legend: 'none',
					  pointSize: 2
					  });
//	alert("all done");
}

function drawColumnChart(xAxis,yAxis,div){
//	alert("enter drawColumnChart()");
//	alert(1);
//	alert(xAxis);
//	alert(yAxis);
	
	var xArray = xAxis.split(","); 
	var yArray = yAxis.split(",");
	
//	alert(xArray);
//	alert(yArray);
	
	var sumArray = new Array();
	var numArray = new Array();
	var newArray = getNewArrays(xArray,yArray,sumArray,numArray);
	
//	alert(sumArray);
//	alert(numArray);
	
	var data = new google.visualization.DataTable();  
//	alert("DataTable manufacted!");
	data.addColumn('string', 'categories');     
	data.addColumn('number', 'sum');    
	data.addColumn('number', 'num'); 
//	alert(newArray.length);
	data.addRows(newArray.length);
	for(var i=0;i<newArray.length;i++){
		data.setValue(i, 0, newArray[i]);  
		data.setValue(i, 1, parseFloat(sumArray[i]));  
		data.setValue(i, 2, parseInt(numArray[i]));
	}     
	var chart = new google.visualization.ColumnChart(div); 
	chart.draw(data, {width: 400,
		              height: 240, 
		              title: 'Column Chart Comparsion',                           
		              hAxis: {title: 'xAxis--Items', 
		                      titleTextStyle: {color: 'red'}}
	                 }); 
//	alert("all done");
}

function getNewArrays(receiveArray,valueArray,sumArray,numArray){
	var arrResult = new Array();
	var index = 0;
//	alert("At begin:" + arrResult.length);
	
//	alert("enter getNewArrays()")
	for(var i=0;i<receiveArray.length;i++){
//		alert("In getNewArrays():i=" + i);
		var signal = check(arrResult,receiveArray[i]);
		if( signal == -1){
			arrResult.push(receiveArray[i]);
			sumArray[index] = parseFloat(valueArray[i]);
			numArray[index] = parseInt(1);
//			alert("index=" + index);
//			alert("sumArray[=" + index + "]" + sumArray[index]);
//			alert("numArray[" + index + "]" + numArray[index]);
			index++;
		}
		else{
			sumArray[signal] += parseFloat(valueArray[i]);
			numArray[signal]++;
//			alert("signal=" + signal);
//			alert("sumArray[=" + signal + "]" + sumArray[signal]);
//			alert("numArray[" + signal + "]" + numArray[signal]);
		}
	}
	
	return arrResult;
}

function check(receiveArray,checkItem){
	var index = -1;
//	alert("enter check()")
	
//	alert(receiveArray.length);
	for(var i=0;i<receiveArray.length;i++){
//		alert("In check: i=" + i);
		if(receiveArray[i].match(checkItem)){
//			alert("matched");
			index = i;
			break;
		}
//		alert("unmatched");
	}
	
	return index;
}




