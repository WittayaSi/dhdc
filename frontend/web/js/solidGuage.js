function solidGuage(obj, title_text, value) {

    var gaugeOptions = {
        chart: {
            type: 'solidgauge',
            weight: 300
        },
        title: {
            text: title_text,
            y: 20
        },
        pane: {
            center: ['50%', '55%'],
            size: '90%',
            startAngle: -90,
            endAngle: 90,
            background: {
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                innerRadius: '60%',
                outerRadius: '100%',
                shape: 'arc'
            }
        },
        tooltip: {
            enabled: false
        },
        // the value axis
        yAxis: {
            stops: [
                [0.5, '#DF5353'], //red
                [0.7, '#DDDF0D'], //yellow
                [0.9, '#55BF3B'] //green
            ],
            lineWidth: 0,
            minorTickInterval: null,
            tickPixelInterval: 400,
            tickWidth: 0,
            title: {
                y: -10
            },
            labels: {
                y: 16
            }
        },
        plotOptions: {
            solidgauge: {
                dataLabels: {
                    y: -60,
                    borderWidth: 0,
                    useHTML: true
                }
            }
        }
    };
    // The speed gauge
    obj.highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
            min: 0,
            max: 100,
            title: {
                text: ''
            }
        },
        credits: {
            enabled: false
        },
        series: [{
                //name: title_text,
                data: [value],
                dataLabels: {
                    format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                            ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                            '<span style="font-size:12px;color:silver">เปอร์เซ็น</span></div>'
                },
                tooltip: {
                    valueSuffix: 'เปอร์เซ็น'
                }
            }]

    }));
}
;
