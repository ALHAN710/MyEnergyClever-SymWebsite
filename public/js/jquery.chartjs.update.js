/**
 * Theme: Frogetor - Responsive Bootstrap 4 Admin Dashboard
 * Author: Mannatthemes
 * Chart Js
 */


!function ($) {
    "use strict";
    let Obj = { 'chart': 1 };
    var ChartJs = function () { };

    ChartJs.prototype.respChart = function (selector, obj, type, data, options) {
        // get selector by context
        var ctx = selector.get(0).getContext("2d");
        // pointing parent container to make chart js inherit its width
        var container = $(selector).parent();

        // enable resizing matter
        $(window).resize(generateChart);

        // this function produce the responsive Chart JS
        function generateChart() {
            // make chart width fit with its container
            var ww = selector.attr('width', $(container).width());
            switch (type) {
                case 'Line':
                    obj.chart = new Chart(ctx, { type: 'line', data: data, options: options });
                    //CourbedeCharge_Linechart = _chart1;
                    //console.log(_chart1);
                    break;
                case 'Doughnut':
                    obj.chart = new Chart(ctx, { type: 'doughnut', data: data, options: options });
                    break;
                case 'Pie':
                    obj.chart = new Chart(ctx, { type: 'pie', data: data, options: options });
                    break;
                case 'Bar':
                    obj.chart = new Chart(ctx, { type: 'bar', data: data, options: options });
                    break;
                case 'Radar':
                    obj.chart = new Chart(ctx, { type: 'radar', data: data, options: options });
                    break;
                case 'PolarArea':
                    obj.chart = new Chart(ctx, { data: data, type: 'polarArea', options: options });
                    break;
            }
            // Initiate new chart or Redraw

        };
        // run function - render chart at first load
        generateChart();
    },

        //init
        ChartJs.prototype.init = function () {
            if (isGrid === 1) { // 1 --> gridChart
                //creating linechart
                var lineChart = {
                    labels: [],
                    datasets: [{
                        label: "Puissance apparente",
                        fill: false,
                        backgroundColor: "rgba(55, 159, 255, 0.3)",//"rgba(58, 169, 244, 0.3)",
                        borderColor: "rgba(58, 169, 244, 0.8)",//"rgba(163, 199, 231, 255)",
                        borderWidth: 1,
                        pointBorderColor: "rgba(58, 169, 244, 0.8)",//"rgba(163, 199, 231, 255)",
                        pointBackgroundColor: "rgba(58, 169, 244, 0.8)",//"rgba(163, 199, 231, 255)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(55, 159, 255,1)",//151,187,205,
                        pointBorderWidth: 0,
                        pointRadius: 1.5,
                        pointStyle: 'circle',
                        data: []
                    }]
                };

                var lineOpts = {
                    responsive: true,
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            // scaleLabel: {
                            //     display: true,
                            //     labelString: 'Month'
                            // },
                            type: 'time',
                            distribution: 'linear',
                            time: {
                                unit: 'hour',//'day'
                                displayFormats: {
                                    //day: 'D MMM'
                                    day: 'h:mm'
                                },
                                unitStepSize: 2
                            },
                            gridLines: {
                                color: "rgba(0,0,0,0.1)",
                                drawOnChartArea: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: moment().format('DD MMMM').toString()//'Mars'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                color: "rgba(255,255,255,0.05)",
                                fontColor: '#ffffff',
                                display: false,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'S(kVA)'
                            },/*
                    ticks: {
                        max: 100,
                        min: -100,
                        stepSize: 20
                    }*/
                        }]
                    }
                };

                this.respChart($("#CourbedeCharge"), Obj, 'Line', lineChart, lineOpts);
                CourbedeCharge_Linechart = Obj.chart;

                //barchart
                var barChart = {
                    labels: [],
                    datasets: [
                        {
                            label: "Energie(kWh)",
                            backgroundColor: "rgba(230, 22, 78, 0.8)",//"rgba(252, 184, 183, 255)",//backgroundColor: "rgba(68, 162, 210, 0.4)",
                            //borderColor: "#44a2d2",
                            //borderWidth: 2,
                            barPercentage: 0.3,
                            categoryPercentage: 0.5,
                            //hoverBackgroundColor: "rgba(68, 162, 210, 0.7)",
                            //hoverBorderColor: "#44a2d2",
                            data: []
                        }
                    ]
                };
                var barOpts = {
                    responsive: true,
                    scales: {
                        xAxes: [
                            {
                                barPercentage: 0.8,
                                categoryPercentage: 0.4,
                                display: true,
                                type: 'time',
                                time: {
                                    unit: 'day',
                                    displayFormats: {
                                        day: 'D MMM'
                                    }
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: moment().format('MMMM')//'Mars'
                                }
                            }
                        ],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            display: true,
                            gridLines: {
                                display: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: '(kWh)'
                            }
                        }]
                    }

                };
                this.respChart($("#PowerConsumption"), Obj, 'Bar', barChart, barOpts);
                PowerConsumption_Barchart = Obj.chart;
            }
            else if (isGrid === 2) { // 2 --> fuelChart
                //barchart
                var barChart = {
                    labels: [],
                    datasets: [
                        {
                            label: "Energie(kWh)",
                            backgroundColor: "rgba(230, 22, 78, 0.8)",//"rgba(252, 184, 183, 255)",//backgroundColor: "rgba(68, 162, 210, 0.4)",
                            //borderColor: "#44a2d2",
                            //borderWidth: 2,
                            barPercentage: 0.3,
                            categoryPercentage: 0.5,
                            //hoverBackgroundColor: "rgba(68, 162, 210, 0.7)",
                            //hoverBorderColor: "#44a2d2",
                            data: []
                        }
                    ]
                };
                var barOpts = {
                    responsive: true,
                    scales: {
                        xAxes: [
                            {
                                barPercentage: 0.8,
                                categoryPercentage: 0.4,
                                display: true,
                                type: 'time',
                                time: {
                                    unit: 'day',
                                    displayFormats: {
                                        day: 'D MMM'
                                    }
                                },
                                scaleLabel: {
                                    display: true,
                                    labelString: moment().format('MMMM')//'Mars'
                                }
                            }
                        ],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            },
                            display: true,
                            gridLines: {
                                display: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: '(kWh)'
                            }
                        }]
                    }

                };
                this.respChart($("#PowerProduction"), Obj, 'Bar', barChart, barOpts);
                PowerProduction_Barchart = Obj.chart;

                //creating linechart
                var lineChart = {
                    labels: [],
                    datasets: [{
                        type: 'bar',
                        yAxisID: 'B',//any with same group will appear in the same axis, also this can be anything as long as you always refer to it with the same name
                        label: "Working Time",
                        backgroundColor: "rgb(252, 184, 183, 0.8)",//"rgba(135, 197, 255, 0.31)",//38, 185, 154,
                        fill: false,
                        data: [],
                    }, {
                        type: 'line',
                        yAxisID: 'L',//any with same group will appear in the same axis, also this can be anything as long as you always refer to it with the same name
                        label: "Conso carburant",
                        backgroundColor: "rgb(255, 255, 255, 0.0)",//"rgba(55, 159, 255, 0.3)",
                        borderColor: "rgba(58, 169, 244, 0.8)",//"rgba(163, 199, 231, 255)",
                        borderWidth: 1,
                        pointBorderColor: "rgba(58, 169, 244, 0.8)",//"rgba(163, 199, 231, 255)",
                        pointBackgroundColor: "rgba(58, 169, 244, 0.8)",//"rgba(163, 199, 231, 255)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(55, 159, 255,1)",//151,187,205,
                        pointBorderWidth: 1,
                        pointRadius: 1.5,
                        pointStyle: 'circle',
                        fill: false,
                        data: [],
                    }],

                };
                function epoch_to_hh_mm_ss(epoch) {
                    return new Date(epoch * 1000).toISOString().substr(12, 7)
                }
                var lineOpts = {
                    responsive: true,
                    Legend: true,
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            type: 'time',
                            time: {
                                unit: 'day',
                                displayFormats: {
                                    day: 'D MMM'
                                },
                                //unitStepSize: 4
                            },
                            gridLines: {
                                //display:false,
                                drawOnChartArea: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: moment().format('MMMM').toString()//'Mars'
                            }
                        }],
                        yAxes: [{
                            id: 'B',
                            type: 'linear',
                            position: 'left',
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Temps de fonctionnement(mins)'
                            },
                            gridLines: {
                                display: false,
                            },
                            ticks: {
                                //callback: console.log(this.formatSecsAsMins(3600)),//(v)=>this.formatSecsAsMins(v),
                                userCallback: function (v) { return epoch_to_hh_mm_ss(v) },
                                //stepSize:300, //add a tick every 5 minutes
                            }
                        }, {
                            id: 'L',
                            type: 'linear',
                            position: 'right',
                            display: true,
                            gridLines: {
                                display: false,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Conso carburant(Litre)'
                            },

                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function (tooltipItem, data) { //tooltipItem.datasetIndex
                                console.log(tooltipItem.yLabel);
                                if (tooltipItem.datasetIndex !== 1) return data.datasets[tooltipItem.datasetIndex].label + ': ' + epoch_to_hh_mm_ss(tooltipItem.yLabel);
                                else return data.datasets[tooltipItem.datasetIndex].label + ': ' + tooltipItem.yLabel;
                            }
                        }
                    }
                };
                this.respChart($("#CourbeCroisé"), Obj, 'Bar', barChart, barOpts);
                CourbeCroisé_BarLinechart = Obj.chart;

                //creating linechart
                var lineChart = {
                    labels: [],
                    datasets: [{
                        label: "Puissance apparente triphasé",
                        fill: false,
                        backgroundColor: "rgba(55, 159, 255, 0.3)",
                        borderColor: "rgba(58, 169, 244, 255)",//"rgba(163, 199, 231, 255)",
                        borderWidth: 1,
                        pointBorderColor: "rgba(58, 169, 244, 255)",//"rgba(163, 199, 231, 255)",
                        pointBackgroundColor: "rgba(58, 169, 244, 255)",//"rgba(163, 199, 231, 255)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(55, 159, 255,1)",//151,187,205,
                        //pointBorderWidth: 0,
                        pointRadius: 1.5,
                        pointStyle: 'circle',
                        data: []
                    }]
                };

                var lineOpts = {
                    responsive: true,
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            type: 'time',
                            time: {
                                unit: 'hour',//'day',
                                displayFormats: {
                                    day: 'h:mm',//'D MMM'
                                },
                                unitStepSize: 1
                            },
                            gridLines: {
                                color: "rgba(0,0,0,0.1)",
                                drawOnChartArea: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: moment().format('DD MMMM').toString()//'Mars'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                color: "rgba(255,255,255,0.05)",
                                fontColor: '#ffffff',
                                display: false,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'S(kVA)'
                            }
                        }]
                    }
                };

                this.respChart($("#ActivityPlan"), Obj, 'Line', lineChart, lineOpts);
                ActivityPlan_Linechart = Obj.chart;

            }

            if (isGrid === 3) { // 3 ----> fuelHistoStockChart
                //creating linechart
                var lineChart = {
                    labels: [],
                    datasets: [{
                        label: "Fuel Supply History",
                        backgroundColor: "rgba(55, 159, 255, 0.3)",
                        borderColor: "rgba(58, 169, 244, 255)",//"rgba(163, 199, 231, 255)",
                        borderWidth: 1,
                        pointBorderColor: "rgba(58, 169, 244, 255)",//"rgba(163, 199, 231, 255)",
                        pointBackgroundColor: "rgba(58, 169, 244, 255)",//"rgba(163, 199, 231, 255)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(55, 159, 255,1)",//151,187,205,
                        pointBorderWidth: 0,
                        pointRadius: 1.5,
                        pointStyle: 'circle',
                        fill: false,
                        data: [],
                    }]
                };

                var lineOpts = {
                    responsive: true,
                    Legend: true,
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            type: 'time',
                            distribution: 'linear',
                            time: {
                                unit: 'hour',//'day'
                                displayFormats: {
                                    //day: 'D MMM'
                                    day: 'h:mm'
                                },
                                unitStepSize: 2
                            },
                            gridLines: {
                                //display:false,
                                drawOnChartArea: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: moment().format('DD MMMM').toString()//'Mars'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                display: false,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: '(Liters)'
                            }
                        }]
                    },
                };
                this.respChart($("#CourbeAppro"), Obj, 'Line', lineChart, lineOpts);
                CourbeAppro_Linechart = Obj.chart;

                //creating linechart
                var lineChart = {
                    labels: [],
                    datasets: [{
                        label: "Fuel Stock History",
                        backgroundColor: "rgba(55, 159, 255, 0.3)",
                        borderColor: "rgba(230, 22, 78, 0.8)",//"rgba(58, 169, 244, 255)",//"rgba(252, 184, 183, 255)",
                        borderWidth: 1,
                        pointBorderColor: "rgba(230, 22, 78, 0.8)",//"rgba(58, 169, 244, 255)",//"rgba(252, 184, 183, 255)",
                        pointBackgroundColor: "rgba(230, 22, 78, 0.8)",//"rgba(252, 184, 183, 255)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(55, 159, 255,1)",//151,187,205,
                        pointBorderWidth: 0,
                        pointRadius: 1.5,
                        pointStyle: 'circle',
                        fill: false,
                        data: [],
                    }]
                };

                var lineOpts = {
                    responsive: true,
                    Legend: true,
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            type: 'time',
                            distribution: 'linear',
                            time: {
                                unit: 'hour',//'day'
                                displayFormats: {
                                    //day: 'D MMM'
                                    day: 'h:mm'
                                },
                                unitStepSize: 2
                            },
                            gridLines: {
                                //display:false,
                                drawOnChartArea: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: moment().format('DD MMMM').toString()//'Mars'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                display: false,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: '(Liters)'
                            }
                        }]
                    },
                };
                this.respChart($("#CourbeStock"), Obj, 'Line', lineChart, lineOpts);
                CourbeStock_Linechart = Obj.chart;
            }
            /*
                else if (isGrid === 'pieDashSiteChart') {
                    //Pie chart
                    var pieChart = {
                        labels: [
                            "Desktops",
                            "Tablets",
                            "Mobiles",
                            "Mobiles",
                        ],
                        datasets: [
                            {
                                data: [80, 50, 100, 121],
                                backgroundColor: [
                                    "#194a8b",
                                    "#00264a",
                                    "#e3eaef",
                                    "#44a2d2",
                                ],
                                borderColor: "#333858",
                                hoverBackgroundColor: [
                                    "#194a8b",
                                    "#00264a",
                                    "#e3eaef",
                                    "#44a2d2",
                                ],
                                hoverBorderColor: "#fff"
                            }]
                    };
                    this.respChart($("#pie"), 'Pie', pieChart);
            }
            */
            else {
                //creating linechart
                var lineChart = {
                    labels: [],
                    datasets: [{
                        label: "SA",
                        backgroundColor: "rgb(255, 255, 255, 0.0)",//"rgba(135, 197, 255, 0.31)",//38, 185, 154,
                        borderColor: "rgba(230, 22, 78, 0.8)",//"rgb(252, 184, 183, 255)",//"rgba(63, 63, 63, 0.7)",//135, 197, 255
                        borderWidth: 1,
                        pointBorderColor: "rgba(230, 22, 78, 0.8)",//"rgb(252, 184, 183, 255)",//"rgba(63, 63, 63, 0.7)",
                        pointBackgroundColor: "rgba(230, 22, 78, 0.8)",//"rgb(252, 184, 183, 255)",//"rgba(63, 63, 63, 0.7)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointBorderWidth: 1,
                        pointRadius: 1.5,
                        //pointStyle: 'line',
                        fill: false,
                        data: [],//[31, 74, 6, 39, 20, 85, 7]
                    }, {
                        label: "SB",
                        backgroundColor: "rgb(255, 255, 255, 0.0)",//"rgba(55, 159, 255, 0.3)",
                        borderColor: "rgba(251, 213, 158, 255)",//"rgb(251, 213, 158, 255)",//"rgba(55, 159, 255, 0.70)",
                        borderWidth: 1,
                        pointBorderColor: "rgb(251, 213, 158, 255)",//"rgba(55, 159, 255, 0.70)",
                        pointBackgroundColor: "rgba(251, 213, 158, 255)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(55, 159, 255,1)",//151,187,205,
                        pointBorderWidth: 1,
                        pointRadius: 1.5,
                        //pointStyle: 'line',
                        fill: false,
                        data: [],//[82, 23, 66, 9, 99, 4, 2],
                    }, {
                        label: "SC",
                        backgroundColor: "rgba(55, 159, 255, 0.3)",//"rgba(3, 88, 106, 0.31)",
                        borderColor: "rgba(58, 169, 244, 255)",//"rgb(163, 199, 231, 255)",//"rgba(240, 167, 135, 0.70)",
                        borderWidth: 1,
                        pointBorderColor: "rgba(58, 169, 244, 255)",//"rgb(163, 199, 231, 255)",//"rgba(240, 167, 135, 0.70)",
                        pointBackgroundColor: "rgba(58, 169, 244, 255)",//"rgb(163, 199, 231, 255)",//"rgba(240, 167, 135, 0.70)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(55, 159, 255, 1)",//"rgba(151,187,205,1)",
                        pointBorderWidth: 1,
                        pointRadius: 1.5,
                        //pointStyle: 'line',
                        fill: false,
                        data: [],//[42, 53, 26, 59, 14, 12]
                    }]
                };

                var lineOpts = {
                    responsive: true,
                    Legend: true,
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            type: 'time',
                            distribution: 'linear',
                            time: {
                                unit: 'hour',//'day'
                                displayFormats: {
                                    //day: 'D MMM'
                                    day: 'h:mm'
                                },
                                unitStepSize: 1
                            },
                            gridLines: {
                                color: "rgba(0,0,0,0.1)",
                                drawOnChartArea: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: moment().format('DD MMMM').toString()//'Mars'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                color: "rgba(255,255,255,0.05)",
                                fontColor: '#ffffff',
                                display: false,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'S(kVA)'
                            },
                        }]
                    }
                };
                this.respChart($("#PowerProfile"), Obj, 'Line', lineChart, lineOpts);
                PowerProfile_Linechart = Obj.chart;

                //creating linechart
                var lineChart = {
                    labels: [],
                    datasets: [{
                        label: "VA",
                        backgroundColor: "rgb(255, 255, 255, 0.0)",//"rgba(135, 197, 255, 0.31)",//38, 185, 154,
                        borderColor: "rgba(230, 22, 78, 0.8)",//"rgba(252, 184, 183, 255)",//135, 197, 255
                        borderWidth: 1,
                        pointBorderColor: "rgba(230, 22, 78, 0.8)",//"rgba(252, 184, 183, 255)",
                        pointBackgroundColor: "rgba(230, 22, 78, 0.8)",//"rgba(252, 184, 183, 255)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointBorderWidth: 1,
                        pointRadius: 1.5,
                        //pointStyle: 'line',
                        fill: false,
                        data: []
                    }, {
                        label: "VB",
                        backgroundColor: "rgb(255, 255, 255, 0.0)",//"rgba(55, 159, 255, 0.3)",
                        borderColor: "rgba(251, 213, 158, 255)",
                        borderWidth: 1,
                        pointBorderColor: "rgba(251, 213, 158, 255)",
                        pointBackgroundColor: "rgba(251, 213, 158, 255)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(55, 159, 255,1)",//151,187,205,
                        pointBorderWidth: 1,
                        pointRadius: 1.5,
                        //pointStyle: 'line',
                        fill: false,
                        data: [],
                    }, {
                        label: "VC",
                        backgroundColor: "rgba(55, 159, 255, 0.3)",//"rgba(3, 88, 106, 0.31)",
                        borderColor: "rgba(58, 169, 244, 255)",//"rgba(163, 199, 231, 255)",
                        borderWidth: 1,
                        pointBorderColor: "rgba(58, 169, 244, 255)",//"rgba(163, 199, 231, 255)",
                        pointBackgroundColor: "rgba(58, 169, 244, 255)",//"rgba(163, 199, 231, 255)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(55, 159, 255, 1)",//"rgba(151,187,205,1)",
                        pointBorderWidth: 1,
                        pointRadius: 1.5,
                        //pointStyle: 'line',
                        fill: false,
                        data: []
                    }]
                };

                var lineOpts = {
                    responsive: true,
                    Legend: true,
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            type: 'time',
                            distribution: 'linear',
                            time: {
                                unit: 'hour',//'day'
                                displayFormats: {
                                    //day: 'D MMM'
                                    day: 'h:mm'
                                },
                                unitStepSize: 1
                            },
                            gridLines: {
                                color: "rgba(0,0,0,0.1)",
                                drawOnChartArea: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: moment().format('DD MMMM').toString()//'Mars'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                color: "rgba(255,255,255,0.05)",
                                fontColor: '#ffffff',
                                display: false,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: '(Volts)'
                            },
                            ticks: {
                                //max: 5,
                                suggestedmin: 0,
                                //stepSize: 50
                            }
                        }]
                    }
                };
                this.respChart($("#VoltageProfile"), Obj, 'Line', lineChart, lineOpts);
                VoltageProfile_Linechart = Obj.chart;

                //creating linechart
                var lineChart = {
                    labels: [],
                    datasets: [{
                        label: "IDC",
                        backgroundColor: "rgb(255, 255, 255, 0.0)",//"rgba(135, 197, 255, 0.31)",//38, 185, 154,
                        borderColor: "rgba(230, 22, 78, 0.8)",//"rgba(252, 184, 183, 255)",//135, 197, 255
                        borderWidth: 1,
                        pointBorderColor: "rgba(230, 22, 78, 0.8)",//"rgba(252, 184, 183, 255)",
                        pointBackgroundColor: "rgba(230, 22, 78, 0.8)",//"rgba(252, 184, 183, 255)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointBorderWidth: 1,
                        pointRadius: 1.5,
                        fill: false,
                        data: [],//[31, 74, 6, 39, 20, 85, 7]
                    }, {
                        label: "IDC_Ref",
                        backgroundColor: "rgb(255, 255, 255, 0.0)",//"rgba(55, 159, 255, 0.3)",
                        borderColor: "rgba(251, 213, 158, 255)",
                        borderWidth: 1,
                        pointBorderColor: "rgba(251, 213, 158, 255)",
                        pointBackgroundColor: "rgba(251, 213, 158, 255)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(240, 167, 135,1)",//151,187,205,
                        pointBorderWidth: 1,
                        pointRadius: 0,
                        fill: false,
                        data: [],//[20, 20, 20, 20, 20, 20, 20],
                    }]
                };

                var lineOpts = {
                    responsive: true,
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            type: 'time',
                            distribution: 'linear',
                            time: {
                                unit: 'hour',//'day'
                                displayFormats: {
                                    //day: 'D MMM'
                                    day: 'h:mm'
                                },
                                unitStepSize: 1
                            },
                            gridLines: {
                                color: "rgba(0,0,0,0.1)",
                                drawOnChartArea: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: moment().format('DD MMMM').toString()//'Mars'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                color: "rgba(255,255,255,0.05)",
                                fontColor: '#ffffff',
                                display: false,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Valeur(%)'
                            },
                        }]
                    }
                };

                this.respChart($("#IDCProfile"), Obj, 'Line', lineChart, lineOpts);
                IDCProfile_Linechart = Obj.chart;

                //creating linechart
                var lineChart = {
                    labels: [],
                    datasets: [{
                        label: "IDD",
                        backgroundColor: "rgb(255, 255, 255, 0.0)",//"rgba(135, 197, 255, 0.31)",//38, 185, 154,
                        borderColor: "rgba(230, 22, 78, 0.8)",//"rgba(252, 184, 183, 255)",//135, 197, 255
                        borderWidth: 1,
                        pointBorderColor: "rgba(230, 22, 78, 0.8)",//"rgba(252, 184, 183, 255)",
                        pointBackgroundColor: "rgba(230, 22, 78, 0.8)",//"rgba(252, 184, 183, 255)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(220,220,220,1)",
                        pointBorderWidth: 1,
                        pointRadius: 1.5,
                        fill: false,
                        data: [],//[31, 74, 6, 39, 20, 85, 7]
                    }, {
                        label: "IDD_Ref",
                        backgroundColor: "rgb(255, 255, 255, 0.0)",//"rgba(55, 159, 255, 0.3)",
                        borderColor: "rgba(251, 213, 158, 255)",
                        borderWidth: 1,
                        pointBorderColor: "rgba(251, 213, 158, 255)",
                        pointBackgroundColor: "rgba(251, 213, 158, 255)",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(240, 167, 135,1)",//151,187,205,
                        pointBorderWidth: 1,
                        pointRadius: 0,
                        fill: false,
                        data: [],//[20, 20, 20, 20, 20, 20, 20],
                    }]
                };

                var lineOpts = {
                    responsive: true,
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            type: 'time',
                            distribution: 'linear',
                            time: {
                                unit: 'hour',//'day'
                                displayFormats: {
                                    //day: 'D MMM'
                                    day: 'h:mm'
                                },
                                unitStepSize: 1
                            },
                            gridLines: {
                                color: "rgba(0,0,0,0.1)",
                                drawOnChartArea: false
                            },
                            scaleLabel: {
                                display: true,
                                labelString: moment().format('DD MMMM').toString()//'Mars'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                color: "rgba(255,255,255,0.05)",
                                fontColor: '#ffffff',
                                display: false,
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Valeur(%)'
                            },
                        }]
                    }
                };

                this.respChart($("#IDDProfile"), Obj, 'Line', lineChart, lineOpts);
                IDDProfile_Linechart = Obj.chart;
            }
            //Pie chart
            /*
                var pieChart = {
                    labels: [
                        "Desktops",
                        "Tablets",
                        "Mobiles",
                        "Mobiles",
                    ],
                    datasets: [
                        {
                            data: [80, 50, 100, 121],
                            backgroundColor: [
                                "#194a8b",
                                "#00264a",
                                "#e3eaef",
                                "#44a2d2",
                            ],
                            borderColor: "#333858",
                            hoverBackgroundColor: [
                                "#194a8b",
                                "#00264a",
                                "#e3eaef",
                                "#44a2d2",
                            ],
                            hoverBorderColor: "#fff"
                        }]
                };
                this.respChart($("#pie"), 'Pie', pieChart);
            */

        },
        $.ChartJs = new ChartJs, $.ChartJs.Constructor = ChartJs

}(window.jQuery),

    //initializing
    function ($) {
        "use strict";
        $.ChartJs.init()
    }(window.jQuery);



