function drillDown(obj, mainData, subData) {
    // Create the chart
    obj.highcharts({
        chart: {
            type: 'column',
            height: 350
        },
        credits: {
            enabled: false
        },
        title: {
            text: ''
        },
//        subtitle: {
//            text: 'Click the columns to view versions. Source: <a href="http://netmarketshare.com">netmarketshare.com</a>.'
//        },
        xAxis: {
            type: 'category',
            labels: {
                rotation: -10
            }
        },
        yAxis: {
            title: {
                text: 'เปอร์เซ็นต์'
            },
            max: 100
        },
        legend: {
            enabled: true
        },
        plotOptions: {
            series: {
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{point.y:.1f}%'
                }
            }
        },
        tooltip: {
            headerFormat: '',
            pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b><br/>'
        },
        series: [{
                name: 'ร้อยละ',
                colorByPoint: true,
                data: mainData
            }],
        drilldown: {
            series: subData
        }
    });
}
;