function chart_pdm(value_complete,value_uncomplete){
      var namepdm 	= document.getElementById('ChartPM6');
      var namepdm 	= echarts.init(namepdm);
      var data    = [{value: value_complete, name: 'ดำเนินการสำเร็จ'},
                     {value: value_uncomplete, name: 'รอดำเนินการ'},]
      var option;
      option = {
        title: {
            text:'รายการ PDM',
            right:'40%',
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
              color: ['rgba(198, 19, 255)','rgba(19, 223, 255)'],
            }],
          itemStyle:{
            shadowBlur: 1,
            shadowColor: "rgba(10, 10, 10, 1)",
            shadowOffsetY: 7
          }
      };
      option && namepdm.setOption(option);
}
