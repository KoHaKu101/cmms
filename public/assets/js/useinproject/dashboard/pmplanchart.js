var colorPalette3 = ['rgba(197,0,250, 1)','rgba(239, 250, 0, 1)' ];
var colorPalette6 = ['rgba(3, 204, 236,1)','rgba(231, 112, 0, 1)' ];
var colorPalette12 = ['rgba(46, 255, 122,1)','rgba(255, 78, 46, 1)' ];

  var NamePlanPm = { 'ChartPM3': 3,'ChartPM6': 6,'ChartPM12': 12, }
  var colorplan  = {3:colorPalette3,6:colorPalette6,12:colorPalette12,}
  // var value_complete 	 = {3:{{  $data_complete[3]}}
  //                        ,6:{{  $data_complete[6]}}
  //                        ,12:{{ $data_complete[12]}},}
  // var value_uncomplete = {3:{{  $data_uncomplete[3]}}
  //                        ,6:{{  $data_uncomplete[6]}}
  //                        ,12:{{ $data_uncomplete[12]}},}
  $.each(NamePlanPm,function(namepm,month){
    var namepm 	= document.getElementById(namepm);
    var namepm 	= echarts.init(namepm);
    var text = month+' เดือน';
    var data = [{value: value_complete[month], name: 'ดำเนินการสำเร็จ'},
               {value: value_uncomplete[month], name: 'รอดำเนินการ'},]
    var option;
    option = {
      title: {
          text:text,
          right:'55%',
          top:'6%',
      },
      tooltip: {
          show :false,
          trigger: 'item',

      },
      legend: {
          bottom: '0%',
          right:'40%',
          textStyle:{
              fontSize:'12',
          }
      },
      series: [{
            type: 'pie',
            radius: ['25%', '73%'],
            right:'30%',
            avoidLabelOverlap: false,
            label: {
                show: true,
                position:'inside',
                formatter:'{c}',
                 fontSize:'15',
                fontWeight:'bold',
                color:'#000000',
            },
            data: data,
            color: colorplan[month],
          }],
        itemStyle:{
          shadowBlur: 1,
          shadowColor: "rgba(10, 10, 10, 1)",
          shadowOffsetY: 7
        }
    };
    option && namepm.setOption(option);
  });
