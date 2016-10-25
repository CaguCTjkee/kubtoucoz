{{if $next}}<p>Следующее видео загрузится через <span id="timer_inp">30</span> секунд<span id="secend"></span></p>{{/if}}
<p>Материал успешно добавлен, перейти к просмотру - <a href="{{$news.href}}" target="_blank">{{$news.title}}</a></p>
{{if $next}}
<script type="text/javascript">
    function timer() {
        var obj = document.getElementById('timer_inp');
        var secend = document.getElementById('secend');
        obj.innerHTML--;

        if (obj.innerHTML >= 5 && obj.innerHTML <= 20 || obj.innerHTML == 0)
            secend.innerHTML = '';
        else if (obj.innerHTML == 1 || obj.innerHTML == 21)
            secend.innerHTML = 'у';
        else if (obj.innerHTML > 1 && obj.innerHTML < 5 || obj.innerHTML > 21 && obj.innerHTML < 25)
            secend.innerHTML = 'ы';

        if (obj.innerHTML == 0) {
                setTimeout(function () {}, 1000
                );
            } else {
                setTimeout(timer, 1000);
            }
        }
        setTimeout(timer, 1000);
</script>
{{/if}}
