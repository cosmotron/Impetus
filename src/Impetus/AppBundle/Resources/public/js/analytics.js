var Analytics = {
    init: function() {
        google.load('visualization', '1', {'packages':['corechart']});
        google.setOnLoadCallback(Analytics.drawCharts);
    },

    drawCharts: function() {
        Analytics.drawGraduationRatesChart();
    },

    drawGraduationRatesChart: function() {
        var jsonData = $.ajax({
            url: Routing.generate('_analytics_graduation_rates'),
            dataType: 'json',
            async: false
        }).responseText;

        var data = new google.visualization.DataTable(jsonData);

        var chart = new google.visualization.LineChart(document.getElementById('graduation-rates'));
        chart.draw(data, {height: 300, width: 400});
    }
}