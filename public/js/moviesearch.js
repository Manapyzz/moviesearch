$(function () {
    $('form').submit(function () {
        $.ajax({
            url: 'index/search',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data){
                var toPrint = $('#searchBox');
                $('#searchBox').html('');
                for(var i in data.films) {
                    second = data.films[i].duration;
                    minutes = second / 60;
                    second = second % 60;
                    hour = minutes / 60;
                    minutes = minutes % 60;

                    toPrint.append(
                        '<tr><td>' + data.films[i].title + '</td>' +
                        '<td>' + data.films[i].year + '</td><' +
                        '<td>' + data.films[i].synopsis + '</td>' +
                        '<td>' + data.films[i].first_name + ' ' + data.films[i].last_name + '</td>' +
                        '<td>' + Math.trunc(hour) + 'h' + Math.trunc(minutes)  + '</td></tr>');
                }
            },
    
            error: function(data, status, error) {
                var toPrint = 'errorAjax';
                console.log(toPrint);
            }
        });
        return false;        
    });
});