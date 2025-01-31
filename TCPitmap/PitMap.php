<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>ORP Paddock Map</title>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <style type="text/css">
	#holder{	
	 height:400px;	 
	 width:900px;
	 background-color:#F5F5F5;
	 background-image: url(pitmap.jpg);
	 border:1px solid #A4A4A4;
	 margin-left:10px;	
	}
	 #place {
	 position:relative;
	 margin:10px;
	 margin-left: 30px;
	 }
     #place a{
	 font-size:0.6em;
	 -ms-transform: rotate(90deg);
	 -moz-transform: rotate(90deg);
	 -webkit-transform:rotate(90deg);
	 }
     #place li
     {
         list-style: none outside none;
         position: absolute;   
         border:1px solid black;
     }    
     #place li:hover
     {
        background-color:yellow;      
     } 
	 #place .seat{
	 height: 30px;

	 display:block;	 
	 }
      #place .selectedSeat
      { 
        background-color:Red; 
      }
	   #place .selectingSeat
      { 
		   	 
      }
      #place .row-0
      {  
          width: 30px;        
      }
      
      #place .row-1
      {
          height:60px;
          width: 15px;
          margin-left: 35px;
      }      
	  #place .row-2
	  {
	    width: 30px;
		margin-top:30px;
		margin-left:15px;
	  }
	  #place .row-3
	  {
	      width: 30px;
	      margin-top:50px;
	      margin-left: 440px;     
	  }
	  #place .row-4
	  {
	      width: 30px;
	      margin-top:60px;
	      margin-left: 440px; 	      
	  }
	  
	 #seatDescription{
	 padding:0px;
	 }
	  #seatDescription li{
	  verticle-align:middle;	  
	  list-style: none outside none;
	   padding-left:35px;
	  height:35px;
	  float:left;
	  }
    </style>

</head>
<body>
<?php
$myfile = fopen("pitmap.txt", "w") or die("unable to open file!");
$txt = "this is a test";
fwrite($myfile, $txt);
fclose($myfile);
?>
<div id="holder">
    <ul id="place">
    </ul>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var settings = {
            rows: 5,
            cols: 40,
            rowCssPrefix: 'row-',
            colCssPrefix: 'col-',
            seatWidth: 30,
            seatHeight: 60,
            seatCss: 'seat',
            selectedSeatCss: 'selectedSeat',
            selectingSeatCss: 'selectingSeat'
        };

        $('a').hover(function () {
            var title = $(this).attr('title');
        });

        function checkSeat(s, bs) {
            for (var i = 0, bsLen = bs.length; i < bsLen; i++) {
                if (s === bs[i][0])
                    return i;
            }

            return -1
        }

        function getName(s, bs) {
            for (var i = 0, bsLen = bs.length; i < bsLen; i++) {
                if (s === bs[i][0])
                    return bs[i][1];
            }
        }
		
       var init = function (reservedSeat) {
            var str = [], seatNo, className, seatName, rowCols = [25, 47, 25, 10, 10], seatWid = [30, 15, 30, 30, 30], seatCounter;
            for (j = 0; j < settings.rows; j++) {
                if (j == 0) { seatCounter = 0; }
                else { seatCounter = (seatCounter + rowCols[j-1] - 1); }
                for (i = 0; i < rowCols[j]; i++) {
                    seatNo = (j + i + 1 + seatCounter);
                    seatName = seatNo;
                    className = settings.seatCss + ' ' + settings.rowCssPrefix + j.toString() + ' ' + settings.colCssPrefix + i.toString();
                    if ($.isArray(reservedSeat) && checkSeat(seatNo, reservedSeat) != -1) {
                        className += ' ' + settings.selectedSeatCss;
                        seatName = getName(seatNo, reservedSeat);
                    }
                    str.push('<li class="' + className + '"' +
                                'style="top:' + (j * settings.seatHeight).toString() + 'px;left:' + (i * seatWid[j]).toString() + 'px">' +
                                '<a title="' + seatName + '">' + seatNo + '</a>' +
                                '</li>');

                }
            }
            $('#place').html(str.join(''));
        };



        var bookedSeats = [];
        var attending = [];

        $.getJSON('http://api.motorsportreg.com/rest/events/A30ED69F-DC06-AC2D-8CCD53C163A4D434/entrylist.jsonp?jsoncallback=?'
		, {
		    dataType: "jsonp"
			, cacheBuster: new Date()
		}
		, function (json) {
		    $.each(json.response.assignments, function (i, evt) {

		        if (evt.segment.toString() == 'Paddock Assignment') {
		            var row = []
		            var space = evt.vehicleNumber;
		            var name = evt.firstName + ' ' + evt.lastName;
		            row.push(space, name.toString());
		            bookedSeats.push(row);

		        }
		    });
		    init(bookedSeats);
		}

	);
    });			

</script>

<div id="msrCalendar"></div>


</body>
</html>


